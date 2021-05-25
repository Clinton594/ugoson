<?php
	require_once "../controllers/Controllers.php";
	require_once "../controllers/FormControl.php";
	//Intstantiate the Generic class
	$cookie		    = explode(",", $_COOKIE["siteData"]);
	$generic 			= new Generic($cookie[1]);
	$get    			= (object)$_GET;
	$uri					= $generic->getURIdata();
	$uri->root    = absolute_filepath("{$uri->backend}");
	//Pass the generic object into the paramController
	$paramControl = new ParamControl($generic);
	$params    		= $paramControl->get_params();
	$param 				= $params->{$get->pageType};
	$formdata			= $paramControl->extract_form($param->form);
	$formControl	= new FormControl($uri);
	$pageId				= strToFilename($get->pageType);
	$param->form->form_view = empty($param->form->form_view) ? "swipe" : $param->form->form_view;
	$param->default_view = empty($param->default_view) ? "list" : $param->default_view;
	global $_color;
	$form_views = ["swipe", "modal", "extension"];
	$default_list_actions = [
		"add"=>["Add new","new","green"],
		"refresh"=>["Refresh","reloadPage","orange"],
		"delete"=>["Delete from","_multiDelete","red"],
	];
	$default_form_actions = [
		"back"=>["Go Back to","close"],
		"clear"=>["Refresh","formReset"],
		"submit"=>["Submit Form",""],
		"print"=>["Print","printForm"],
	];
	$_color   = array("green","red","blue","cyan darken-3","teal darken-4","orange","light-green darken-3","lime darken-1","pink darken-4","purple darken-4", "purple accent-4");
  shuffle($_color);
