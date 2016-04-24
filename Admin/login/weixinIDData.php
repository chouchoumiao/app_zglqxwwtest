<?php
session_start();
$user = $_SESSION['user'];

if(isset($user)){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
    
	$weixinID = addslashes($_POST['weixinID']);

    if(isset($weixinID)){
        $_SESSION['weixinID'] = $weixinID;
        $arr['success'] = 1;
        $arr['msg'] = "OK";
    }else{
        $arr['success'] = 0;
        $arr['msg'] = "NG";
    }
}else{
	$arr['success'] = 0;
	$arr['msg'] = "session出错，请重新登录！";
}

echo json_encode($arr);