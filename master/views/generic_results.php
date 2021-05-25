<?php
	$page_title="";$_form=array();$_actions=array();$action_columns=array();$coldesc=array(); $category_source=array();
	require_once "../select_page.php";
	require_once "../get_param.php";
	require_once "../get_role_func.php";
	extract($_GET,EXTR_OVERWRITE);
	$dir="desc";$sort="";$sort_col=""; $source_count=0; $hierachy_count=0; $value_count=0; $group_count=0;$group_sum=0;$col="";$colD="";$order="";$required="";$Label="";

	$vt=extractForm($form);
	extract($vt);
	foreach($actions as $k=> $v)
	{
		$action_forms[$k]=extractForm($param[$v]['form']);
	}
	if(!empty($_category)){
		$xparam=explode("|",$_category);
		for($i=0;$i<count($xparam);$i++)
		{
			$rparam=$xparam[$i];
			$sparam=explode(",",$rparam);
			$category_column[]=$sparam[0];
			$category_name[]=$sparam[1];
			$category_source[]=$sparam[2];
			if(!empty($sparam[3])) $category_default[] = $sparam[3];
			else $category_default[]='';
		}
	}else $category_column=array();
?>
<?php if(empty($ajax)) { ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $page_title ?></title>
<?php require_once('../subs/links.php') ?>
</head>

<body>
<div style="height:2rem;display:block"><div class="progress hide">
      <div class="indeterminate"></div>
  </div></div><?php } ?>
	<style>/*body{overflow-y: hidden;}*/</style>
	<div class="house" id="_<?php echo strToFilename($page_title) ?>" style="display:none">
	<!--Page (2) Form section for swipe-->
	<div class="upload resultpage" id="new_form" style="display:none" >
		<section id="formData">
			<form class="result-data">
				<div class="top-control row">
				  <div class="input-field col s3">
					  <select name="class" id="l_result_class" required="required" data-school_id=""> </select>
					  <label class="" for="l_result_class">Select Class</label>
				  </div>

				<div class="input-field col s3 offset-s3">
				  <select name="term" id="l_result_term" required="required">
					<?php $data=loadData('term'); foreach($data as $_k3=> $v3) { if(!empty($v3)){?>
					<option value="<?php echo $_k3 ?>" > <?php echo $v3 ?> </option><?php  }} ?>
				  </select>
				  <label class="" for="l_result_term">Select Term</label>
				</div>
				<div class="input-field col s3">
					<select name="year" id="l_result_year" required="required">
						<?php $data=loadData('session'); foreach($data as $_k3=> $v3) { if(!empty($v3)){?>
						<option value="<?php echo $_k3 ?>" > <?php echo $v3 ?> </option><?php  }} ?>
				  	</select>
				   <label class="" for="l_result_year">Select Year</label>
				</div>
				</div>
				<div class="result-title">
					<div class="col s2"><div class="title right-align"></div></div>
					<div class="col s3"><div class="left-align school"></div></div>
					<div class="col s3"><div>Class :</div><div class="class"></div></div>
					<div class="col s2"><div>Term :</div><div class="term"></div></div>
					<div class="col s2"><div>Year :</div><div class="year"></div></div>
				</div>
			</form>
			<input name="<?php echo $primary_key ?>" type="text" id="<?php echo $primary_key ?>" value="" style="display:none" class="uniqueId" />
			<input name="pageType" type="hidden" class="keep" id="page_type" value="<?php echo $pageType ?>" />
			<input name="page_title" type="hidden" class="keep" id="page_title" value="<?php echo $page_title ?>" />
			<?php if(!empty($filter_dialog)) { ?>
				<input  type="hidden"  class="keep" id='_linkInput'  name="<?php echo $filter_dialog["column"] ?>" />
			<?php } ?>
			<div id="spreadsheet" class="confirm_result">
				<table class="bordered load_term"></table>
			</div>
		</section>
		  <div class="flt">
		  <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
			<a class="btn-floating btn-large red" id="resultLoad" title="Get Results">
			  <img src="icons/send.svg" class="large material-icons" />
			</a>
			<ul>
				 <li><a data-position="left" data-delay="50" data-tooltip="Close Window" class="tooltipped btn-floating blue" id="close"><img src="icons/reply.svg" class="material-icons" /></a></li>
				 <li><a data-position="left" data-delay="50" data-tooltip="Delete Result" class="tooltipped btn-floating green" id="resultDelete" ><img src="icons/delete.svg" class="material-icons" /></a></li>
				<li><a data-position="left" data-delay="50" data-tooltip="View Annual result" class="tooltipped btn-floating orange" id="getAnnual"><img src="icons/visibility.svg" class="material-icons" /></a></li>
			</ul>
		  </div>
		</div>
	</div>

