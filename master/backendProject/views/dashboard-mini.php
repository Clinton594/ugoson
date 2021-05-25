<?php

require_once (__DIR__."../../../controllers/Controllers.php");
$generic 			= new Generic();
$paramControl = new ParamControl($generic);
$get    			= (object)$_GET;
$uri					= $generic->getURIdata();
$uri->root    = absolute_filepath("{$uri->site}{$uri->backend}");
$db = $generic->connect();
$fmn = new NumberFormatter("en", NumberFormatter::DECIMAL );
$appCurrency = $paramControl->load_sources("nairaEntity");
$bb = [
  'DBT'=>['red','Company Profit','blur_on'],
  'DEP'=>['green','Deposit','assistant'],
  'WTD'=>['blue','Withdrawal','assessment'],
  '2'=>['orange','Saved Customers','person_outline']
];
$background = ["#ffe3e3", "transparent",  "transparent", "transparent", "transparent", "transparent"];
// AND tbl.sub > 0
//Get charat data
// total deposit,withdrawal,
// debit by the company

if(isset($_POST['getChart'])){
	$y = date('Y');
	$qe = "
	SELECT MONTH(date) as month, tx_type as type, count(id) as num, sum(amount) as num FROM `transaction` WHERE YEAR(date)='$y' AND status='1' AND (tx_type IN('DEP','WTD') OR (tx_type='DBT' AND paid_into='COMPANY')) GROUP BY MONTH(date) ORDER BY MONTH(date) ASC
	";
	$pgquery = $db->query($qe) or die($db->error."($qe)");
	while($r = $pgquery->fetch_assoc()){
		$row[$r['type']][] = $r;
	}
	$bbx = ["DBT"=>true, "DEP"=>true,"WTD"=>true];
	foreach($bbx as $type => $arr){
			if(!isset($row[$type]))$row[$type] = [['month' => 1, 'num' => 0, "sum"=>0]];
	}
	foreach($row as $type => $info){
		$nums = array();$ddd = array();
		for($i=1; $i<=12;$i++){
			$nums[$i]=0;
			foreach($info as $m => $month ){
				if($month['month'] == $i){
					$nums[$i]=intval($month['num']);
				}
			}
		}
		foreach($nums as $c => $num){$ddd[] = $num;}
		$json[$type]=['data'=>$ddd, 'color'=>$bb[$type][0], 'label'=>$bb[$type][1]];
	}
	echo json_encode($json);
	die();

}else{
	  //Get number of posts on each of the key  parameters initialized above
		$query1 = "SELECT SUM(amount) as num, tx_type as type FROM `transaction` WHERE   `paid_into`='COMPANY' AND tx_type = 'DBT' AND `status`='1'
    UNION SELECT SUM(amount) as num, tx_type as type FROM `transaction` WHERE tx_type='DEP' AND `status`='1'
		UNION SELECT SUM(amount) as num, tx_type as type FROM `transaction` WHERE  tx_type='WTD' AND `status`='1'
		UNION SELECT count(id) as num, type FROM `users` WHERE  type='2'
	";

 $con1 = $db->query($query1) or die($db->error."($query1)");
 $rows = [];
 while($row = $con1->fetch_assoc()){
		 $rows[] = $row;
 }

 $types = array_column($rows, 'type');
 $build1 = [];
 foreach($bb as $type => $arr){
	 $key = array_search($type, $types);
	 $build1[$type]['icon'] = $arr[2];
	 $build1[$type]['label'] = $arr[1];
	 $build1[$type]['color'] = $arr[0];
	 $build1[$type]['type'] = $type;
	 if(is_numeric($key)){
		 $build1[$type]['num'] = $rows[$key]['num'];
	 }else{
		 $build1[$type]['num'] = 0;
	 }
	 if($type != "2") 	$build1[$type]['num'] = round($build1[$type]['num']);
 }




   //----------------------------------------------------------------------
	 $wid =
	 "SELECT `transaction`.tx_no,`transaction`.`status`, `transaction`.`description`,`transaction`.`amount`,  `transaction`.`date`, `transaction`.`user_id`,`users`.`username`
	 FROM `transaction` LEFT JOIN users on users.id=`transaction`.`user_id`
	 WHERE `transaction`.tx_type='WTD'  ORDER BY `transaction`.`date` DESC LIMIT 15" ;
	 $widd = $db->query($wid) or die($db->error."($wid)");
	 $wids = [];
	 if($widd->num_rows){
		 while($row = $widd->fetch_object()){
			 $now = date('Y-m-d H:i:s');
			 $date = new DateDifference($row->date , $now);
			 $date_= new DateTime($row->date);
			 $row->date_created = $date->smart();
			 $row->date_due = $date_->format("Y-m-d");
			 $row->date_d = $date_;
			 $row->status =  empty($row->status) ? "pending" : "completed";
			 $wids[] = $row;
		 }
	 }
 

	 $dep = 	 "SELECT `transaction`.tx_no,`transaction`.amount, `transaction`.`description`,`transaction`.`status`,  `transaction`.`date`, `transaction`.`user_id`,`users`.`username`
	 FROM `transaction` LEFT JOIN users on users.id=`transaction`.`user_id`
	 WHERE `transaction`.tx_type='DEP'  ORDER BY `transaction`.`date` DESC LIMIT 15" ;
	 
	//  ORDER BY charter_requests.reference DESC
	//  LIMIT 7
	 $depp = $db->query($dep) or die($db->error."($dep)");
	 $deps = [];
	 if($depp->num_rows){
		 while($row = $depp->fetch_object()){
			 $now = date('Y-m-d H:i:s');
			 $date = new DateDifference($row->date , $now);
			 $row->date_created = $date->smart();
			 $row->status =  empty($row->status) ? "pending" : "completed";
			 $deps[] = $row;
		 }
	 }
 

	 $pla = "SELECT username,email,date,status, count(id) as num FROM users GROUP BY `id` ORDER BY num DESC LIMIT 6" ;
	 $trip = $db->query($pla) or die($db->error."($pla)");
	 $trips = [];
	 if($trip->num_rows){
		 while($row = $trip->fetch_object()){
			 $now = date('Y-m-d H:i:s');
			 $row->status =  empty($row->status) ? "Not-Activated" : "Activated";
			 $trips[] = $row;
		 }
	 }



}

