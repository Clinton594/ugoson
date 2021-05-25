<?php
require_once "Generic.php";

class Ecommerce extends Generic {
  public function __construct($backend = "") {
    parent::__construct($backend);
  }

  public function getCategories($sort = SOLD_DESC) {
    $sort = empty($sort) ? SOLD_DESC : $sort;
    $result=[];
    $sql1 =  self::$mydb->query("SELECT  typeid as id, name, menu_image FROM category_type WHERE parent = '0' AND category='2'") or die(self::$mydb->error);
    if($sql1->num_rows){
      while($row = $sql1->fetch_assoc()) {
        $row = (object) $row;
        $rowtypeid = $row->id;
        $sql2 = self::$mydb->query("SELECT  typeid as id, name, menu_image FROM category_type WHERE parent = '$rowtypeid'") or die(self::$mydb->error);;
        if($sql2->num_rows){
          while($row2 = $sql2->fetch_assoc()) {
            $row->child[] = (object) $row2;
          }
        }
        $result[] = $row;
        $sql2->free();
      }
      $sql1->free();
    }
    return($result);
  }

  public function money($number){
    return '&#8358;'.number_format((int)$number);
  }

  public function getItem($id) {
    $result=[];
    $id = intval($id);
    $row = $this->getFromTable("item", "tid={$id}, master_stock_id=0, inactive=1");
    if(count($row)) {
      $row = $row[0];
      $row->category_name = null;
      $categ = $this->getFromTable("category_type", "typeid={$row->categories}");
      if (count($categ)) {
        $row->category_name = $categ[0]->name;
      }
      if(intval($row->is_master_stock) === 1){
        $cols = "slave_stock_id,tid, price1, primary_attrib_name as primary_title, substock_primary_attrib_desc as primary_desc, secondary_attrib_name as secondary_title, substock_second_attrib_desc as secondary_desc";
        $master = $this->getFromTable("item", "master_stock_id={$row->$id}", 1, 0, null, false, $cols);
        $row->children = $master;
      }
      $row->picture = json_decode($row->picture);
      $row->picture = (empty($row->picture)) ? ["0"=>['src'=>$this->domain.$this->backend."images/default.jpg","title"=>"","desc"=>""]] : $row->picture;
      $row->sales_desc = json_decode($row->sales_desc);
      $row->sales_desc = empty($row->sales_desc) ? [] : $row->sales_desc;
      $result = $row;
    }
    return json_decode(json_encode($result));
  }

  public function getItems($filter, $page = 1 ,$pagesize=0 , $sort = SOLD_DESC) {
    $sort = empty($sort) ? SOLD_DESC : $sort;
    $page = empty($page) ? 1 : $page;
    $pagesize = empty($pagesize) ? 400 : $pagesize;
    $recordstart = ($page - 1) * $pagesize;
    $_filter = $result = [];
    if(!empty($filter)){
      $filter = parent::filterQuery($filter, "item", false)." AND";
    }
    $cateories = $this->getFromTable("category_type","",1,0,false,["typeid","name"]);
    $cat = new stdClass;
    foreach ($cateories as $key => $value) {
      $cat->{$value->typeid} = $value->name;
    }
    $cols = "tid,itemid,description,upc,categories,pcategories,sunit_value,brand,warehouse,unit,sunit,subdivision,price1,picture,quantity_on_hand,is_master_stock,sold";
    $sql = "SELECT $cols from item WHERE $filter master_stock_id='0' AND publish='1' order by $sort LIMIT $recordstart,$pagesize";
    $sql2 = self::$mydb->query($sql) or die(self::$mydb->error."($sql)");
    if($sql2->num_rows) {
      while ($row = $sql2->fetch_assoc()) {
        $row = (object) $row;
        $row->category_name = null;
        if (!empty($row->categories)) {$row->category_name = $cat->{$row->categories};}
        if (!empty($row->pcategories)) {$row->pcategory_name = $cat->{$row->pcategories};}
        $row->picture = json_decode($row->picture);
        $row->picture = (empty($row->picture) || !file_exist($row->picture[0]->src)) ? ["0"=>['src'=>$this->domain.$this->backend."images/default.jpg"]] : $row->picture;
        // $row->sales_desc = json_decode($row->sales_desc);
        $result[] = $row;
      }
      $sql2->free();
    }
    return json_decode(json_encode($result));
  }

