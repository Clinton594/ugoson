<?php if(isset($_POST['mainPageType'])) $_POST['pageType']=$_POST['mainPageType']; 
require_once '../database/connect.php';
require_once "select_page.php";
require_once "get_param.php";
require_once "table_func.php";
require_once "office_extractor.php";
require_once "fileserver.php";

$duplicate=0; $insert=0;
/*print_r($_POST);
print_r($_GET);
print_r($_FILES);
//die();*/
extract($_POST, EXTR_OVERWRITE);
if(isset($_FILES["datafile"])){//Verify a resultsheet file
	
	$filename = "temp/".$_FILES["datafile"]['name'];
	if(!is_dir("temp/")){mkdir("temp/",0777,true);}
	$realfile=$_FILES["datafile"]['name'];
	if(!file_exists($filename)) move_uploaded_file($_FILES["datafile"]['tmp_name'],$filename) or die('error moving file');
	$ext = strtolower(pathinfo($realfile, PATHINFO_EXTENSION));
	if($ext == 'xlsx'){
		$data = extract_xlsx($filename);
		$smart = smart_result_scanner($data);
		$colspan = $smart['colspan'];
		$tabledata = $smart['table'];
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
} else if(isset($_POST["link_submitsheet"])){//submit a verified result
	if(!file_exists($filename)){die("file does not exist");}
	$data = extract_xlsx($filename);	  
	$rowcount=count($data);
	$ext = strtolower(pathinfo($realfile, PATHINFO_EXTENSION));
	if($ext !== 'xlsx')die('Use a "xlsx" file format');
	$data = extract_xlsx($filename);
	$smart = smart_result_scanner($data);
	$colspan = $smart['colspan'];
	$t = $smart['table'];
	$name = $smart['others'][0];
	$exam_no = $smart['others'][1];
	$realrow=0;
	$results = [];
	$gradeRow = $t[1];

	foreach($t as $line => $row){
	  if(isset(${"rcbox".$line})){
		  if(empty($row[$name]) || empty($row[$exam_no])){
			  $thisline = $line-1;
			  $title = empty($row[0]) ? "Row $thisline" : "S/N $row[0]";
			  die("$title is not valid for submission, uncheck it to continue");
		  }
		  $student_name = ''; $stu_exam_no = '';
		  foreach($row as $i => $box){
			  if(isset(${"s".$i})){
				  $subj = explode('~', ${"s".$i});
				  $aa = trim($subj[0]);
				  $bb = trim($subj[1]);
				  if($aa != '00' && $aa != '0' && $colspan[$i] > 2){
					  $result = [];

					  $result['code'] = $aa;
					  $result['exam_no'] = trim($row[$exam_no]);
					  $result['student_name'] = trim($row[$name]);
					  $result['year'] = $year;
					  $result['school_id'] = $school_id;
					  $result['classs'] = $class;
					  $result['term'] = $term;
					  $result['subject'] = trim($bb);

					  $coursC = $i+$colspan[$i]; $total = 0;
					  for($g = $i; $g < $coursC; $g++){
						  if(!empty($gradeRow[$g])){
							  $ca = empty($row[$g]) ?  0 : trim($row[$g]);
							  $result_Arr[$gradeRow[$g]] = $ca;
						  }
					  }
					  $result['result']=json_encode($result_Arr);
					  $results[] = $result;
				  }
			  }
			  
		  }
	  }
	}
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
	
	if($duplicate > 0){
		echo $duplicate.' Duplicate(s)';
	}else{
		echo'submitted';
		unlink($filename);
	}
	die();
	$msg=urlencode("$insert row(s) inserted. $duplicate duplicate row(s) found");
	$loc=explode("?",$referer);
	header("Location:{$loc[0]}?msg=$msg&pageType=$_pageType");
}else if(isset($_POST['loadresults'])){//View results
	$loadData = [];
	$query = "SELECT * from results WHERE class='$class' AND school_id='$school_id' AND year='$year' AND term='$term'";
	$get = $db->query($query) or die($db->error);
	$loadData['result'] = [];
	if($get->num_rows > 0){
		while($row = $get->fetch_assoc()){
			$row['result'] = json_decode($row['result'], true);
			$results[] = $row;
		}
		//Group courses by students;
		foreach($results as $result){
			$pupil = $result['exam_no'];
			$subject = $result['code'];
			$stu[$pupil][] = $result;
		}
		//sort courses
		foreach($stu as $num => $cou){
			foreach($cou as $sub){
				$courses[$sub['subject']] = ['grade'=>$sub['result'], 'id'=>$sub['id']];
			}
			$name = $cou[0]['student_name'];
			ksort($courses);
			$loadData['result'][] = array('exam_no'=>$num, 'name'=>$name, 'course'=>$courses);
		}
	}
	/*$sorted = array_sort($raw, 'exam_no', SORT_ASC);
	 = $sorted;*/
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
}else if(isset($_POST['updateResult'])){//Edit Result
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
}else if(isset($_POST['deleteResult'])){//Delete result
	$query = "DELETE FROM results WHERE class='$class' AND school_id='$school_id' AND year='$year' AND term='$term'";
	if($db->query($query) or die($db->error)){
		echo 1;
	}
}else if(isset($_POST['getAnnual'])){//Load/ View Annual computation
	$loadData = [];
	$query = "SELECT * from results WHERE (term='1' OR term='2' OR term='3') AND class='$class' AND school_id='$school_id' AND year='$year'";
//	die($query);
	$get = $db->query($query) or die($db->error);
	if($get->num_rows > 0){
		while($row = $get->fetch_assoc()){
			$grade = json_decode($row['result'], true);$gr = 0;$grr = 1; $grd_c = count($grade);
			foreach($grade as $g){if($grr < $grd_c){$gr = $gr+$g;}$grr++;}
			$pupil = trim($row['exam_no']);
			$name = trim($row['student_name']);
			$subject = $row['subject'];
			$term = $row['term'];
			$l[$pupil.'|'.$name]['term'.$term][] = array('grade'=>$gr,'subject'=>$subject, 'id'=>$row['id']);
		}
	}
	foreach($l as $user => $pup){
		foreach($pup as $t => $term){
			$args = array_column($term, 'subject');
			asort($args);
			foreach($args as $k1 => $subjs1){
				$key = array_search($subjs1, array_column($term, 'subject'));
				$j[$user][$t][] = $term[$key];
			}
		}
	}
	//print_r($j);
	/*while($row = $get->fetch_assoc()){
			$row['result'] = json_decode($row['result'], true);
			$results[] = $row;
		}
		//Group courses by students;
		foreach($results as $result){
			$pupil = $result['exam_no'];
			$subject = $result['code'];
			$stu[$pupil][] = $result;
		}
		//sort courses
		foreach($stu as $num => $cou){
			foreach($cou as $sub){
				$courses[$sub['subject']] = ['grade'=>$sub['result'], 'id'=>$sub['id']];
			}
			$name = $cou[0]['student_name'];
			ksort($courses);
			$loadData['result'][] = array('exam_no'=>$num, 'name'=>$name, 'course'=>$courses);
		}
	}*/
	echo json_encode($j);
}

function smart_result_scanner($excelData){
	global $db; $nameAndNumber = 0; $fixed = array('name of','exam no'); $courses = [];
	
	$name_rows = []; $name_row_count = 0; $name_row = [];
	$all = $db->query("SELECT name FROM subject ORDER  BY name DESC") or die($db->error);
	while($row = $all->fetch_assoc()){$courses[] = trim($row['name']);}
	//print_r($courses);die();
	$valcount = []; $course_rows = []; $course_row_count = 0; $course_row = [];
	//First Loop through data
	foreach($excelData as $c => $row){
		$count = count($row); $valcount[$c] = 0; $course_count = 0;//Intialize counters per row
		for($i=0; $i<$count; $i++){
			if(!empty(trim($row[$i]))){
				$valcount[$c]++;//number of non-empty values per row
				if(arraysearch_match($courses, $row[$i], 50, ''))$course_count++;
				if(arraysearch_match($fixed, $row[$i], 50, '')){
					$name_rows[$c][$i] = $row[$i];
				}
			}
		}
		if($course_count > 3){$course_row_count++; $course_rows[]=$c;}
	}
	$first_subject_row = $course_rows[0];//key for First course row; (The course row key)
	$first_name_row = array_keys($name_rows)[0];//First reoccurence key of "Exam No and Name of pupil"
	list($name, $num) = array_keys($name_rows[$first_name_row]);
	//Second Loop through data
	foreach($excelData as $c => $row){
		if($first_subject_row == $c){//Build rowspaning from course_rows
			$lastful = ''; $firstcol = 0;
			for($i=0; $i<$count; $i++){
				if(!empty(trim($row[$i]))){
					$lastful = $i;
					$course_row[$lastful] = 1;
					if($i == $firstcol){$course_row[$firstcol] = 1;}
				}else{
					if(!empty($lastful))$course_row[$lastful]++;
					$course_row[$i] = 0;
					if($i == $firstcol){$course_row[$firstcol] = 0;}
				}
			}	
		}
		if((!empty($row[$name]) && !empty($row[$num])) || ($valcount[$c] > (count($row)/5))){
			$key=array_search($c,$course_rows);
			if($key === false || $c == $first_subject_row){
				$z[] = array_map('strtoupper', $row);
			}
		}
	}
	// get accurate positionin of "student name" and "exam no";
	foreach($fixed as $c => $item){
		$found = arraysearch_match($excelData[$first_subject_row], $item, 50, 'key');
		if($found){
			$order[$c] = $found;
		}
	}
	$order['course'] = $first_subject_row;
	//echo json_encode($course_rows);//die();
	//colspan = number of span for each col from the first row
	$json = array('colspan'=>$course_row, 'table'=>$z, 'others'=>$order);
	return($json);
}


function findHeader($ar,$key){
	$selected="None";
	if(count($ar) ==0) return $selected;
	foreach($ar as $k=>$v)
	{
		$a[$k]=0;
		similar_text(strtolower($v),strtolower($key),$a[$k]);	
	}
	$f=max($a); $fk=array_search($f,$a);
	if($f > 80){
		$selected=$ar[$fk];
	}
	return $selected;
}


function arraysearch_match($arr, $str, $perc, $returnType){
	$perc = is_numeric($perc) ? $perc : 50; 
	foreach($arr as $k => $v){//foreach supplied array
		similar_text(strtolower($v), strtolower($str),$a[$k]);//store percentage of matched array text and supplied text in $a
	}
	$match=max($a); //get highest percentage value
	$matchkey = array_search ($match, $a); //get highest percentage value key
	
	if($match >= $perc){
		switch($returnType){
			case 'key':
			$result = $matchkey;
				break;
			case 'value':
				$result = $match;
				break;
			case 'boolean':
				$result = true;
				break;
			default:
				$result = 1;
				break;
		}
		return($result);
	}else{
		switch($returnType){
			case 'key':
			$result = null;
				break;
			case 'value':
				$result = null;
				break;
			case 'boolean':
				$result = false;
				break;
			default:
				$result = 0;
				break;
		}
		return($result);
	}
}
function array_sort($array, $on, $order=SORT_ASC){

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
$db->close();
?>