?>
<?php if(!empty($get->ajax)) { ?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title><?=$param->page_title ?></title>
			<?php $company = $generic->company(); ?>
			<?php require_once("{$uri->root}includes/link_css.php") ?>
			<?php require_once("{$uri->root}backendProject/link_css.php") ?>
		</head>
		<body>
			<div style="height:2rem;display:block">
				<div class="progress hide">
					<div class="indeterminate"></div>
				</div>
			</div>
		<?php } ?>
		<div class="house" id="_<?=$pageId?>">
			<?php require_once("{$uri->root}includes/form_container.php") ?>
			<?php require_once("{$uri->root}includes/list_container.php") ?>
		</div>

		<!---Generic level first page  ----------->

	<?php if(!empty($param->display_level)) {
		$parent_type  = $param->display_level->source;
		$parent_colum = $param->display_level->column;
		$level 				= $params->{$parent_type};
		$parent_title = strToFilename("_$parent_type");
	?>
		<div class="house" id="<?=$parent_title?>">
			<div  id="open_form">
				<div class="upload_bar" id="upload_bar">
	      	<div class="top-control row">
						<div class="input-field col l3 s6">
							<input name="toplevel" type="hidden" class="keep" id="_toplevel" value="_<?=$pageId ?>" />
							<input name="pageType" type="hidden" class="keep" id="page_type" value="<?=$parent_type?>" />
							<input name="<?=$get->pageType?>" type="hidden" class="keep" id="hierachy" value="<?=$parent_colum?>" />
							<input name="page_title" type="hidden" class="keep" id="page_title" value="<?=$level->page_title?>" />
							<input name="search" type="text"  id='_searchBox' size="50" value="" />
							<label for="_searchBox">Search <?=$level->page_title?></label>
							<img src="icons/search.svg" class="search prefix material-icons" />
						</div>
						<div class="input-field col l2 s6">
							<select name="range" id='_rangeBox'>
								<option value="">Number of items</option><option value="25">25 Items</option>
								<option value="50">50 Items</option><option value="100">100 Items</option>
								<option value="150">150 Items</option><option value="200">200 Items</option>
							</select>
						</div>
						<div id="_filterList" class="_filterList">
							<input class="filterValue" id='_linkFilter' type="checkbox" checked hidden name="<?=$param->display_level->column ?>" />
							<?php
							if(!empty($level->category)):
								foreach($level->category as $category_key => $category_data) {
									if(isset($category_data->type) && $category_data->type == "period") {?>
										<div class='input-field col m6 l3 right'>
											<input class="dateFilter" type="text" id='<?=$category_data->column?>' size="50" name="<?=$category_data->column?>" />
											<input class="filterValue" type="hidden"  id='_linkFilter'  name="<?=$category_data->column?>" />
											<label class="cat" for='period'>Date Range </label>
										</div>
									<?php } else {
										$kn[]=$category_data;
									}
								}
							endif;
							?>
							<?php if(!empty($kn)) {?>
								<div class="input-field col s6 l3 right">
									<input type="text" id='_filter' size="50" class="filter" data-value="<?=$paramControl->jsonRe_encode($kn)?>"  />
									<label for="_filter">Filter By</label>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="list_cont">
					<div id="dialog_display" class="open_select-div collection with-header" ></div>
				</div>
				<div class="pagination">
					<div class="cont" id="cont"></div>
					<input id="active_page" type="hidden" name="page" />
				</div>
				<!-- Build Action Buttons for first level if any -->
				<?php if(!isset($level->listFAB) || $level->listFAB !== false){
					$fixed_keys = array_keys($default_list_actions);
					$build = empty($level->listFAB) ? $fixed_keys : $level->listFAB;
					$first_build = reset($build); ?>
					<div id="fab" class="fixed-action-btn" <?php if(isMobile()){?>data-mobile="true" <?php }?> style="right:5px">
						<?php if(in_array($first_build, $fixed_keys)){?>
							<a class="btn-floating btn-large tooltipped <?=$default_list_actions[$first_build][2]?>" id="<?=$default_list_actions[$first_build][1]?>" style="padding: 8px;" data-position="left" data-delay="50" data-tooltip="<?=$default_list_actions[$first_build][0]?> <?=$level->page_title; ?>">
								<img class="material-icons" src="icons/<?=$first_build?>.svg">
							</a>
						<?php }else{ $thisparam = $params->{$first_build}?>
							<a href="#<?=strToFilename("{$level->page_title} {$thisparam->page_title} {$parent_title}")?>" data-position="left" data-delay="50" data-tooltip="<?=$thisparam->page_title ?>" class="btn-large tooltipped btn-floating <?=$_color[0]; ?> modal-trigger" style="padding: 8px;">
								<img src="icons/<?=$thisparam->icon ?>.svg" class="material-icons" />
							</a>
						<?php }?>
						<!-- Add Extra Actions to List Fixed Action bar -->
						<ul>
							<?php foreach ($build as $key => $value) {
								if($key == 0)continue;
								if(in_array($value, $fixed_keys)){?>
									<li>
										<a class="btn-floating tooltipped <?=$default_list_actions[$value][2]?>" id="<?=$default_list_actions[$value][1]?>" data-position="left" data-delay="50" data-tooltip="<?=$default_list_actions[$value][0]?>  <?=$level->page_title; ?>">
											<img src="icons/<?=$value?>.svg" class="material-icons" />
										</a>
									</li>
								<?php }else{$thisparam = $params->{$value};?>
									<li>
										<a href="#<?=strToFilename("{$level->page_title} {$thisparam->page_title} {$parent_title}")?>" data-position="left" data-delay="50" data-tooltip="<?=$thisparam->page_title ?>" class="tooltipped btn-floating <?=$_color[$key]; ?> modal-trigger">
											<img src="icons/<?=$thisparam->icon ?>.svg" class="material-icons" />
									  </a>
									</li>
								<?php }?>
							<?php }?>
						</ul>
					</div>
					<!-- Build Modal for external actions -->
					<?php
						foreach($build as $k=> $v):
							if(!empty($params->{$v})):
								$submitype = 0;
								$thisparam = $params->{$v};
								$thisparam->submit_type = isset($thisparam->submit_type) ? $thisparam->submit_type : 0;?>
								<div class="modal modal-fixed-footer" id="<?=strToFilename("{$level->page_title} {$thisparam->page_title}")?>">
									<form
									<?php if(!empty($thisparam->form->onload)){?>onload="<?=$thisparam->form->onload?>"<?php }?>
									<?php if(!empty($thisparam->form->callback)){?>callback="<?=$thisparam->form->callback?>"<?php }?>
									<?php if(!empty($thisparam->process_url)){?>data-action="<?=$thisparam->process_url?>"<?php }?>
									method="post"  id="fm_action_<?=strToFilename("{$level->page_title} {$thisparam->page_title}")?>"  data-submit="<?=$thisparam->submit_type?>" enctype="multipart/form-data" action="javascript:;">
									<div class="form-header">
										<div class="form-title" id="form_title"></div>
									</div>
									<div class="modal-content">
											<?php
												$thisForm = $paramControl->extract_form($thisparam->form);
												$formControl->build_form($thisForm)
											?>
									</div>
									<?php if((isset($level->form->form_submit) && $level->form->form_submit !== false) || !isset($level->form->form_submit)){?>
										<div class="modal-footer">
											<input type="hidden" name="pageType" value="<?=$parent_type?>"  class="keep">
											<a class=" modal-action modal-close waves-effect waves-green btn-flat">CLOSE</a>
											<button class="waves-effect waves-green btn formSave">SUBMIT</button>
										</div>
									<?php } ?>
								</form>
							 </div>
						<?php endif;
						endforeach;
					?>
				<?php }?>
			</div>

			<!-- Build form for first level if any -->
			<?php if(!empty($param->display_level->loadform)) {	?>
				<?php
				$level->form->form_view = empty($level->form->form_view) ? "swipe" : $level->form->form_view;
				if($level->form->form_view == "modal") {	?>
					<div class="modal modal-fixed-footer" id="new_form" >
						<form id="formData"  href="javascript:;" onSubmit="return(false)">
							<div class="form-header">
								<div class="form-title" id="form_title"></div>
							</div>
							<div class="modal-content black-text">
								<?php
									$level_formdata = $paramControl->extract_form($level->form);
									$formControl->build_form($level_formdata)
								?>
							</div>
							<div class="modal-footer">
								<a class=" modal-action modal-close waves-effect waves-green btn-flat">CLOSE</a>
								<button class="waves-effect waves-green btn formSave">SUBMIT</button>
							</div>
							<input name="<?=$level->primary_key ?>" type="hidden" id="<?=$level->primary_key ?>" class="uniqueId"/>
							<input name="pageType" class="keep"  type="hidden" id="page_type" value="<?=$parent_type ?>" />
							<input name="page_title" class="keep" type="hidden" id="page_title" value="<?=$level->page_title ?>" />
							<input name="modal" class="keep" type="hidden" id="modal" value="1" />
						</form>
					</div>
				<?php }else{?>
					<!--For Swipe forms-->
				  <div class="row" id="new_form" style="display:none">
						<form id="formData"  href="javascript:;" onSubmit="return(false)">
							<div class="form-header">
								<div class="form-nav" id="form-nav-prev" data-move="prev">
									<img src="icons/chevron_left_black.svg" class="material-icons small"/>
								</div>
								<div class="form-title" id="form_title"></div>
								<div class="form-nav" id="form-nav-next" data-move="next">
									<img src="icons/chevron_right_black.svg" class="material-icons small"/>
								</div>
							</div>
							<?php
								$level_formdata = $paramControl->extract_form($level->form);
								$formControl->build_form($level_formdata)
							?>
							<input name="<?=$level->primary_key?>" type="hidden" id="<?=$level->primary_key?>" class="uniqueId"/>
							<input name="pageType" class="keep" type="hidden" id="page_type" value="<?=$parent_type ?>" />
							<input name="page_title" class="keep" type="hidden" id="page_title" value="<?=$level->page_title ?>" />
							<div class="flt">
								<div data-role="form" class="form-actions" <?php if(isMobile()){?>data-mobile="true" <?php }?>>
									<ul>
										<?php if(!isset($level->form->form_actions) || $level->form->form_actions !== false){
											$fixed_form_keys = array_keys($default_form_actions);
											// Activate the default print in a form only when it is added in form_actions of the param
											$form_keys_build = empty($level->form->form_actions) ? array_slice($fixed_form_keys, 0, -1) : $level->form->form_actions;
										?>
										<?php foreach ($form_keys_build as $key => $value) {
											// for fixed form actions
											if(in_array($value, $fixed_form_keys)){
												if($value == "submit"){ if(!isset($level->form->form_submit) || !empty($level->form->form_submit)){?>
													<li>
														<button class="tooltipped formSave" type="submit" data-position="top" data-delay="50" data-tooltip="<?=$default_form_actions[$value][0]?> <?=$level->page_title; ?>">
															<img src="icons/<?=$value?>.svg" class="material-icons small" />
														</button>
													</li>
												<?php }}else{ ?>
													<li>
														<a data-axx="<?=$value?>" style="display:block" class="tooltipped" id="<?=$default_form_actions[$value][1]?>" data-position="top" data-delay="50" data-tooltip="<?=$default_form_actions[$value][0]?> <?=$level->page_title; ?>">
															<img src="icons/<?=$value?>.svg" class="material-icons tiny" />
														</a>
													</li>
												<?php }?>
											<?php }else{
												$thisparam = $params->{$value};?>
												<li>
													<!-- Has to have pageid added to the href bcos the modal id would auto get a page appended to it -->
													<a onclick="<?=$thisparam->onclick?>"  style="display:block" data-position="top" data-delay="50" data-tooltip="<?=$thisparam->page_title ?>" class="tooltipped">
														<img src="icons/<?=$thisparam->icon ?>.svg" class="material-icons tiny" />
													</a>
												</li>
											<?php }?>
										<?php }
										}?>
									</ul>
								</div>
							</div>
						</form>
					</div>
				<?php }?>
			<?php }?>

			<!-- Build only modal extensions for first level if any -->
			<?php if(!empty($level->extension)){
				foreach($level->extension as $k=> $ext_param_name) {
					if(!empty($params->{$ext_param_name})){
						$extension 	= $params->{$ext_param_name};
						$tep_id 		= strToFilename("{$k} {$ext_param_name}");
						$ext_form 	= $paramControl->extract_form($extension->form);
						$extension->form->form_view = empty($extension->form->form_view) ? "swipe" : $extension->form->form_view;
						?>
						<?php if($ext_form){ ?> <!-- Build Modals as long as it has form elements -->
							<div class="modal modal-fixed-footer" id="newextform<?=$tep_id ?>">
								<form <?php if(!empty($extension->form->onload)){?>onload="<?=$extension->form->onload?>"<?php }?>
									<?php if(!empty($extension->form->callback)){?>callback="<?=$extension->form->callback?>"<?php }?>
									<?php if(!empty($extension->process_url)){?>data-action="<?=$extension->process_url?>"<?php }?>
									action="javascript:;" id="extForm<?=$tep_id?>" onSubmit="return(false)">
									<div class="form-header">
										<div class="form-title" id="form_title"></div>
									</div>
									<div class="modal-content">
										<?php $formControl->build_form($ext_form)?>
									</div>
									<?php if(!empty($extension->form->form_submit)){?>
										<div class="modal-footer">
											<a class="modal-action modal-close waves-effect waves-green btn-flat">CANCEL</a>
											<button href="#extForm<?=$tep_id?>" class="waves-effect waves-green btn action-btn formSave">SUBMIT</button>
											<input name="filter_checkbox" class="filter_checkbox" type="hidden" />
										</div>
									<?php } ?>
									</form>
							</div>
						<?php }?>
					<?php }
				}
			}?>
		</div>
	</div>
	  <script language="javascript" type="text/javascript">
			var firstpage='<?=$parent_title?>';
			formInitialize(firstpage);
			$("#"+pageId).swapDiv($("#"+firstpage));

			$('#_backTop'+pageId).click(function(){
				let parent = $(this).attr("top");
				let current = $(this).data("pageid");
				$("#"+current).swapDiv($("#"+parent));
				reloadPageList(parent);
			})
		</script>
	<?php } ?>
<?php
	if(!empty($get->ajax)) { ?>
	</body>
	</html>
<?php } ?>
