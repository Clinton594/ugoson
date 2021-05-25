<div class="open_diplay_box" id="open_form"  >
	<div class="upload_bar" id="upload_bar">
		<?php if(!empty($param->display_level)) { ?>
			<div class="top-control row">
				<a href="javascript:;" class="col s2" id="_backTop" top="_<?=$params->{$param->display_level->source}->page_title ?>">
					<img src="icons/reply_black.svg" class="material-icons" />
					<span style="font-size: 24px">Back</span>
				</a>
				<div class="right-align col s10">
					<h5 id="_linkTitle"></h5>
				</div>
		 </div>
		<?php } ?>
		<div class="top-control">
			<div class="input-field col s6 l1">
				<input name="search" type="text" disabled id='listCount' size="50" value="" />
			</div>
			<div class="input-field col s6 l3">
				<input name="search" type="text"  id='_searchBox' size="50" value="" />
				<label for="_searchBox">Search <?=$param->page_title ?></label>
				<img src="icons/search.svg" class="search prefix material-icons" />
			</div>
			<div class="input-field col s6 l2">
				<select name="range" id='_rangeBox'>
					<option value="">Number of items</option><option value="25">25 Items</option>
					<option value="50">50 Items</option><option value="100">100 Items</option>
					<option value="150">150 Items</option><option value="200">200 Items</option>
				</select>
			</div>
			<div id="_filterList" class="_filterList">
				<?php if(!empty($param->display_level)) { ?>
					<input class="filterValue" id='_linkFilter' type="checkbox" checked hidden name="<?=$param->display_level->column ?>" />
				<?php } ?>
				<?php
				if(!empty($param->category)):
					foreach($param->category as $category_key => $category_data) {
						if(isset($category_data->type) && $category_data->type == "period") {?>
							<div class='input-field col m6 l3 right'>
								<input class="dateFilter" type="text" id='<?=$category_data->column?>' size="50" name="<?=$category_data->column?>" />
								<?php if(!empty($param->display_level)) {?>
									<input class="filterValue" type="hidden"  id='_linkFilter'  name="<?=$category_data->column?>" />
								<?php } ?>
								<label class="cat" for='period'>Date Range </label>
							</div>
						<?php } else {
							$jn[]=$category_data;
						}
					}
				endif;
				?>
				<?php if(!empty($jn)) {?>
					<div class="input-field col s6 l3 right">
						<input type="text" id='_filter' size="50" class="filter" data-value="<?=$paramControl->jsonRe_encode($jn)?>"  />
						<label for="_filter">Filter By</label>
					</div>
				<?php } ?>
			</div>

		</div>
	</div>
	<div class="list_cont">
		<div id="dialog_display" class="open_select-div collection with-header" <?php if(isset($param->ModalForm) && $param->ModalForm == true) {?> data-open="modal" <?php } ?>></div>
	</div>
	<div class="pagination">
		<div class="cont" id="cont"></div>
		<input id="active_page" type="hidden" name="page" />
	</div>
	<?php if(!isset($param->listFAB) || $param->listFAB !== false){
		$actionIsArray = false;
		if(!empty($param->listFAB) && gettype($param->listFAB) == 'array')$actionIsArray = true;?>
		<div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
			<a
				class="btn-floating btn-large red tooltipped"
				data-position="left"
				data-delay="50"
				<?php if(($actionIsArray && in_array("add", $param->listFAB)) || empty($param->listFAB)){ ?>
					data-tooltip="Add New <?=$param->page_title; ?>"
					<?php if(isset($param->ModalForm) && $param->ModalForm == true) { ?>
						id="newFloat"
					<?php }else{ ?>
						id="new"
					<?php }
				}else{?>
					data-tooltip="Disabled"
				<?php }?>
			> 	<!-- End of Anchor opening tag -->
				<?php if(($actionIsArray && in_array("add", $param->listFAB)) || empty($param->listFAB)){ ?>
					<img src="icons/add.svg" class="large material-icons" />
				<?php }else{ ?>
					<img src="icons/ic_grid_off.svg" class="large material-icons"  />
				<?php }?>
			</a>
			<ul>
				<!-- Add Extra Actions to List Fixed Action bar -->
				<?php
					if(!empty($param->actions)):
						foreach($param->actions as $k => $v) {
							if(!empty($params->{$v})){
								$thisparam = $params->{$v};?>
								<li>
									<a href="#actionpanel<?php echo $k.'_'.$thisparam->page_title ?>" data-position="left" data-delay="50" data-tooltip="<?=$thisparam->page_title ?>" class="tooltipped btn-floating <?=$_color[$k]; ?> modal-trigger">
										<img src="icons/<?=$thisparam->icon ?>.svg" class="material-icons" />
									</a>
								</li>
							<?php }
						}
					endif;
				?>
				<li>
					<a class="btn-floating pink tooltipped" id="reloadPage" data-position="left" data-delay="50" data-tooltip="Refresh <?=$param->page_title; ?>">
						<img src="icons/refresh_white.svg" class="material-icons" />
					</a>
				</li>
				<?php if(($actionIsArray && in_array("void", $param->listFAB))){?>
					<li>
						<a class="btn-floating grey tooltipped" id="void" data-position="left" data-delay="50" data-tooltip="Void <?=$param->page_title; ?>">
							<img src="icons/ac_unit.svg" class="material-icons" />
						</a>
					</li>
				<?php }?>
				<!-- Shows delete button by default when there is no listFAB action or when delete is in actions -->
				<?php if(($actionIsArray && in_array("delete", $param->listFAB)) || empty($param->listFAB)){?>
					<li>
						<a class="btn-floating blue blue-darken-3 tooltipped" id="_multiDelete" data-position="left" data-delay="50" data-tooltip="Delete from <?=$param->page_title; ?>">
							<img src="icons/delete.svg" class="material-icons" />
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php }?>
	</div>
	<!-- Build Modals for external Actions -->
	<?php
		if(!empty($param->actions)):
			foreach($param->actions as $k=> $v):
				if(!empty($params->{$v})):
					$submitype = 0;
					$thisparam = $params->{$v};
					$thisparam->submit_type = isset($thisparam->submit_type) ? $thisparam->submit_type : 0;
					?>
					<form action="<?=$thisparam->process_url?>" method="post" name="form2" id="fm_action_<?=$k ?>"  data-submit="<?=$thisparam->submit_type?>" enctype="multipart/form-data" onSubmit="return(false)">
						<div class="modal modal-fixed-footer" id="actionpanel<?=$k ?>">
							<div class="modal-content">
								<?php
									//Load form elements into to the hidden modal
									$thisForm = $paramControl->extract_form($thisparam->form);
									$formControl->build_form($thisForm)
								?>
							</div>
							<div class="modal-footer">
								<button href="#fm_action_<?=$k ?>" class="waves-effect waves-green btn action-btn">Ok</button>
								<a class="modal-action modal-close waves-effect waves-green btn-flat">CANCEL</a>
							 <input name="filter_checkbox" class="filter_checkbox" type="hidden" />
						 </div>
					 </div>
				</form>
			<?php endif;
		endforeach;
		endif;
		if(!empty($params->extension) && count($extension) > 0){
			$ext_c = 1;
			foreach($extension as $k=> $v) { if(!empty($param[$v])){ $ext=$param[$v]; $tep_id = $k.'_'.$v; ?>
			<?php $vt=extractForm($ext['form']);  ?>
			<?php if(isset($ext['ModalForm']) && $ext['ModalForm'] == true) {?>
				<div class="modal modal-fixed-footer" id="newExtForm<?=$tep_id ?>">
				<form action="<?php echo $ext['process_url'] ?>" id="extForm<?=$tep_id?>" onSubmit="return(false)">
					<div class="modal-content">
						<?php extract($vt); generic_form($vt);?>
					</div>
					<div class="modal-footer">
						<button href="#extForm<?=$tep_id?>" class="waves-effect waves-green btn action-btn">Ok</button>
						<a class="modal-action modal-close waves-effect waves-green btn-flat">CANCEL</a>
						<input name="filter_checkbox" class="filter_checkbox" type="hidden" />
					</div>
					</form>
			</div>
			<?php } else{?>
			 <div id="newExtForm<?=$tep_id?>" style="display:none">
				<form id="extForm<?=$tep_id?>" href="javascript:;" onSubmit="return(false)">
					<?php extract($vt); generic_form($vt);?>
					<div class="flt">
						<div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
						<button <?php if(isset($_id)) {?> data-modal="<?=$_id ?>" <?php }?> class="btn-floating btn-large red " id="formSave" href="#extForm<?=$tep_id?>">
							<img src="icons/save.svg" class="large material-icons" >
						</button>
						<ul>
							 <li><a data-position="left" data-delay="50" data-tooltip="Close Window" class="tooltipped btn-floating blue" id="close"> <img src="icons/reply.svg" class="material-icons" ></a></li>
							 <li><a data-position="left" data-delay="50" data-tooltip="Reset" class="tooltipped btn-floating green" id="formReset"> <img src="icons/clear.svg" class="material-icons" /></a></li>

						</ul>
						</div>
					</div>
				</form>
			</div>
			<?php }?>
		</form>
	<?php } $ext_c++; } }?>
