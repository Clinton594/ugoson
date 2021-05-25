	<div class="myNav transit brown darken-4 white-text">
        <ul class="sideNav">
        <!--<li class="myMenu valign-wrapper white">
            	<div class="iconColor valign-wrapper">
                	<img style="height: 20px" src="icons/home.svg" class="material-icons myIconSize iconColor iconPad"/>
                	<span class="menu">
                    	<span class="option"><a class="noref" href="setup/dashboard.php">Dashboard</a></span>
                    </span>
                </div>
            </li>-->
		<?php $_i=-1; foreach($pages as $k => $v ) {
			$_i++; $vp=json_decode($v['props'],true);
			if(empty($check[$vp['id']]))continue; 
			if(count($v['subs']) > 1){
		?>
        	<li class="myMenu valign-wrapper <?php echo $_color[$_i]?>">

            	<div class="iconColor valign-wrapper width70">
                	<img style="height: 20px" class="iconPad" src="icons/<?php echo $vp['icon']; ?>.svg" />
                	<span class="menu">
                    	<span class="option white-text"><?php echo $k ?></span>
                    	<ul>
                            <div class="menuHead <?php echo $_color[$_i]?>"><span class="white-text"><?php echo $k ?></span></div><?php foreach($v['subs'] as $k1=> $v1){
								$sp=json_decode($v1,true);
								if(empty($check[$vp['id']][$sp['id']]))continue;
								$t = explode('=', $sp['url'])[1];
								$t = !empty($param[$t]) ? str_replace(' ','_',$param[$t]['page_title']) : '';
							?>
                            	<a data-title="<?=$t?>" href="<?php echo $sp['url']; ?>" class="noref"><li><?php echo $sp['name']; ?></li></a>
                            <?php } ?>
                        </ul>
                    </span>
                </div>

            </li>
			<?php } else{ ?>
				<a class="white-text noref" href="<?php echo json_decode($v['subs'][0],true)['url']; ?>"><li class="myMenu valign-wrapper <?php echo $_color[$_i]?>">
					<div class="iconColor valign-wrapper width70">
						<img style="height: 20px"  src="icons/<?php echo $vp['icon']; ?>.svg" class="iconPad" />
						<span class="menu">
							<span class="option white-text"><?php echo $k ?></span>
						</span>
					</div>
				</li></a>
			<?php }} ?><!--
			<li class="myMenu valign-wrapper">
            	<div class="iconColor valign-wrapper">
                	<i class="material-icons myIconSize iconColor iconPad">date_range</i>
                	<span class="menu">
                    	<span class="option"><a href="#changeDate" class="modal-trigger">Set Today's Date</a></span>
                    </span>
                </div>
            </li>
              <li class="myMenu valign-wrapper brown">
				  <a href="javascript:;" style="width: 100%;">
					<div class="iconColor valign-wrapper" onclick="synchronize(this)">
						<img style="height: 20px" src="icons/sync.svg" class="material-icons myIconSize iconColor iconPad" />
						<span class="menu">
							<span class="option">Synchronize</span>
						</span>
					</div>
				</a>
            </li>-->
			<li class="myMenu valign-wrapper">
				<a href="log_out.php" style="width: 100%;">
					<div class="iconColor valign-wrapper">
						<img style="height: 20px" src="icons/power_settings_new.svg" class="material-icons myIconSize iconColor iconPad" />
						<span class="menu">
							<span class="option">Logout</span>
						</span>
					</div>
				</a>
            </li>
			<li class="myMenu valign-wrapper prof_">
				<a class="white-text noref" href="setup/generic_content.php?pageType=log" style="color: antiquewhite; width: 100%">
					<div class="iconColor valign-wrapper">
						<img style="height: 30px;margin: 0 10px;border-radius: 50%;width: 30px;float: left;padding: 0;" src="<?=$pic?>" class="material-icons myIconSize iconColor iconPad" />
						<span class="menu">
							<span class="option"><?=$login_name?></span>
						</span>
					</div>
				</a>
            </li>
        </ul>
    </div>
