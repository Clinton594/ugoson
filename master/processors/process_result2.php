<?php if(isset($_POST['mainPageType'])) $_POST['pageType']=$_POST['mainPageType']; 
require_once '../database/connect.php';
require_once "select_page.php";
require_once "get_param.php";
require_once "table_func.php";
require_once "office_extractor.php";
require_once "fileserver.php";
$vt=extractForm($form);
$coldesc=$vt['desc'];
$columns=$vt['col'];
$duplicate=0; $insert=0;
/*print_r($_POST);
print_r($_GET);
print_r($_FILES);
//die();*/
extract($_POST, EXTR_OVERWRITE);
if(isset($_FILES["datafile"])){
	
	$filename = "temp/".$_FILES["datafile"]['name'];
	if(!is_dir("temp/")){mkdir("temp/",0777,true);}
	$realfile=$_FILES["datafile"]['name'];
	if(!file_exists($filename)) move_uploaded_file($_FILES["datafile"]['tmp_name'],$filename) or die('error moving file');
	$ext = strtolower(pathinfo($realfile, PATHINFO_EXTENSION));
	if($ext == 'xlsx'){
		$data = extract_xlsx($filename);
		$t = smart_result_scanner($data);
		/*$count = 0;
		foreach ( $data as $r => $row ) {
			if($r == 0 || $r == 1)continue;
			if($r > 3){
				$row[1] = strtoupper($row[1]);
				$row[2] = strtoupper($row[2]);
			}
			$t[] = $row;

			$count++;
		}*/
	}
	require_once "confirm_result.php";
} else if(isset($_POST["link_submitsheet"])){
	if(!file_exists($filename)){die("file does not exist");}
	$data = extract_xlsx($filename);	  
	$rowcount=count($data);
	$ext = strtolower(pathinfo($realfile, PATHINFO_EXTENSION));
	if($ext !== 'xlsx')die('Use a "xlsx" file format');
	foreach ( $data as $r => $row ) {
		if($r <= 2)continue;
		$row[1] = strtoupper($row[1]);
		$row[2] = strtoupper($row[2]);
		$tds = count($row);
		unset($row[$tds-1]);
		unset($row[$tds-2]);
		$t[] = $row;
		$count++;
		
	}
	$realrow=0;
	$results = [];

	foreach($t as $line => $row){
	  if($line == 0)continue;
	  $line = $line+1;
	  if(isset(${"rcbox".$line})){
		  if(empty($row[1]))die("S/N {$row[0]} has no exam number and can't be submited, uncheck it to continue");
		  foreach($row as $i => $box){
			  if($i>2 && isset(${"s".$i})){
				  $result = [];
				  $subj = explode('~', ${"s".$i});
				  $result['code'] = trim($subj[0]);
				  $result['exam_no'] = trim($row[1]);
				  $result['student_name'] = trim($row[2]);
				  $result['year'] = $year;
				  $result['school_id'] = $school_id;
				  $result['classs'] = $class;
				  $result['term'] = $term;
				  $result['subject'] = trim($subj[1]);
				  $ca1 = empty($box) ?  0 :$box;
				  $ca2 = empty($row[$i+1]) ?  0 :$row[$i+1];
				  $exam = empty($row[$i+2]) ?  0 :$row[$i+2];
				  $total = $ca1 + $ca2 + $exam;
				  $result['result']=json_encode(["{$t[0][$i]}"=>$ca1,"{$t[0][$i+1]}"=>$ca2,"{$t[0][$i+2]}"=>$exam,"{$t[0][$i+3]}"=>'']);
				  $results[] = $result;
			  }
		  }
	  }
	}
	//print_r($results);die();
	foreach($results as $one => $result){
		$result = (object) $result;
		$query =  $db->query("SELECT exam_no FROM results WHERE code='{$result->code}' AND school_id='{$result->school_id}' AND class='{$result->classs}' AND year='{$result->year}' AND exam_no='{$result->exam_no}' AND term='{$result->term}'")or die($db->error) ;
		if($query->num_rows > 0 ){
			$duplicate++;
		}else{
			//die;
			$query =  $db->query("INSERT INTO results (code, exam_no, student_name, year, school_id, class, term, subject, result) VALUES('{$result->code}','{$result->exam_no}','{$result->student_name}','{$result->year}','{$result->school_id}','{$result->classs}','{$result->term}','{$result->subject}','{$result->result}' )") or die($db->error) ;
		}
	}
	 //unlink($filename);
	if($duplicate > 0){
		echo $duplicate.' Duplicate(s)';
	}else{
		echo'submitted';
	}
	die();
	  $msg=urlencode( "$insert row(s) inserted. $duplicate duplicate row(s) found");
		$loc=explode("?",$referer);
		header("Location:{$loc[0]}?msg=$msg&pageType=$_pageType");
		
		
}else if(isset($_POST['loadresults'])){
	$loadData = [];
	$query = "SELECT * from results WHERE class='$class' AND school_id='$school_id' AND year='$year' AND term='$term'";
	$get = $db->query($query) or die($db->error);
	$loadData['result'] = [];
	if($get->num_rows > 0){
		while($row = $get->fetch_assoc()){
			$row['result'] = json_decode($row['result'], true);
			$results[] = $row;
		}
		foreach($results as $result){
			$pupil = $result['exam_no'];
			$subject = $result['code'];
			$loadData['result'][$pupil][] = array('grade'=>$result['result'],'name'=>$result['student_name'],'subject'=>$result['subject'], 'id'=>$result['id']);
		}
	}
	if(isset($_POST['getclass'])){
		$query = "SELECT * from class WHERE school_id='$getclass' ORDER BY level ASC";
		$get = $db->query($query) or die($db->error);
		$loadData['classes'] = [];
		if($get->num_rows > 0){
			while($row = $get->fetch_assoc()){
				$loadData['classes'][] = $row;
			}
		}
	}
	echo json_encode($loadData);
}else if(isset($_POST['updateResult'])){
	$query = "SELECT result from results WHERE id='$id'";
	$get = $db->query($query) or die($db->error);
	if($get->num_rows > 0){
		while($row = $get->fetch_assoc()){
			$result = json_decode($row['result'], true);
		}
		$result[$name] = $grade;
		$newR = json_encode($result);
		$upd = $db->query("UPDATE results SET result='$newR' WHERE id='$id'") or die($db->error);
		if(isset($upd))echo 1;
	}
}else if(isset($_POST['deleteResult'])){
	
}else if(isset($_POST['getAnnual'])){
	$loadData = [];
	$query = "SELECT * from results WHERE (term='1' OR term='2' OR term='3') AND class='$class' AND school_id='$school_id' AND year='$year'";
//	die($query);
	$get = $db->query($query) or die($db->error);
	if($get->num_rows > 0){
		while($row = $get->fetch_assoc()){
			$grade = json_decode($row['result'], true);$gr = 0;$grr = 1;
			foreach($grade as $g){if($grr <=3){$gr = $gr+$g;}$grr++;}
			$pupil = trim($row['exam_no']);
			$name = trim($row['student_name']);
			$subject = $row['subject'];
			$term = $row['term'];
			$loadData[$pupil.'|'.$name]['term'.$term][] = array('grade'=>$gr,'subject'=>$subject, 'id'=>$row['id']);
		}
	}
	echo json_encode($loadData);
}

