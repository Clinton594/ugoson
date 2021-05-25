<?php
$json					=	array();
$sort_col 	  = $sort = $sort_col2="";

if (!empty($post->mobile)) {$session->user_id = $post->user_id;}
// $session->user_id = 1;
$generic 	    = new Auth($session->user_id, "admin_signin");
$user					= $generic->user();
//Pass the generic object into the paramController
$paramControl = new ParamControl($generic);
$params 			= $paramControl->get_params();
$sort_col 		= !empty($param->sort) ? $param->sort : $sort_col;
$sources      = $paramControl->load_sources();
$case         = $uri->event;
if(!empty($post->pageType) && isset($params->{$post->pageType})){
  $param        = $params->{$post->pageType};
}else $param = new stdClass;

switch ($case) {
  // Get Form Data
  case "loadform":
    require_once "../controllers/FormControl.php";
    if (!empty($param->table)) {
      $data 		= $generic->getFromTable($param->table, "{$param->primary_key}={$post->id}", 1, 1);
    	$formelem	= $paramControl->extractFormElements($param->form);
      $form_filter = "";
      if(count($data)){
    		$data = reset($data);
    		$data = array_map("utf8ize", (array)$data);
    		foreach($formelem as $fk=> $value){
    			if(isset($value->type) && $value->type == "richtext-body"){
    				$value->column = strtolower($value->column);
    				$data[$value->column] = stripslashes(html_entity_decode(utf8_decode($data[$value->column])));
    			}
          if(isset($value->type) && $value->type == "transactions"){
            $form_elments = $paramControl->extractFormElements($params->{$value->source}->form);
      			$trans_row = array_values(array_column($form_elments, "column"));
            $trans_row  = array_merge($trans_row, ["tid","sub"]);
            if(!empty($param->form->form_filter))$form_filter = ",{$param->form->form_filter}";
            $trans = $generic->getFromTable($param->table, "trans_no={$data['trans_no']}, sub>0 $form_filter", 1, 0, SUB_ASC);
            $trans = array_map(function ($row){
              global $trans_row;
              return array_extract($row, $trans_row, true);
            }, $trans);
            $data[$value->type] = $trans;
          }
    		}
        if(!empty($param->form->post_select_function)){
          $data =  call_user_func_array($param->form->post_select_function, [$data]);
        }
    		$json[]=$data;
      }
      $response = $json;
    }
  break;
  case 'delete':
    if(!empty($user->accesslevel) && $user->accesslevel >= 2){
      if(!empty($param->pre_delete_function)){call_user_func_array($param->pre_delete_function, [$post->delIds]);}
      $_ittitle = $param->display_fields[0]->column;
      $_del = explode(',', $post->delIds);
      $build = [];
      foreach($_del as $itemid){
        $elements = [];
        if(!empty($param->form)){
          $elements = $paramControl->extractFormElements( $param->form);
          $elements = array_filter($elements, function ($stack){
            return ($stack->type == "text" || $stack->type == "richtext-title") ? 1 : 0 ;
          });
          $elements = array_values($elements);
        }
        $title = !empty($elements[0]) ? $elements[0]->column : $_ittitle;
        //Build logging query
        $action = "deleted";
        $row 	= $generic->getFromTable($param->table, "{$param->primary_key}={$itemid}",1,1)[0];
        $descr	=	$row->{$title};
        $logger = (object)[
          "user_id" 	=> $user->primary_key,
          "action" 		=> $action,
          "location"	=> $param->table,
          "location_id"	=> $itemid,
          "submitType"=> "insert",
          "type" 			=> "admin",
          "description" => substr("{$user->name_col} $action {$descr} from {$param->page_title}", 0, 240)
        ];
        $logParam = $paramControl->get_params("log");
        $generic->insert($logger, $logParam);
        $row->pageType = $post->pageType;
        $build[$itemid] = $row;
      }
      $query="delete from {$param->table} where {$param->primary_key} in ({$post->delIds})";
      $result = $db->query($query) or die($db->error);
      if(!empty($param->post_delete_function)){
        $json =  call_user_func_array($param->post_delete_function, [$build]);
      }
      if(empty($json))$json=["status"=>1, "message"=>"Successfully Deleted", "color"=>"green"];
    }else $json=["status"=>0, "message"=>"Permission not granted", "color"=>"red"];
    $response = $json;
  break;
  case 'void':
    if(!empty($user->accesslevel) && $user->accesslevel >= 2)	{

    }
  break;
  case 'list':
    if(isset($param->table)){
      $coldesc = $column = $element = $tabs = array();
      $cp      = $param->display_fields;
      $param->table_fields  = $generic->getTableFields($param->table);
      foreach($cp as $k=> $v2)	{
        $column[$k]   = strtolower($v2->column);
        $coldesc[$k]  = !empty($v2->description) ? $v2->description : '';
        $element[$k]  = !empty($v2->component) ? $v2->component : '';
        $actions[$k]  = !empty($v2->action) ? $v2->action : '';
        $source[$k]   = !empty($v2->source) ? $v2->source : '';
        $class[$k]    = isset($v2->class) ? $v2->class : '';
        $filter[$k]   = !empty($v2->filter) ? $v2->filter : '';
        if(isset($v2->table)){$tabs[$k] = $v2->table; }
      }
      $json["desc"]= $coldesc;
      $json["col"] = $column;
      $json["fmt"] = $element;
      $json["ord"] = $actions;
      $json["cls"] = $class;
      $json["ext"] = array();
      if(!empty($param->extension) && gettype($param->extension) == "array"){
        $ext = $param->extension;
        foreach($ext as $k => $ext_param_name){
          $thisparam = $paramControl->get_params($ext_param_name);
          if(empty($thisparam))continue;
          unset($thisparam->form->sections);
          $thisparam->pageid = strToFilename("_{$post->pageType}");
          $thisparam->name = $ext_param_name;
          $thisparam->form_views = ["swipe", "modal", "extension"];
          // add $thisparam->pageid ot the formid because this is how the href locates the id of the form when formInitialize has auto appended a formid to all ids
          $thisparam->formId = strToFilename("newextform{$k}_{$ext_param_name}");
          $thisparam->formId = "{$thisparam->formId}{$thisparam->pageid}";
          $json["ext"][] = $thisparam;
        }
      }
      $col=""; $val =""; $ct=0;

      foreach($column as $k => $v)	{
        //column exists in database table
        if(array_search(strtolower($v), $param->table_fields) !==false) {
          if($ct){
            $col .=",";
            if(!empty($post->search) && empty($post->search_cols))$val .=" or ";
          }
          $col .= "`{$v}`";
          if(!empty($post->search)  && empty($post->search_cols))$val .="$v LIKE '%{$post->search}%'";
          if($sort_col=="") $sort_col=$v;
          $sort_col2 .= ", `{$v}`";
          $ct++;
        }
      }

      if(!empty($post->search_cols)&& !empty($post->search)){
        $_search_cols = [];
        $post->search_cols = array_map("trim", explode(",", $post->search_cols));
        foreach ($post->search_cols as $key => $search_col) {
          $_search_cols[] ="$search_col LIKE '%{$post->search}%'";
        }
        $val .= implode(" or ", $_search_cols);
      }

      $text="";
      if(!empty($post->condition))
      {
        $cnd=explode("|",$post->condition);
        foreach($cnd as $k => $v)
        {
          $combine="or";
          $tx=explode(",",$v);
          if(empty($tx[1])) continue;
          if(isset($tx[2]) && $tx[2]=="date"){
            $combine="and";
          }
          if($text !="") $text .=" $combine ";
          if(isset($tx[2]) && $tx[2]=="negate")
          {
            $text .=$tx[0]. "<>'". $tx[1]. "'";
          }else if(isset($tx[2]) && $tx[2]=="null")
          {
            $text .=$tx[0]. " is null ";
          }else if(isset($tx[2]) && $tx[2]=="not")
          {
            $text .=$tx[0]. " not like '". $tx[1]. "'";
          } else if(isset($tx[2]) && $tx[2]=="start")
          {
            $text .=$tx[0]. " like '". $tx[1]. "%'";
          } else if(isset($tx[2]) && $tx[2]=="end")
          {
            $text .=$tx[0]. " like '%". $tx[1]. "'";
          } else if(isset($tx[2]) && $tx[2]=="contain")
          {
            $text .=$tx[0]. " like '%". $tx[1]. "%'";
          }else if(isset($tx[2]) && $tx[2]=="greater")
          {
            $text .=$tx[0]. " > '". $tx[1]. "'";
          }else if(isset($tx[2]) && $tx[2]=="less")
          {
            $text .=$tx[0]. " <'". $tx[1]. "'";
          }else if(isset($tx[2]) && $tx[2]=="date")
          {
            $text .=getDateValue($tx[1],$tx[0]);
          }
          else {
            $text .=$tx[0]. "='". $tx[1]. "'";
          }

        }
      }
      if(!empty($param->retrieve_filter))
      {
        if($text !="" ) $text .=" and ";
        $temp = str_replace(' and ', ',', $param->retrieve_filter);
        if(stripos($param->retrieve_filter, "(") !== false){
          $text .= $param->retrieve_filter;
        }else{
          $text .= $generic->filterQuery($temp, $param->table);
        }
      }
      if(isset($post->dateRange))
      {
        if($text !="" && $post->dateRange !="All Time" ) $text .=" and ";
        $text .=getDateValue($post->dateRange, $post->dateColumn);
      }
      if(isset($post->page) && isset($post->limit))
      {
        $lt=($post->page - 1) * $post->limit;
        if($lt <0) $lt=0;
        $limit_query= " LIMIT {$lt} , {$post->limit} ";

      }else if(isset($post->limit))
      {
        $limit_query= "LIMIT {$post->limit} ";
      } else $limit_query= "";

      if(!empty($val)) $val = "where ($val) ";
      if(!empty($val) && !empty($text)) $val .= "and $text";  else if(empty($val) && !empty($text)) $val = "where $text";
      if(!empty($param->primary_key) && !empty($pkey)) $val ="where {$param->primary_key}='{$pkey}'";
      if(!empty($col))
      {
        if(!empty($param->primary_key)) $col= "{$param->primary_key}, {$col}";
        $sort_col = empty($sort_col) ? "{$param->primary_key} DESC" : $sort_col;
        $sql = "SELECT {$col} FROM {$param->table} {$val} order by {$sort_col} {$limit_query}";
        $result    = $db->query($sql) or die($db->error.$sql);
        $actions_  = $actions;   array_unshift($actions_, "id");
        $source_   = $source;    array_unshift($source_, "id");
        $column_   = $column;    array_unshift($column_, "id");
        while($row = $result->fetch_assoc()){
          $r=array();
          $r["i"]=$row[$param->primary_key];
          $c = 0;
          //Go thru each column of the rows
          $predefinedactions = ["select", "date", "datetime", "time"] ;
          foreach($row as $k => $colvalue){
            //    print_r($colvalue);
            $mk = array_search($k, $column);
            if($mk !== false){//if fetched column name exists in column array, get the position
              if($actions[$mk]=='select') {
                // see($row, false);
                if(empty($colvalue))$colvalue = intval($colvalue);
                if(!empty($colvalue) || $colvalue===0)$colvalue = $paramControl->load_data_from_param($source_[$c], $colvalue);
                if(gettype($colvalue) == "array" || gettype($colvalue) == "object"){
                  if(gettype($colvalue) == "object")$colvalue = json_decode(json_encode($colvalue), true);
                  unset($colvalue[$param->primary_key]);
                  $colvalue = implode(" ", array_values($colvalue));
                }
              }
              else if($actions[$mk]=='date') {$date = new DateTime($colvalue); $colvalue = $date->format('F jS, Y');}
              else if($actions[$mk]=='datetime') {$date = new DateTime($colvalue); $colvalue = $date->format("j-M-y g:ia");}
              else if($actions[$mk]=='time') {$date = new DateTime($colvalue); $colvalue = $date->format("g:ia");}
              else if(!empty($actions[$mk]) && !in_array($actions[$mk], $predefinedactions) && empty($tabs[$mk])){
                $colvalue =  call_user_func_array($actions[$mk], [$colvalue, $row]);
              }
              $r["c"][$mk] = utf8ize($colvalue);
            }
            $c++;
          }

          foreach($tabs as $k => $v){
            $filt = !empty($filter[$k]) ? "AND $filter[$k]" : "";
            $query1="select {$actions[$k]}({$column[$k]}) as {$column[$k]} from $v where {$column[$k]}='{$row[$param->primary_key]}' $filt";
            $result1=$db->query($query1) or die($db->error.$query1);
            while($row1=$result1->fetch_assoc()){
              $r["c"][$k] = $row1[$column[$k]];
            }
            $result1->free();
          }
          $json["row"][]=$r;
        }

        $g = "SELECT count({$param->primary_key}) as count FROM {$param->table} $val";
        $result2 = $db->query($g) or die($db->error." ($g)");
        if($rwo = $result2->fetch_assoc()){
          $json["total"]=intval($rwo['count']);
        }
        $result->free();
        $result2->free();
      }
    }else if(!empty($sources->{$post->pageType})){
      $r=array();
      $r["i"] = $post->pageType;
      $list = $sources->{$post->pageType};
      $c = 0;
      foreach ($list as $key => $value) {
        $r["row"][$c]["i"]=$key;
        $r["row"][$c]["c"][0] = [$value];
        $c++;
      }
      $json["row"] = $r;
    }
    $response = $json;
  break;
  // All generic backend select elements and Combos
  case "dropdown":
    $result = [];
    if(!empty($_POST["data"]))$_POST = json_decode($_POST["data"], true);

    foreach($_POST as $id => $arr){
      $saved = $arr_data = $paramControl->source_decode($arr['source']);
      // For fillups
      if(!empty($arr["action"]) && $arr["action"] == "fillup"){
        $param = $paramControl->get_params($arr_data['pageType']);
        $arr_data["retrieve_filter"] = "{$param->primary_key}='{$arr['value']}'";
        $arr_data["usekey"] = true;
      // for hierachy
      }else	if(isset($arr['value']) && isset($arr_data["column"])){
        $arr_data["retrieve_filter"] = "{$arr_data["column"]}='{$arr['value']}'";
        unset($arr_data["column"]);
      }
      $data = $paramControl->load_data_from_param($arr_data);

      if(!empty($arr["action"]) && $arr["action"] == "fillup"){
        $data = array_extract($data[array_keys($data)[0]], explode(",", $saved["column"]), true);
      }
      $result[$id] = $data;
    }
    $response = $result;
  break;
  case 'getParameters':
    if(!empty($post->pageParam)){
			$response = $paramControl->get_page_param($post->param);
		}else if (!empty($post->pageType)) {
      $response = $param;
    }else{
      $response = $params;
    }
	break;
	case 'startUp':
		$response = [
			"params"=>$paramControl->get_params(),
			"sidebar"=>$paramControl->get_page_param("roles"),
			"company"=>$company,
			"session"=>$session,
		];
	break;
  case 2.1:
    $id = implode(",", $id);
    $source = (object)$paramControl->source_decode($source);
    if($param->table){
      $response->status = $db->query("DELETE FROM {$param->table} WHERE {$param->primary_key} IN ('{$id}')");
      if(!$response->status)$response->message = $db->error;
    }
  break;
  default:
    $response->message = "Unrecognized Ajax Case";
  break;
}
$generic->closeDB();
?>
