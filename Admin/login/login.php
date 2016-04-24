<?php
session_start();
header("Content-type: text/html; charset=utf-8");
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$action = addslashes($_GET['action']);

if ($action == 'login') {  //登录
	$user = stripslashes(trim(addslashes($_POST['user'])));
	$pass = stripslashes(trim(addslashes($_POST['pass'])));
	if (empty ($user)) {
		echo '用户名不能为空';
		exit;
	}
	if (empty ($pass)) {
		echo '密码不能为空';
		exit;
	}
	$md5pass = md5($pass);

    $sql = "select * from AdminUser where username='$user' and isdeleted = 0";

	//$adminInfo = getlineBySql($sql);
	DB::init('mysql', $config['dbconfig']);
	$adminInfo = DB::findOne($sql);;
	print_r($adminInfo);exit;
	if ($md5pass == $adminInfo['password']){
		$ps = true;
	}else{
		$ps = false;
	}
	
	if ($ps) {
		$counts = $adminInfo['login_counts'] + 1;
		$_SESSION['user'] = $adminInfo['username'];
		$_SESSION['login_time'] = $adminInfo['login_time'];
		$_SESSION['login_counts'] = $counts;
        $nowTime  = date("Y-m-d H:i:s",time());
		$ip = GetIP();
		$updateSql = "update AdminUser
					  set login_ip = '$ip',
						  login_counts='$counts',
						  loginTime = '$nowTime'
					  where username = '$user'";
		$errono = SaeRunSql($updateSql);
		if($errono == 0){
			$arr['success'] = 1;
			$arr['msg'] = '登录成功！';
		}else{
			$arr['success'] = 0;
			$arr['msg'] = '登录失败';
		}
	} else {
		$arr['success'] = 0;
	    $arr['msg'] = '用户名或密码错误！';
	}
	echo json_encode($arr);
}
else if ($action == 'logout') {  //退出
	unset($_SESSION);
	session_destroy();
	//echo '1';
	echo "<script>window.location.href='index.php';</script>";
}