?>

<div id="dashboard-mini" style="background-color: #fdfdfd; min-height: 100vh">
	<div class="dashboard">
		<div class="row">
			<?php foreach($build1 as $c => $item){?>
			<div class="col l3 m6 s12">
				<div class="info-box hoverable pointer">
				<span class="info-box-icon  <?=$item['color']?>"><i class="material-icons medium white-text"><?=$item['icon']?></i></span>
				<div class="info-box-content">
				  <span class="info-box-text">Total <?=$item['label']?></span>
				  <span class="info-box-number"><?=$fmn->format(round($item['num']))?></span>
				</div>
			  </div>
			</div>
			<?php } ?>
		</div>
		<div class="graph row">
			<canvas id="myChart" style="width:100%; height: 400px !important"></canvas>
		</div>
		<div class="stats  row">
			<div class="col l12 s12 nopad">
				<div class="white stat-title" style="border-top: 4px solid red"><b>Most Recent Withdrawal Request</b></div>
				<div class="table-holder">
					<table class="stats stats1 white bordered">
						<thead>
							<th style="padding-right: 20px">S/N</th>
              <th>Username</th>
							<th>Description</th>
							<th>Amount</th>
							<th>Date</th>
							<th>Status</th>
						</thead>
						<tbody>
						<?php foreach($wids as $c =>$row){ $xx = $row->status == "pending" ? 0 : 1;?>
							<tr style="background-color:<?=$background[$xx]?>">
								<td><?=$c+1?></td>
								<td><?=$row->username?></td>
								<td><?=$row->description?></td>
								<td><?=$appCurrency.$fmn->format($row->amount)?></td>
								<td><?=date("d, M Y",strtotime($row->date_due))?></td>
								<td class="right-align"><?=$row->status?></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
      <div class="col l12 s12 posRel nopad">
			<?php if(!empty($deps)){?>
					<div class="col s12 substat nopad" style="margin-bottom:25px">
						<div class="white stat-title" style="border-top: 4px solid green">
							<b>Most Recent Deposit</b>
						</div>
						<div class="table-holder">
							<table class="stats white bordered">
                <thead>
    							<th style="padding-right: 20px">S/N</th>
    							<th>Username</th>
    							<th>Description</th>
    							<th>amount</th>
    							<th>Date</th>
    							<th>Status</th>
    						</thead>
								<tbody>
									<?php foreach($deps as $v => $row){ $xx = $row->status == "pending" ? 0 : 1; ?>
                    <tr style="background-color:<?=$background[$xx]?>">
      								<td><?=$v+1?></td>
                      <td><?=$row->username?></td>
      								<td><?=$row->description?></td>
      								<td><?=$appCurrency.$fmn->format($row->amount)?></td>
      								<td><?=date("d, M Y",strtotime($row->description))?></td>
      								<td class="right-align"><?=$row->status?></td>
      							</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
					</div>
  			<?php }?>
  			<?php if(!empty($trips)){?>
  					<div class="col s12 substat nopad">
  						<div class="white stat-title" style="border-top: 4px solid yellow">
  							<b>Most Rescent Customers</b>
  						</div>
  						<div class="table-holder">
  							<table class="stats white bordered">
                  <thead>
      							<th style="padding-right: 20px">S/N</th>
      							<th>Username</th>
      							<th>Email</th>
      							<th>Date</th>
      							<th>Status</th>
      						</thead>
  								<tbody>
  									<?php foreach($trips as $c => $row){?>
                      <tr>
        								<td><?=$c+1?></td>
        								<td><?=$row->username?></td>
        								<td><?=$row->email?></td>
        								<td><?=date("d, M Y",strtotime($row->date))?></td>
        								<td><?=$row->status?></td>
        							</tr>
  									<?php }?>
  								</tbody>
  							</table>
  						</div>
  					</div>
				<?php }?>
  		</div>
  	</div>
  </div>
<style>
.info-box {
    display: block;
    min-height: 90px;
    background: #fff;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 2px;
    margin-bottom: 15px;
}
.info-box-icon {
    border-top-left-radius: 2px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 2px;
    display: block;
    float: left;
    height: 90px;
    width: 90px;
    text-align: center;
    font-size: 45px;
    line-height: 90px;
    background: rgba(0,0,0,0.2);
        background-color: rgba(0, 0, 0, 0.2);
    background-color: rgba(0, 0, 0, 0.2);
    padding-top: 12px;
}.info-box-content {
    padding: 5px 10px;
    margin-left: 90px;
    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
    font-weight: 400;
}
.info-box-content {
    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
    font-weight: 400;
}
.info-box-number {
    display: block;
    font-weight: bold;
    font-size: 18px;
}
.dashboard .table-holder {
    border: solid 1px lightgray;
}
</style>
<script>

$(document).ready(function(){
	$.post(site.backend+"backendProject/views/dashboard-mini.php",{'getChart':true},function(response){
		var ctx = $('#myChart');
    // console.log(response);
		var data = isJson(response);
		//console.log(response);
		if(data !== false){
			var dataset = [];
			$.each(data, function(key, value){
				var obj = {
					label : value.label,
					backgroundColor: '',
					borderColor: value.color,
					data: value.data
				};
				dataset.push(obj);
			});
			var chart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
					datasets: dataset
				},
				options: {}
			});
		}else{console.log(response)}
	});
});
function isJson(str) {
	if(!str){
		return false;
	}else{
		try {
			var data = JSON.parse(str);
			var type = typeof(data);
			if(type.toLowerCase() !== 'object'){
				return false;
			}else{return data;}
		} catch (e) {
			//alert(e)
			return false;
		}
	}
}
</script>
