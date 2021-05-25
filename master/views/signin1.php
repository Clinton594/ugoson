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
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/login.css">
	<script language="javascript" type="text/javascript" src="js/jquery_3.3.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/controllers.js"></script>
	<title><?=$company->name?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $px = !empty($company->logo_ref)? $company->logo_ref : "images/logo.png" ?>" />
  <style>#forgot-panel{background-color: white}.form-control {width: 100%;height: 40px;border: solid 1px gray;padding: 10px;}</style>
</head>
<body>
  <div class="main">
    <div class="log">
      <div id="log">
        <form method="POST" action="javascript:;" onsubmit="return(false)">
          <div class="image">
            <img src="images/default.png" />
          </div>
          <div class="form">
            <h1 class="hide username">Jerry Afrobeat</h1>
            <?php if(isset($_GET['msg'])){?><p style="color: white">! <?=$_GET['msg']?></p> <?php }?>
              <div class="w-100">
                <input class="forgot-element" name="email" type="text" placeholder="Email or Username" required="required">
                <button onClick="getfields(this);"><img src="icons/send_black.svg"></button>
              </div>

              <nav>
                <p >
                  <a href="#" class="forgot-password" style="color:white">
                    <img src="images/forgot-pass.png" height="25" width="25"/> Forgot password </a>
                </p>
                <p>
                  <a href="<?=$uri->site?>" class="link">
                    <img style="float: left; height: 30px" src="<?php echo $px = !empty($company->logo_ref)? $company->logo_ref : "images/logo.png" ?>" />
                    <b style="float: left;color: white;margin: 0px 10px;font-size: 24px;"><?php echo !empty($company->name)? $company->name : "Unassigned Company" ?></b>
                  </a>
                </p>
              </nav>
            </div>
          </form>
        </div>

      </div>
    </div>
<script>
 function getfields(ths){
	var form   = $(ths).closest('form');
	if(form[0].checkValidity()){
    var $input  = form.find('input');
		var $val    = $input.val();
		var $type   = $input.attr('type');
		var $case    = 1;
		var obj = {value:$val, case:$case};
		$(ths).startLoader(true);

		$.post(site.backend+'process/login', obj, function(response){
			$(ths).stopLoader(true);
			let res = isJson(response);
      if(res === false){
        console.log(response);
        alert('An Error Occured')
      }else if(res.status == 0){
				toast(res.message, 'red')
			}else if(res.status == 1){// Get username
				$('h1.username').removeClass('hide').text(res.name);
				$input.attr({name:"password",type:'password','placeholder':'Password','data-id':res.id}).val('');
				form.attr({action: site.backend+'process/login?case=2', method:"post"}).removeAttr('onsubmit').append($('<input>').attr({name:'value', type:'hidden',value:res.id}));
				$(ths).removeAttr('onclick');
				if(res.pix){$('.image img').attr({src:res.pix});}
			}
		});
	}
}

$("document").ready(function() {
  $("#log").forgoTPassword({
    formName:"admin_signin",
    submitType:"process"
  });
})
</script>
</body>
</html>