  public function getGroupedItems($groupedBy="parent", $sort, $page, $pagesize = 4) {
    $sort = empty($sort) ? SOLD_DESC : $sort;
    $page = empty($page) || !is_numeric($page) ? 1 : $page;
    $pagesize = intval($pagesize) < 1 ? 4: $pagesize;
    $groupedBy = empty($groupedBy) ? "parent": $groupedBy;
    $groupdata = ["parent"=>["parent='0'","pcategories"], "children"=>["parent!='0'","categories"]];
    $recordstart = ($page - 1) * $pagesize;
    $_filter = $result = [];
    $sql = "SELECT DISTINCT(parent), typeid, name FROM category_type WHERE {$groupdata[$groupedBy][0]} AND category='2' ORDER BY name ASC";
    $sql1= self::$mydb->query($sql) or die(self::$mydb->error);
    if($sql1->num_rows) {
      while ($cate = $sql1->fetch_assoc()) {
        $cate = (object) $cate;
        $result[$cate->name]['name'] = $cate->name;
        $result[$cate->name]['id'] = $cate->typeid;
        $sql = "SELECT * FROM item WHERE {$groupdata[$groupedBy][1]}='{$cate->typeid}' AND master_stock_id='0' AND inactive='1' ORDER BY $sort LIMIT $recordstart, $pagesize";
        $sql2 = self::$mydb->query($sql) or die(self::$mydb->error."($sql)");
        if($sql2->num_rows) {
          while ($row2 = $sql2->fetch_assoc()) {
            $row2 = (object) $row2;
            $row2->category_name = $cate->name;
            if($groupedBy=="parent"){
              $sq=self::$mydb->query("SELECT name FROM category_type WHERE typeid='$row2->categories'");
              $row2->category_name = $sq->fetch_assoc()['name'];
            }
            $row2->picture = json_decode($row2->picture);
            $row2->picture = (empty($row2->picture) || !file_exist($row2->picture[0]->src)) ? ["0"=>['src'=>$this->domain.$this->backend."images/default.jpg"]] : $row2->picture;
            $row2->sales_desc = json_decode($row2->sales_desc);
            $result[$cate->name]['list'][] = $row2;
            if(count($result[$cate->name]['list']) === $pagesize)break;
          }
          $sql2->free();
        }
      }
      $sql1->free();
    }
    return json_decode(json_encode($result));
  }

  public function getUserData($user_id, $get_trans = false) {
    $response = $qrouped = new stdClass;
    $filter = "cid=$user_id";
    $page = 1;
    $profile = Generic::getFromTable("customer", $filter, $page,  1, "cid desc")[0];
    $profile->picture = empty($profile->picture) || !file_exist($profile->picture) ?
      $this->domain.$this->backend."images/c_icon.png" :
      $profile->picture;
    $response = $profile;
    if($get_trans === true){
      //$filter = "trans_type=RCP,sub!=0";//Test get transactions
      $filter = "cid=$user_id, trans_type=ORD, sub > 0";
      $transactions = Generic::getFromTable("transactions", $filter, $page,  0, "date_updated desc");
      foreach ($transactions as $key => $value) {
        if(!empty($value->it_id)){
          $image = Ecommerce::getItem($value->it_id)->picture;
          $date = new DateDifference(date("Y-m-d"), $value->date_created);
          $value->date = $date->smart();
          $value->picture = (empty($image) || !isset($image->picture) || !file_exist($image->picture[0]->src)) ?
          $this->domain.$this->backend."images/default.jpg" :
          $image->picture[0]->src;
          $qrouped->{$value->trans_no}[] = $value;
        }
      }
      $response->transactions = $qrouped;
    }
    return $response;
  }

  public function manageShippingAddress($post, $action = "ADD") {//action = ADD or DELETE
    $response = new stdClass;
    $response->status = 0;
    $post = (gettype($post) == "array") ? (object)$post : $post;
    $validateFields = (object)[
      "ADD" => ['cid','customer','address','telephone','state','country'],
      "DELETE" => ['cid','index'],
    ];
    if($fields = $validateFields->{$action}){
      $ship = new stdClass;
      foreach ($fields as $key) {
        if(!isset($post->{$key})){$response->error = "$key field is not found in 'post'";return $response;}
        elseif(empty($post->{$key})){$response->error = "$key cannot be empty";return $response;}
        else{if($key !== 'cid' && $action == "ADD")$ship->{$key} = $post->{$key};}
      }
      $addresses = self::$mydb->query("SELECT shipping_details FROM customer WHERE cid='{$post->cid}'") or die(self::$mydb->error);
      //see($ship);
      if(!$addd = json_decode($addresses->fetch_object()->shipping_details, true)){$addd = [];}
      if($action === 'ADD'){
        $addd[] = (array)$ship;
        $newadd = json_encode($addd);
        if(self::$mydb->query("UPDATE customer SET shipping_details='$newadd' WHERE cid='{$post->cid}'") or die(self::$mydb->error)){
          $response->status = 1;
          $response->message = "New shipping details successfully added";
        }
      }else{
        if(isset($addd[$post->index]))unset($addd[$post->index]);
        $newadd = json_encode($addd);
        if(self::$mydb->query("UPDATE customer SET shipping_details='$newadd' WHERE cid='{$post->cid}'") or die(self::$mydb->error))$response->message = "This shipping detail successfully deleted";
      }
    }else{
      die("manageShippingAddress() must contain these arguments(post, action); The fields in 'post' is dependent on the type of 'action' it requests. Visit documentation for more details");
    }
    return $response;
  }

