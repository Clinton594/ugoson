<?php if(isset($_POST['mainPageType'])) $_POST['pageType']=$_POST['mainPageType'];
require_once '../database/connect.php';
//require_once "get_school_info.php";
require_once "select_page.php";
//require_once "get_picture.php";
require_once "get_param.php";
require_once "table_func.php";
require_once "office_extractor.php";
require_once "fileserver.php";
if(isset($form)){
	$vt=extractForm($form);
	$coldesc=$vt['desc'];
	$columns=$vt['col'];
	$duplicate=0; $insert=0;
}

/*print_r($_POST);
print_r($_GET);
print_r($_FILES);
//die();*/
if(isset($_FILES["datafile"]))
{

	extract($_POST, EXTR_OVERWRITE);

	  $filename = "temp/".$_FILES["datafile"]['name'];
	  if(!is_dir("temp/")){mkdir("temp/",0777,true);}
	  $realfile=$_FILES["datafile"]['name'];
	  /*if(!file_exists($filename)) */move_uploaded_file($_FILES["datafile"]['tmp_name'],$filename) or die('error moving file');

	  if($handle = fopen($filename, "rb")) ; else die("file can't open");
	  $contents = fread($handle, filesize($filename));
	  fclose($handle);
	  $line=explode("\r",$contents);
	  $count=0;
	  $ext = strtolower(pathinfo($realfile, PATHINFO_EXTENSION));
	  if($ext=="csv"){
		  $xd=",";
		  for($i=0;$i<count($line)-1;$i++){
				  $t[]=explode("$xd",str_replace("'","~",str_replace('"',"",$line[$i])));
				  $count++;

		  }
	  }else if($ext=="xlsx"){
		  $data = extract_xlsx($filename);
		  $count = count($data);
		  $t = $data;
	  }

	   require_once "confirm_load.php";
} else if(isset($_POST["link_submitsheet"]))
{
	//print_r($_POST);die();
	  extract($_POST, EXTR_OVERWRITE);
  	  $errorlog="";$order =[];
	  if(!file_exists($filename)){die("file does not exist anymore, re-upload to continue");}

	  $count=0; $header=-1;	$namecol=-1;$refcol=-1;$count5=0; $course_level=1;$course_term=1;
	  $subquery='';
		$ext = strtolower(pathinfo($realfile, PATHINFO_EXTENSION));
		if($ext=="csv"){
			if($handle = fopen($filename, "rb")) ; else die("file can't open");
			$contents = fread($handle, filesize($filename));
			fclose($handle);
			$line=explode("\r",$contents);
			$rowcount=count($line);
		  	$xd=",";
			for($i=0;$i<count($line)-1;$i++){
			  $t[]=explode("$xd",str_replace("'","~",str_replace('"',"",$line[$i])));
			  $colcount[]=count($t[$count]);
			  $count++;
			}
		}else if($ext=="xlsx"){
		  $data = extract_xlsx($filename);
		  foreach($data as $count => $row){
			  $t[] = $row;
			  $colcount[]=count($row);
		  }
		  $rowcount = count($t);
		}
	  $realrow=0;
	  $table_fields=getFields($table);

	  for($i=1;$i<$rowcount-1;$i++)
	  {
		  if(isset(${"rcbox".$i}))
		  {
			  //echo(${"rcbox".$i});
			  $test=0; $realcol=0;$tx[$realrow]=array();
			  for($j=0;$j < $colcount[$i];$j++)
			  {

				 if($colcount[$i]>1)
				{
					if(isset(${"s".$j})){
						$_pc=${"s".$j};

						if($_pc !="")$scol=$columns[$_pc];else continue;

						if(isset($_explode) && isset($_explode[$scol]))
						{
							$delim=$_explode[$scol]['delimiter'];
							$dcol=$_explode[$scol]['col'];
							$pr=explode($delim,$t[$i][$j]);
							$cr=explode(',',$dcol);
							foreach($pr as $k0=>$v0)
							{
								if(isset($cr[$k0]) && array_search(strtolower($cr[$k0]),$table_fields) !==false)
								{
									$tx[$realrow][]=$cr[$k0]."='".$db->escape_string($v0)."'";
								}
							}

						}

						if(array_search(strtolower($scol),$table_fields) ===false)continue;
						if( isset($order[$_pc]) && $order[$_pc]=='s' && !empty($source[$_pc]))
						{
							$tx[$realrow][]=$scol."='".$db->escape_string(getId($t[$i][$j],$source[$_pc]))."'";
						}else $tx[$realrow][]=$scol."='".$db->escape_string($t[$i][$j])."'";

					   $realcol++;
					}

				}
			  }

			 foreach($_POST as $k0=>$v0)
			 {
			 	if(array_search(strtolower($k0),$table_fields) ===false)continue;

				   $tx[$realrow][]=$k0."='".$db->escape_string($v0)."'";

			 }
			 // print_r($tx[$realrow]);
			if(!empty($tx[$realrow]))
			{
				$cxr=implode(' and ',$tx[$realrow]);
				$txr=implode(',',$tx[$realrow]);
				$check=$db->query("select $primary_key from $table where {$cxr}") or die($db->error);
				if($row2=$check->fetch_assoc())
				{
					 $duplicate ++;
				} else
				{
						$query="insert into $table set $txr";
						$result=$db->query($query) or die($db->error);
						$insert ++;

				}

				$check->free_result();
			}
		  }

		  $realrow++;
	  }
	 unlink($filename);
	  for($i=0;$i<$realrow;$i++)
	  {

	  }
	if($duplicate > 0){
		echo $duplicate.' Duplicate(s)';
	}else{
		echo'submitted';
	}
	die();
	  $msg=urlencode( "$insert row(s) inserted. $duplicate duplicate row(s) found");
		$loc=explode("?",$referer);
		header("Location:{$loc[0]}?msg=$msg&pageType=$_pageType");


}else if(isset($_GET['get_ext'])){
	$retun = [];
	foreach($_POST as $one => $details){
		$retun[$one] = [];
		$wheres = explode(',',$details['where']);
		$sort = $details['sort'] ? "ORDER BY {$details['sort']}" : '';
		$where = '';
		foreach($wheres as $e){
			if($e == 'this-hierachy'){$where .= " AND {$details['hierachy_name']}='{$details['hierachy']}'";}
			else{$ee = explode('=',$e);$where .= " AND {$ee[0]}='{$ee[1]}'";}
		}
		$where = substr($where,4);
		$con = $db->query("SELECT id,{$details['column']} FROM {$details['table']} WHERE $where $sort") or die($db->error);
		while($row = $con->fetch_assoc()){
			$retun[$one][] = [$row['id'], $row[$details['column']]];
		}
	}
	$ff = json_encode($retun);
	echo $ff;
	$db->close();
}

?>