<!--Annual Result page   ------- PAGE(2)-------->
<form id="annual_page" class="annual" style="display: none;">
	<div class="result-title">
		<h3 class="col s12"><b class="title right-align schoolName"></b></h3>
		<div class="col s4"><div>Class :</div><div class="class"></div></div>
		<div class="col s4"><div>Year :</div><div class="year"></div></div>
	</div>
	<table class="bordered annual_table" data-school="" data-class="" data-year="" style="margin-top: 30px"></table>
	<div class="flt">
	  <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
		<a class="btn-floating btn-large red" onclick="loadClose('annual_page_<?php echo $page_title ?>')">
		 <img src="icons/reply.svg" class="large material-icons" />
		</a>
	  </div>
	</div>
</form>
<!--Open Form List page   ------- PAGE(2)-------->
<div class="open_diplay_box resultpage" id="open_form"  >
   <div class="upload_bar" id="upload_bar">
	  <?php if(!empty($filter_dialog)) { ?>  <div class="top-control row"><a href="javascript:;" class="col s2" id="_backTop" top="<?php echo '_'.strToFilename($param[$filter_dialog["source"]]['page_title']); ?>"><img src="icons/reply_black.svg" class="material-icons" /><span style="font-size: 24px">Back</span></a><div class="right-align col s10"><h5 id="_linkTitle"></h5></div></div> <?php } ?>
      <div class="top-control row">

           <div class="input-field col s3"><input name="search" type="text"  id='_searchBox' size="50" value="" /><label class="active" for="_searchBox">Search <?php echo $page_title ?></label><img src="icons/search.svg" class="search prefix material-icons" /></div>
		  <div id="_filterList" ><?php if(!empty($filter_dialog)) { ?> <input class="filterValue keep" type="hidden"  id='_linkFilter'  name="<?php echo $filter_dialog["column"] ?>" /><?php } ?><?php for($k=0;$k<count($category_column);$k++) { if($category_source[$k]=="period") {?><div class='input-field col s3 right'> <input class="dateFilter" type="text"  id='<?php echo $category_column[$k] ?>' size="50" name="<?php echo $category_column[$k] ?>" /><label class="cat active" for='<?php echo $category_column[$k] ?>'>Date Range </label></div><?php } else $jn[]=$xparam[$k]; }?><?php if(!empty($jn)) {?><div class="input-field col s3 right"><input name="search" type="text"  id='_filter' size="50" class="filter" data-value="<?php echo implode("|",$jn)?>"  /><label  class="active"for="_filter">Filter By</label></div><?php } ?></div>
		  		<!--<div class="input-field col s3">
				  <select name="level" id="v_result_level" required="required">
					<?php $data=loadData('pr_level'); foreach($data as $_k3=> $v3) { if(!empty($v3)){?>
					<option value="<?php echo $_k3 ?>" > <?php echo $v3 ?> </option><?php  }} ?>
				  </select>
				  <label class="" for="v_result_level">Select Level</label>
			  </div>-->
		   <form class="result-data">
			  <div class="input-field col s3 offset-s3">
				  <select name="term" id="v_result_term" required="required">
					<?php $data=loadData('term'); foreach($data as $_k3=> $v3) { if(!empty($v3)){?>
					<option value="<?php echo $_k3 ?>" > <?php echo $v3 ?> </option><?php  }} ?>
				  </select>
				  <label class="" for="v_result_term">Select Term</label>
			  </div>
			  <div class="input-field col s3">
				<select name="year" id="v_result_year" required="required">
					<?php $data=loadData('session'); foreach($data as $_k3=> $v3) { if(!empty($v3)){?>
					<option value="<?php echo $_k3 ?>" > <?php echo $v3 ?> </option><?php  }} ?>
				  </select>
				   <label class="" for="v_result_year">Select Year</label>
			  </div>
		  </form>
       </div>
  	</div>
	<div id="dialog_display" class="open_select-div collection with-header" data-open="results"></div>
	<!--Page(2) List page Action buttons-->
	<?php if(!empty($param)) { $c = 1;?>
		<div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
			<?php foreach($actions as $k => $v) { if($c ==1){ $act=$param[$v];?>
			<a href="#actionpanel<?php echo $k.'_'.$page_title ?>" data-position="left" data-delay="50" data-tooltip="<?php echo $act['page_title'] ?>" class="tooltipped btn-floating btn-large red ?> modal-trigger">
				<img src="icons/<?php echo $act['icon'] ?>.svg" class=" large material-icons" />
			</a>
			<?php $c++;}} ?>
			<ul>
				<li>
					<a data-position="left" data-delay="50" data-tooltip="Back to <?php echo $filter_dialog["source"] ?>" class="tooltipped btn-floating blue" id="_back_Top" top="<?php echo '_'.strToFilename($param[$filter_dialog["source"]]['page_title']) ?>">
						<img src="icons/reply.svg" class="material-icons" />
					</a>
				</li>
				<?php $c = 1; foreach($actions as $k => $v) { if($c==2){
					if(!empty($param[$v])){ $act=$param[$v];?>
						<li>
							<a href="#actionpanel<?php echo $k.'_'.$page_title ?>" data-position="left" data-delay="50" data-tooltip="<?php echo $act['page_title'] ?>" class="tooltipped btn-floating <?php echo $_color[$k]; ?> modal-trigger">
								<img src="icons/<?php echo $act['icon'] ?>.svg" class="material-icons" />
							</a>
						</li>
					<?php }
				 } $c++;}?>

			</ul>
		</div>
	<?php }?>
 </div>


 <!--Loop Float-Action Modals for page(2)-->
