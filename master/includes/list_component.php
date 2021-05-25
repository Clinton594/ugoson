<?php if (!empty($extension->table)) { $param = $extension; } ?>
<div class="upload_bar" id="upload_bar">
	<?php if(!empty($param->display_level)) { ?>
		<div class="top-control row" style="border-bottom: solid 1px #dbdfd6;margin: 0;">
			<a href="javascript:;" class="col s2" id="_backTop" top="_<?=strToFilename($param->display_level->source) ?>">
				<img src="icons/reply_black.svg" class="material-icons" />
				<span>Back</span>
			</a>
			<div class="right-align col s10">
				<p id="_linkTitle"></p>
			</div>
	 </div>
	<?php } ?>
	<div class="top-control row" style="margin:0">
		<div class="input-field col s3 m2 l1">
			<input name="search" type="text" disabled id='listCount' size="50" value="" />
		</div>
		<div class="input-field col s9 m10 l3">
			<input name="search" type="text"  id='_searchBox' size="50" value="" <?php if(!empty($param->search)){?> data-search='<?=json_encode($param->search) ?>' <?php } ?>/>
			<label for="_searchBox">Search <?=$param->page_title ?></label>
			<img src="icons/search.svg" class="search prefix material-icons tiny" />
		</div>
		<div class="input-field col s6 m4 l2">
			<select name="range" id='_rangeBox'>
				<option value="">Number of items</option><option value="25">25 Items</option>
				<option value="50">50 Items</option><option value="100">100 Items</option>
				<option value="150">150 Items</option><option value="200">200 Items</option>
			</select>
		</div>
		<div id="_filterList" class="_filterList">
			<?php if(!empty($param->display_level)) { ?>
				<input class="filterValue keep" id='_linkFilter' type="checkbox" checked hidden name="<?=$param->display_level->column ?>" />
			<?php } ?>
			<?php
			if(!empty($param->category)):
				foreach($param->category as $category_key => $category_data) {
					if(isset($category_data->type) && $category_data->type == "period") {?>
						<div class='input-field col s6 m4 l3 right'>
							<input class="dateFilter keep" type="text" id='<?=$category_data->column?>' size="50" name="<?=$category_data->column?>" />
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
				<div class="input-field col s12 m4 l3 right">
					<input type="text" id='_filter' size="50" class="filter" data-value="<?=$paramControl->jsonRe_encode($jn)?>"  />
					<label for="_filter">Filter By</label>
				</div>
			<?php } ?>
		</div>

	</div>
</div>
<div class="list_cont">
	<div id="dialog_display" class="open_select-div collection with-header" data-open="<?=$param->form->form_view?>"></div>
</div>
<div class="pagination">
	<div class="cont" id="cont"></div>
	<input id="active_page" type="hidden" name="page" />
</div>
