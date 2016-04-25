<?php
class adminEditModel{

	function editAdmin($post){
		$user = $_SESSION['auth']['username'];
		if(isset($user)){
			$action = addslashes($post["action"]);
			if($action == "newPassEdit"){
				if($this->newPassEdit($post,$user)){
					return 1;
				}else{
					return -1;
				}
			}else if($action == "addUserByAdmin"){
				$rst = $this->addUserByAdmin($post);
				if($rst == -1){
					return -4;
				}else if ($rst == 2) {
					return 2;
				}else{
					return -2;
				}
			}else if($action = "eventListSet"){
				$rst = $this->eventListSet($post);
				if($rst == -1){
					return -5;
				}else if ($rst == 3){
					return 3;
				}else{
					return -3;
				}
			}
		}else{
			return 0;
		}
	}

	private function newPassEdit($post,$user){
		//取得set页面传递过来的数据
		$newPass = addslashes($post["newPass"]);

		$md5NewPass = md5($newPass);
		$logintime  = date("Y-m-d H:i:s",time());

		$sql = "update AdminUser
				set password  = '$md5NewPass',
					editTime='	$logintime'
				where username = '$user'";
		$errono = DB::query($sql);
		if($errono){
			return true;
		}else{
			return false;
		}
	}

	private function addUserByAdmin($post){
		//取得set页面传递过来的数据
		$addUser = addslashes($post["addUser"]);

		$sql = "select * from AdminUser
						where username='$addUser'
						and isdeleted = 0";
		$addUserInfo = DB::findOne($sql);
		if($addUserInfo['username'] != ""){
			return -1;
		}else{
			$newPass = addslashes($post["newPass"]);

			$md5NewPass = md5($newPass);
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

			$errono = DB::query($sql);

			if($errono){
				return 2;
			}else{
				return -2;
			}
		}
	}

	private function eventListSet($post){
		$weixinID = $_SESSION['weixinID'];
		$sql = "select * from  setEventForAdmin where WEIXIN_ID = $weixinID";
		$data = DB::findAll($sql);

		if($data){
			return -1;
		}else{
			$eventNameList = $post['eventNameList'];
			$eventBackUrlList = $post['eventBackUrlList'];
			$eventForwardUrlList = $post['eventForwardUrlList'];
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
			$errono = DB::query($sql);
			if($errono){

				return 3;
			}else{
				return -3;
			}

		}
	}
}