  public function getCart(){
    global $db;
    $sum_total = $total_items = $count = 0;
    $a = new stdClass;
    if(isset($_COOKIE['_cartData_'])){
      $cart_items = $_COOKIE['_cartData_'];
      $cookieData = json_decode($cart_items);
      $count = count((array)$cookieData);
      foreach($cookieData as $tid =>$qty){
        $item = $this->getFromTable("item","tid={$tid}", 1, 1, null, false, "tid, subdivision, description, price1, itemid, picture, quantity_on_hand, pcategories");
        if(isset($item[0])){
          $item = $item[0];
          $qty = intval($qty);
          $item->quantity_on_hand = intval($item->quantity_on_hand);
          $qty = ($qty > $item->quantity_on_hand) ? $item->quantity_on_hand : $qty;
          $total_items += $qty;
          $item->picture = json_decode($item->picture);
          $item->picture = (empty($item->picture) || !file_exist($item->picture[0]->src)) ? ["0"=>['src'=>$this->domain.$this->backend."images/default.jpg"]] : $item->picture;
          $item->cart_qty = $qty;
          $item->price = $item->price1 * $qty;
          $a->list[] = $item;
          $sum_total += $item->price;
        }
      }
    }else{
      $a->list = array();
    }
    $a->item_count = "$count";
    $a->total_items = "$total_items";
    $a->sub_total="$sum_total";
    $a->total = (intval($a->sub_total) > 2500) ? ((1.5/100)*$a->sub_total)+$a->sub_total+100 : (1.5*$a->sub_total)+$a->sub_total ;
    return json_decode(json_encode($a));
  }

  public function updateCart($data, $validate_qty = true){
    /*
      > Handles inserting, updating, removing, deleting item from cart and emptying the cart.
      > This is example of the $data it accepts {tid:5856, qty:+2}
      > SET $data to null to empty cart (boolean or string)
      > If $data->qty has -sign, it reduces the quanty
      > If $data->qty has +sign, it increases the quanty
      > If $data->qty has no sign, it sets as the quanty
      > If $data->qty is 0, it deletes the item
    */
    $response = new stdClass;
    $response->status = 0;
    $cookieNum = 0;
    $datatype = gettype($data);
    if($datatype == "array" || $datatype == "object" || $data == null){
      $data = ($datatype == "array") ? (object)$data : $data;
      $cookieData = new stdClass;
      if(isset($data->tid) && isset($data->qty)){
        $qty_on_hand = self::$mydb->query("SELECT quantity_on_hand as qty FROM item WHERE tid={$data->tid}")->fetch_assoc()['qty'];
        $qty_on_hand = intval($qty_on_hand);
        if(isset($_COOKIE['_cartData_'])){
          $cookieData = json_decode($_COOKIE['_cartData_']);
          $cookieNum = count((array)$cookieData);
        }
        if($validate_qty === true && isset($cookieData->{$data->tid})){//item exists in cart
          if(($data->qty[0] == "+" && intval($cookieData->{$data->tid}) > $qty_on_hand) || $data->qty > $qty_on_hand){$response->message = "Quantity exceeds available stock";return $response;}
        }else {//Item not exist in cart
          if($validate_qty === true && cleanUp($data->qty) > $qty_on_hand){$response->message = "Quanty exceeds available stock";return $response;}
        }
        $cookieData->{$data->tid} = ($data->qty < 0 || (isset($data->qty[0]) && $data->qty[0] == "+")) ?
          (!isset($cookieData->{$data->tid}) ? intval($data->qty): $cookieData->{$data->tid}+$data->qty) :  $data->qty;
      }elseif($data != null) {$response->message = "Cart data must have attributes (tid & qty)";return $response;}
      foreach ($cookieData as $tid => $qty) {if($qty < 1)unset($cookieData->{$tid});}
      setcookie("_cartData_", json_encode($cookieData), time()+36000, "/");
      $response->status = 1;
      $response->message = "success";
    }else $response->message = "Cart data-type must be an array or an object or a null value to empty the cart";
    return $response;
  }
}

?>
