<?php
	
	session_start();

	if(isset($_GET['phone'])){
		$phone = $_GET['phone'];
	}else{
		$phone = "none";
	}

	include 'global_info.php';
	
	header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http%3A%2F%2F'.$redirect_url.'&response_type=code&scope=snsapi_userinfo&state='.$phone.'&connect_redirect=1#wechat_redirect');

?>
