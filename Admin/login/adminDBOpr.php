<?php
session_start();
$user = $_SESSION['user'];
$weixinID = $_SESSION['weixinID'];

if(isset($user)){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/IF/CommIF.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/IF/DBOp/SaeDBPOpr.php');
	$action = addslashes($_POST["action"]);
	
	if($action == "newPassEdit"){
		//取得set页面传递过来的数据
		$newPass = addslashes($_POST["newPass"]);

		$md5NewPass = md5($newPass);
		//$logintime = mktime();
		$logintime  = date("Y-m-d H:i:s",time());
		$ip = GetIP();
		$counts = $_SESSION['login_counts'] + 1;
		
		$sql = "update AdminUser
				set password  = '$md5NewPass',
					loginTime='	$logintime',
					login_ip = '$ip',
					login_counts='$counts'
				where username = '$user'";
		$errono = SaeRunSql($sql);
		if($errono == 0){
			$arr['success'] = 1;
			$arr['msg'] = "更新新密码成功！";
		}else{
			$arr['success'] = 0;
			$arr['msg'] = "更新新密码失败！";
		}
	}else if($action == "addUserByAdmin"){
		//取得set页面传递过来的数据
		$addUser = addslashes($_POST["addUser"]);
		
        $sql = "select * from AdminUser
				where username='$addUser'
				and isdeleted = 0";
		$addUserInfo = getlineBySql($sql);
		if($addUserInfo['username'] != ""){
			$arr['success'] = 0;
			$arr['msg'] = "已有该用户名了，请更换";
			echo json_encode($arr);	
			exit;
			
		}else{
			$newPass = addslashes($_POST["newPass"]);

			$md5NewPass = md5($newPass);
			//$logintime = mktime();
			$logintime  = date("Y-m-d H:i:s",time());
			$ip = GetIP();

			$sql = "insert into AdminUser
								(username,
								password,
								loginTime,
								login_counts,
								login_ip,
								isdeleted
								) values (
								'$addUser',
								'$md5NewPass',
								'$logintime',
								0,
								'$ip',
								0
								)";
			
			$errono = SaeRunSql($sql);
			if($errono == 0){
				$arr['success'] = 1;
				$arr['msg'] = "新用户追加成功！";
			}else{
				$arr['success'] = 0;
				$arr['msg'] = "新用户追加失败！";
			}
		}	
	}else if($action = "eventListSet"){
        $sql = "select * from  setEventForAdmin where WEIXIN_ID = $weixinID";
        $data = getDataBySql($sql);

        if($data){
            $arr['success'] = 0;
            $arr['msg'] = "已经存在该公众号的设置信息了，请确认！";
        }else{
            $eventNameList = $_POST['eventNameList'];
            $eventBackUrlList = $_POST['eventBackUrlList'];
            $eventForwardUrlList = $_POST['eventForwardUrlList'];
            $nowTime = date('Y-m-d H:i:s',time());

            $sql = "insert into setEventForAdmin
								(WEIXIN_ID,
								eventNameList,
								eventUrlList,
								eventForwardUrlList,
								editDateTime
								) values (
								$weixinID,
								'$eventNameList',
								'$eventBackUrlList',
								'$eventForwardUrlList',
								'$nowTime'
								)";
            $errono = SaeRunSql($sql);
            if($errono == 0){
                $arr['success'] = 1;
                $arr['msg'] = "设置成功！";
            }else{
                $arr['success'] = 0;
                $arr['msg'] = "设置失败！.$sql";
            }
        }
    }
}else{
	$arr['success'] = 0;
	$arr['msg'] = "session出错，请重新登录！";
}

echo json_encode($arr);