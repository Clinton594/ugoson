<?php
	require_once "../controllers/Controllers.php";
	require_once "../controllers/FormControl.php";
	$_color   = array("green","red","blue","cyan darken-3","teal darken-4","orange","light-green darken-3","lime darken-1","pink darken-4","purple darken-4", "purple accent-4");
	shuffle($_color);
	$cookie		    = explode(",", $_COOKIE["siteData"]);
	//Intstantiate the Generic class
	$generic 			= new Generic($cookie[1]);
	$get    			= (object)$_GET;
	$uri					= $generic->getURIdata();
	$db					  = $generic->connect();
  $uri->root    = absolute_filepath("{$uri->backend}");
	$is_dashboard = true;
	//Pass the generic object into the paramController
	$paramControl = new ParamControl($generic);
  $params    		= $paramControl->get_params();
	$param 				= $params->{$get->pageType};
	$formdata			= $paramControl->extract_form($param->form);
	$fmt 					= numfmt_create("en", NumberFormatter::DECIMAL );
	$formControl	= new FormControl($uri);
	$pageId				= strToFilename($get->pageType);
	$default_list_actions = [];
	$param->default_view = $param->form->form_view = "swipe";

	// Display Fields
	$display_fields = [];
	$extracted = $paramControl->extract_display_fields($param->display_fields);
	$extracted = object($extracted);

	// Get database values for the display fields, Theseare the colourful cards in the first row of a dashboard
	if(!empty($param->display_fields)){
		$queries = array();
		foreach ($param->display_fields as $key => $value) {
			$filter = empty($value->filter) ? "" : "WHERE $value->filter";
			$value->table = empty($value->table) ? $param->table : $value->table;
			$queries[] = "SELECT {$value->column} AS {$value->name} FROM {$value->table} {$filter}";
			$display_fields[$value->name] = object(["desc"=>$value->description, "color"=>$_color[$key], "class"=>$value->class]);
		}
		$queries = implode(";", $queries);
		$query = $db->multi_query($queries) or die($db->error);
		if($query){
			do {
			  if ($result = $db->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$key = array_keys($row);
						$key = reset($key);
						if(!empty($extracted->action->{$key}))$row[$key] = call_user_func_array($extracted->action->{$key}, [$row[$key]]);
						$display_fields[$key]->result = $row[$key];
					}
					$result->free();
			  }
	 		} while ($db->more_results() && $db->next_result());
		}
	}
	// Charts
	//Filter elements with graph type
	$elements =	$paramControl->extractFormElements($param->form);
	$elements = array_filter($elements, function ($value) {
		return in_array($value->type, ["pie-chart","line-graph","bar-graph"]);
	});
	$chart_data = [];
	$colors = ["red", "blue", "green", "violet", "orange", "teal", "orangered", "black", "#880e4f"];
	if(count($elements)){
		foreach ($elements as $key => $value) {
			shuffle($colors);
			$labels = $paramControl->load_data_from_param($value->source);
			$value->table = empty($value->table) ? $param->table : $value->table;
			$value->filter = empty($value->filter) ? "" : "WHERE {$value->filter}";
			// Extract grouping parameter
			$group = explode("group by", strtolower(str_replace("  ", " ", trim($value->filter))));
			if(empty($group[1]))die("{$value->name} must have a grouping data in the filter field to be able to run a graph");
			$groupedby = "k";
			$sql = "SELECT {$value->column} AS {$value->name}, $group[1] AS $groupedby FROM {$value->table} {$value->filter}";
			$query = $db->query($sql) or die($db->error. "($sql)");
			if($query->num_rows){
				$collection = [];
				while ($row = $query->fetch_object()) {
					$row->{$groupedby} = empty($labels[$row->{$groupedby}]) ? "Undefined" : $labels[$row->{$groupedby}];
					$collection[] = $row;
				}
				$graphdata = ["labels"=>array_column($collection, $groupedby), "datasets"=>[]];
				$graphdata["datasets"][] = [
					"label" => "",
					"backgroundColor" => array_range($colors, count($collection)),
					"data" => array_column($collection, $value->name),
					// "borderColor "=> array_range($colors, count($collection))
				];
				$chart_data[$value->name] = $graphdata;
			}
		}
	}
	if(!empty($get->ajax)) { ?>
		<!DOCTYPE html>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
				<title><?=$param->page_title ?></title>
				<?php $company = $generic->company(); ?>
				<!-- Include default CSS files-->
				<?php require_once("{$uri->root}includes/link_css.php") ?>
				<!-- Include custom CSS files-->
				<?php require_once("{$uri->root}backendProject/link_css.php") ?>
			</head>
			<body>
				<div style="height:2rem;display:block">
					<div class="progress hide">
						<div class="indeterminate"></div>
					</div>
				</div> <?php } ?>
	      <div class="house" id="_<?=$pageId?>">
					<div class="row" id="open_form"><!--Closed inside the list_container-->
						<div id="flash" class="hide"><?=json_encode($chart_data)?></div>
						<div class="row">
							<div class="col s12 mt-3 p-0">
								<!-- Build the cards -->
								<?php foreach($display_fields as $c => $item){?>
									<div class="<?=$item->class?>">
										<div class="card <?=$item->color?>">
											<div class="card-content white-text">
												<b><span><?=$item->desc?></span></b>
												<div class="dataSpan"><?=$item->result?></div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php if(!empty($formdata)){ ?>
							<!--Build other dashboard widgets-->
							<div class="row">
								<?php $formControl->build_form($formdata, "dashboard_elements") ?>
							</div>
						<?php require_once("{$uri->root}includes/list_container.php") ?>
					<?php }?>
		    </div>
	    <?php if(!empty($get->ajax)) { ?>
		</body>
  </html>
<?php } ?>
<?php $db->close();  ?>
<script language="javascript" type="text/javascript">
	var pageId='_<?=$pageId?>';
	// Normal Initialize form without loadOpen(display_fields)
	formInitialize(pageId, 1);

	let flash = $("#flash"+pageId).text();
	flash = isJson(flash);
	$("#flash"+pageId).remove();
	if(flash){
		for (var canvas in flash) {
			if (flash.hasOwnProperty(canvas)) {
				let thiscanvas = $("#"+canvas+pageId);
				let type = thiscanvas.data("type");
				let data = flash[canvas];
				// console.log(data);
				thiscanvas.plotGraph(type , data);
				thiscanvas.css({height:thiscanvas.data("height")})
			}
		}
	}
</script>
