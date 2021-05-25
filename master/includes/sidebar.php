<div class="myNav transit brown darken-4 white-text">
	<ul class="sideNav">
		<?php
			$parent_id	= 1;
			$sidelinks 	= $param->get_page_param("roles");
			$params 		= $param->get_params();
			// see($user_role)
			foreach($sidelinks as $name => $data ) {
				if(empty($user_role[$parent_id])){$parent_id++;continue;} ?>
					<li class="myMenu valign-wrapper <?=$_color[$parent_id - 1]?>">
						<div class="iconColor valign-wrapper width70">
							<img style="height: 20px" class="iconPad" src="<?=$uri->backend?>icons/<?=$data->icon?>.svg" />
							<span class="menu">
								<span class="option white-text"><?=ucwords($name)?></span>
								<ul>
									<div class="menuHead <?=$_color[$parent_id - 1]?>">
										<span class="white-text"><?=ucwords($name)?></span>
									</div>
									<?php foreach($data->links as $k => $link){
										$onclick = null;
										$url = explode("/", $link->url);
										$t = end($url);
										$t = !empty($params->{$t}) ? "_{$t}" : $t;
										$l = $k+1;
										if(empty($user_role[$parent_id]["$l"]))continue;

										$javascript = explode("/", $link->url);
										if(reset($javascript) == "javascript"){
											$t = $link->title;
											$link->url = "javascript:;";
											$onclick = end($javascript);
										}

										?>
										<a data-title="<?=strToFilename($t)?>" href="<?=$link->url?>" <?php if(!empty($onclick)){?> onclick="<?=$onclick?>" <?php }else{?> class="noref" <?php } ?>>
											<li><?=$link->title?></li>
										</a>
									<?php } ?>
								</ul>
							</span>
						</div>
					</li>
			<?php $parent_id++;}?>
		<li class="myMenu valign-wrapper">
			<a href="<?=$uri->backend?>logout" style="width: 100%;" >
				<div class="iconColor valign-wrapper">
					<img style="width: 55px" src="<?=$uri->backend?>icons/power_settings_new.svg" class="material-icons myIconSize iconColor iconPad" />
					<span class="menu">
						<span class="option">Logout</span>
					</span>
				</div>
			</a>
		</li>
		<li class="myMenu valign-wrapper prof_">
			<a class="white-text noref" data-title="_log"  href="content-view/log" style="color: antiquewhite; width: 100%">
				<div class="iconColor valign-wrapper">
					<img style="height: 30px;margin: 0 10px;border-radius: 50%;width: 30px;float: left;padding: 0;" src="<?=$user->image_col?>" class="material-icons myIconSize iconColor iconPad" />
					<span class="menu">
						<span class="option"><?=$user->name_col?></span>
					</span>
				</div>
			</a>
		</li>
	</ul>
</div>