<?php foreach($actions as $k=> $v) { if(!empty($param[$v])){ $act=$param[$v];?>
	<form action="<?php echo $act['process_url'] ?>" method="post" name="form2" id="fm_action_<?php echo $k ?>"  data-submit="<?php if(isset($act['submit']))echo 1; else echo '0' ?>" enctype="multipart/form-data" onSubmit="return(false)">
		<div class="modal modal-fixed-footer" id="actionpanel<?php echo $k ?>">
			<div class="modal-content">
				<?php $vt=extractForm($act['form']); extract($vt); require('generic_form.php');?>
			</div>
			<div class="modal-footer">
				<button href="#fm_action_<?php echo $k ?>" class="waves-effect waves-green btn action-btn">Ok</button>
				<a class="modal-action modal-close waves-effect waves-green btn-flat">CANCEL</a>
			   <input name="filter_checkbox" class="filter_checkbox" type="hidden" />
			</div>
		</div>
	</form>
<?php }}?>




<script language="javascript" type="text/javascript">
	var pageId='_<?php echo strToFilename($page_title) ?>';
	var oldPageId=pageId;
	$('#'+pageId).find('[id]').each(function()
	{
		var tmp=$(this).attr('id')+ pageId;
		$(this).attr({'id':tmp});
		$(this).attr({'data-pageid':pageId});
	})
		$('#'+pageId).find('[for]').each(function()
	{
		var tmp=$(this).attr('for')+ pageId;
		$(this).attr({'for':tmp});
	})
	formInitialize(pageId,2);
	$('#'+pageId+' .tooltipped').each(function(){
		$(this).attr({title:$(this).data('tooltip')})
	})

</script>
</div>

	<!---Generic level first page  ----------->

