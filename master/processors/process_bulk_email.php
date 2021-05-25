<?php
require_once "../controllers/Controllers.php";
require_once "../functions/barcode_functions.php";
//Intstantiate the Generic class
$cookie		    = explode(",", $_COOKIE["siteData"]);
$generic 			= new Generic($cookie[1]);
$post    			= (object)$_POST;
$db						= $generic->connect();
$uri					= $generic->getURIdata();

$company = json_decode($generic->company());
extract($_POST, EXTR_OVERWRITE);
$param = $param[$pageType];
extract($param, EXTR_OVERWRITE);
$sql = "SELECT $actionCol FROM $table WHERE $primary_key IN ($filter_checkbox)";
$query = $db->query($sql) or die($db->error);
if($query->num_rows){
    while($row = $query->fetch_assoc()){
        $email = $row[$actionCol];
        $data = [
            'subject'=>$subject,
            'body'=>$message,
            'from'=>$company->email,
            'to'=>$email,
            'from_name'=>$company->name,
            'address'=>$company->address,
            'to_name'=>"",
            'user_id'=>$primary_key,
            'site'=>$site
        ];
       sendMail($data);
    }
    echo "Successful";
}
$db->close();
?>
