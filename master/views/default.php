<?php
	if(empty($session->loggedin) || empty($session->user_id)){
	  header("Location: {$uri->backend}logout");
		die();
	}
	$user 		=	$auth->user();
	$user_role	= $auth->role();
	// see($user_role);
	$backend 	= absolute_filepath($uri->backend);
	//If it still doesn't find any user from the id suplied, log him out
	if(empty($user))header("Location: {$uri->backend}logout/?msg=user not found");
	$_SESSION["accesslevel"] = $user->accesslevel;
?>
<!DOCTYPE html>
<html >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BACKEND - <?=strtoupper($company->name); ?></title>
	<?php
	 $browser_caching = empty($browser_caching) ? "" : $browser_caching;
	?>
	<?php require_once("{$backend}includes/link_css.php") ?>
	<?php require_once("{$backend}backendProject/link_css.php") ?>
</head>

<body class="scrollbar-macosx">
	<div  class="navbar-fixed height50">
		<nav class="height50">
      <div class="nav-wrapper myLineHeight  pink darken-2">
				<div class="brand">
					<a href="<?=$uri->site?>" class="brand-logo left" id="_logo" style="position:relative !important">
						<img src="<?=$company->logo_ref?>" height="50px" align="left">
					</a>
					<a href="<?=$uri->backend?>" class="left hide-on-med-and-down "><span id='_name'><?=strtoupper($company->name); ?> - BACKEND</span></a>
				</div>
        <ul class="pink darken-4 right screen-title">
					<a href="<?=$uri->backend?>">
						<li id="pageLabel">Dashboard</li>
					</a>
        </ul>
      </div>
    </nav>
		<div>
		 <div class="progress" style="position:fixed; top:45px">
			  <div class="indeterminate"></div>
			</div>
		</div>
  </div>
 	<?php require_once("{$backend}includes/sidebar.php") ?>
  <div class="row">
		<div class="mv" id="default_div"></div>
	</div>
</body>
<footer>
	<?php require_once("{$backend}includes/link_js.php") ?>
	<?php require_once("{$backend}backendProject/link_js.php") ?>
</footer>
</html>
