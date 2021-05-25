<?php
$auth 		= new Auth($post->value, "admin_signin");
switch ($post->case) {
	case 1:
		$user = $auth->user();
		if(!empty($user)){
			$user->name  = empty($user->name_col) ? $user->email_col : $user->name_col;
			$response = array('status'=>1,'name'=>$user->name,'id'=>$user->primary_key, 'pix'=>$user->image_col);
		}else{
			$response = array('status'=>0,'message'=>"User Not Found");
		}
	break;
	case 2:
		$admin_signin	= $auth::$column_maps;
		$post->return_values = true;
		$response = $auth->login($post);
		if($response->status){
			$user = $auth->user();
			if($user->status_col == $admin_signin->status_val){
				if (empty($post->mobile)) {
					header("Location: {$uri->backend}");
				}else $response->user_id = $user->primary_key ;
			}else{
				if (empty($post->mobile)) {
					header("Location: {$uri->backend}logout?msg=User is no an admin");
				}else {
					$response->status = 0;
					$response->message = "User is no an admin";
				}
			}
		}else {
			if (empty($post->mobile)) {
				header("Location: {$uri->backend}logout?msg={$response->message}");
			}
		}
		if (!empty($post->mobile)) {echo json_encode($response);}
	break;
	default:
		$response->message = "Invalid Case paramter";
	break;
}
?>