<?php if(!empty($filter_dialog)) {
	$parentType=$pageType; $parent_title='_'.strToFilename($page_title); $pageType=$filter_dialog["source"];  if(isset($pageType)){
		$pageType=trim($pageType);
		if(isset($param[$pageType])) extract($param[$pageType],EXTR_OVERWRITE);
	}?>
	<div class="house" id="_<?php echo strToFilename($parent_title."_".$page_title) ?>" <?php if(!empty($callback)){?>callback="<?=$callback?>"<?php }?>> <div  id="filter_dialog"> <div class="upload_bar" id="upload_bar">
      <div class="top-control row">

            <!--<div class="col s12">Select <?php echo $page_title ?></div>-->
           <div class="input-field col s3">
			   <input name="toplevel" type="hidden" id="_toplevel" value="<?php echo $parent_title ?>" />
			   <input name="pageType" type="hidden" id="page_type" value="<?php echo $pageType ?>" />
			   <input name="<?=$pageType?>" type="hidden" id="hierachy" value="<?=$filter_dialog["column"] ?>" />
            	<input name="page_title" type="hidden" id="page_title" value="<?php echo $page_title ?>" />
			   <input name="search" type="text"  id='_searchBox' size="50" value="" />
			   <label for="_searchBox">Search <?php echo $page_title ?></label>
			   <img src="icons/search.svg" class="search prefix material-icons" />
		  </div>
		  <div class="input-field col s2">
					<select name="range" id='_rangeBox'>
						<option value="">Number of items</option><option value="25">25 Items</option>
						<option value="50">50 Items</option><option value="100">100 Items</option>
						<option value="150">150 Items</option><option value="200">200 Items</option>
					</select>
				</div>
		  <!--<div id="_filterList" >
			<?php if(isset($filter_dialog) && !empty($filter_dialog)) { ?> <input class="filterValue" type="hidden"  id='_linkFilter'  name="<?php echo $filter_dialog["column"] ?>" /><?php } ?>
			<?php foreach($category as $k => $f) { if(isset($f['type']) && $f['type'] == 'period') {?><div class='input-field col s3 right'> <input class="dateFilter" type="text"  id='<?php echo $category_column[$k] ?>' size="50" name="<?php echo $category_column[$k] ?>" /><label class="cat" for='<?php echo $category_column[$k] ?>'>Date Range </label></div><?php } else $jn[]=$f; }?><?php if(!empty($jn)) {?><div class="input-field col s3 right"><input name="search" type="text"  id='_filter' size="50" class="filter" data-value="<?php echo jsonRe_encode($jn)?>"  /><label for="_filter">Filter By</label></div><?php } ?>
		</div>-->

       </div>

  </div>
	<div class="list_cont">
		<div id="dialog_display" class="open_select-div collection with-header" ></div>
		<div class="reloadPage" id="reloadPage"><img class="material-icons" src="icons/refresh.svg"></div>
	</div>
	<div class="pagination"><div id="pagination"></div><input id="active_page" type="hidden" name="page" /></div>
  </div></div>  </div>
  <script language="javascript" type="text/javascript">
	var firstpage='_<?php echo strToFilename($parent_title."_".$page_title) ?>';
	$('#'+firstpage).find('[id]').each(function()
	{
		var tmp=$(this).attr('id')+ firstpage;
		$(this).attr({'id':tmp});
		$(this).attr({'data-pageid':firstpage});
	})
		$('#'+firstpage).find('[for]').each(function()
	{
		var tmp=$(this).attr('for')+ firstpage;
		$(this).attr({'for':tmp});
	})
	formInitialize(firstpage);
	$('#_backTop'+pageId+', #_back_Top'+pageId).click(function(){

		var pg = $(this).data('pageid');
		var top = $(this).attr('top');
		$('#'+pg).swapDiv($('#_'+pg+top));
	})
</script>

  <?php } ?>
	<script type="text/javascript" language="javascript" src="scripts/student_results.js"></script>
<?php if(empty($ajax)) { ?>

</body>
</html>
<?php } ?>
