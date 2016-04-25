<?php
class loginModel{

	/**
	 * 判断用户密码
	 * @param $username
	 * @param $password
	 * @return bool
	 */
	function checkauth($username, $password){
		$adminobj = M('admin');
		$auth = $adminobj -> findOne_by_username($username);
		if((!empty($auth))&& $auth['password']==md5($password)){
			return $auth;
		}else{
			return false;
		}
	}

	/**
	 * 更新改用户的登录信息
	 * @param $auth
	 */
	function updateAdminInfo($auth){
		$counts = $auth['login_counts'] + 1;
		$user = $auth['username'];
		$nowTime  = date("Y-m-d H:i:s",time());
		$ip = GetIP();
		$sql = "update AdminUser
					  set login_ip = '$ip',
						  login_counts='$counts',
						  loginTime = '$nowTime'
					  where username = '$user'";
		DB::query($sql);
	}

	/**
	 * 显示主页面的所有信息
	 * @return array
	 */
	function showMain($name){

		//初始化各字段
		$weixinName = '';
		$isWeixinInfoExist = false;
		$isEventListExist = false;
		$msg = '';
		$username = '';
		$eventNameArr = array();
		$eventUrlArr = array();
		$thisWeixinID = '';

		//获取该用户所有可用的公众号的基本信息
		$weixinInfo = $this->getWeiInfoByName($name);

		//判断该用户是否存在可设置的公众号
		if(empty($weixinInfo)){
			$msg = "当前未设置过公众号，请添加公众号信息！";
		}else{
			$isWeixinInfoExist = true;
			if(!isset($_SESSION['weixinID'])){
				$thisWeixinID = $weixinInfo[0]['id'];
			}else{
				$thisWeixinID = $_SESSION['weixinID'];
			}
			$username = $weixinInfo[0]['username'];
			$baseInfo = getConfigWithMMC($thisWeixinID);
			if($baseInfo){
				$weixinName = $baseInfo['CONFIG_VIP_NAME'];
			}
			$info = $this->getEventListByWeiID($thisWeixinID);
			if($info){
				$isEventListExist = true;
				$eventNameArr = explode(",",$info['eventNameList']);
				$eventUrlArr = explode(",",$info['eventUrlList']);
			}
		}

		//返回相关信息
		return array(
			'eventNameArr'=>$eventNameArr,
			'eventUrlArr'=>$eventUrlArr,
			'weixinName'=>$weixinName,
			'userName'=>$username,
			'weixinInfo'=>$weixinInfo,
			'weixinID'=>$thisWeixinID,
			'isWeixinInfoExist'=>$isWeixinInfoExist,
			'isEventListExist'=>$isEventListExist,
			'msg'=>$msg
		);
	}

	/**
	 * 获取该用户所有可用的公众号的基本信息
	 * @return mixed
	 */
	private function getWeiInfoByName($userName){
		$sql = "select * from AdminToWeiID
					where username = '$userName'
					AND weixinStatus = 1";
		if(DB::findAll($sql)){
			return DB::findAll($sql);
		}else{
			array();
		}
	}

	/**
	 * 取得该公众号设置的活动list一览
	 * @param $weiID
	 * @return mixed
	 */
	private function getEventListByWeiID($weiID){
		$sql = "select * from setEventForAdmin where WEIXIN_ID = $weiID";
		return DB::findOne($sql);

	}

}