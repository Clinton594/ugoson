<?php
header("Expires: ".date('D, d M Y h:i:s')." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if(!empty($session->loggedin) && !empty($session->user_id)){
  header("Location: {$uri->backend}home");
  die();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/materialize.css" rel="stylesheet" type="text/css" media="screen,projection"/>
  <link href="css/index.css" rel="stylesheet" type="text/css"/>
  <link href="css/login.css" rel="stylesheet" type="text/css"/>
  <link href="css/responsive.css" rel="stylesheet" type="text/css"/>
  <title>BACKEND -  <?=$company->name; ?></title>
  <script type="text/javascript" src="js/jquery_3.3.1.min.js"></script>
  <script type="text/javascript" src="js/controllers.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="<?= $px = !empty($company->logo_ref)? $company->logo_ref : "images/logo.png" ?>" />
</head>
<body style="overflow: hidden">
  <div class="mycontainers">
    <div class="parallax-container slht1">
      <div class="parallax"><img src="images/background.jpg" style="display: block"></div>
      <div class="container" >
        <div class="row">
          <div class="col s12 m8 offset-m2">
            <div class="col s12" style="margin-top:50px;">
              <div class="transparent z-depth-0 ">
                <h4 class="">
                  <a href="<?=$uri->site?>" class="brand-logo center-align white-text">
                    <img src="<?= $px = !empty($company->logo_ref)? $company->logo_ref : "images/logo.png" ?>" /> <small><b><?= strtoupper($company->name); ?>'s Backend</b></small></a>
                </h4>
              </div>
            </div>
            <div class="col s12">
              <div class="white login_f z-depth-0" style="padding: 40px 10px;">
                <form id="login_f" style="padding:10px 10%" class="row" action="proccess_login.php" method="post" enctype="application/x-www-form-urlencoded">
                  <h5 style="font-weight:bold" class="orange-text darken-2-text center-align">LOGIN</h5>
                  <div class="input-field col s12">
                    <input placeholder="Username | Email" type="text" class="inp2 forgot-element" required="required" name="email">
                  </div>
                  <div class="input-field col s12">
                    <input placeholder="Password" type="password" class="inp2" required="required" name="password">
                  </div>
                  <input type="hidden" required="required" name="normal" value="1">
                  <?php if(isset($_GET['msg'])){?><small><i style="color: indianred"><?=$_GET['msg']?></i></small><?php }?>
                    <div class="col s6 left"><a href="javascript:;" class="forgot-password">Forgot Password</a></div>
                    <div class="col s6 right"><button class="btn right btn-cus submit" name="submit">Submit</button></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
  $("#login_f").loginForm({
    formName : "admin_signin",
    forgoTPassword: true
  }, function(response){
    window.location.href = site.backend;
  });
  </script>
  </html>