function smart_result_scanner($excelData){
	global $db; $nameAndNumber = 0; $fixed = array('name of','exam no'); $courses = [];
	
	$name_rows = []; $name_row_count = 0; $name_row = [];
	$all = $db->query("SELECT name FROM subject ORDER  BY name DESC") or die($db->error);
	while($row = $all->fetch_assoc()){$courses[] = trim($row['name']);}
	$valcount = []; $course_rows = []; $course_row_count = 0; $course_row = [];
	//First Loop through data
	foreach($excelData as $c => $row){
		$count = count($row); $valcount[$c] = 0; $course_count = 0;//Intialize counters per row
		for($i=0; $i<=$count; $i++){
			if(!empty(trim($row[$i])))$valcount[$c]++;//number of non-empty values per row
			$row[$i] = strtoupper(trim($row[$i]));
			if(arraysearch_match($courses, $row[$i]))$course_count++;
			if(arraysearch_match($fixed, $row[$i])){$name_rows[$c][$i] = $row[$i];}
		}
		if($course_count > 3){$course_row_count++; $course_rows[]=$c;}
	}
	$first_subject_row = $course_rows[0];
	$first_name_row = array_keys($name_rows)[0];//First reoccurence key of "Exam No and Name of pupil"
	list($name, $num) = array_keys($name_rows[$first_name_row]);
	//Second Loop through data
	foreach($excelData as $c => $row){
		if($first_subject_row == $c){//Build course_row for rowspaning from course_rows
				
		}
		if((!empty($row[$name]) && !empty($row[$num])) || ($valcount[$c] > (count($row)/5))){
			$key=array_search($c,$course_rows);
			if($key === false || $c == $first_subject_row){
				$z[] = $row;
			}
		}
	}
	
	//echo json_encode(array_keys($name_rows[$first_name_row]));//die();
	return($z);
}




function arraysearch_match($arr, $str){
	foreach($arr as $k => $v){
		similar_text(strtolower($v), strtolower($str),$a[$k]);
	}
	$match=max($a); //$key=array_search($f,$a);
	if($match >= 50){
		return(1);
	}else{return(0);}
}
$db->close();
?>