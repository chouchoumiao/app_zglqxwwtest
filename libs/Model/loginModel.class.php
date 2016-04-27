<?php
class loginModel{

	/**
	 * �ж��û�����
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
	 * ���¸��û��ĵ�¼��Ϣ
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
	 * ��ʾ��ҳ���������Ϣ
	 * @return array
	 */
	function showMain($name){

		//��ʼ�����ֶ�
		$weixinName = '';
		$isWeixinInfoExist = false;
		$isEventListExist = false;
		$msg = '';
		$username = '';
		$eventNameArr = array();
		$eventUrlArr = array();
		$thisWeixinID = '';

		//��ȡ���û����п��õĹ��ںŵĻ���Ϣ
		$weixinInfo = $this->getWeiInfoByName($name);

		//�жϸ��û��Ƿ���ڿ����õĹ��ں�
		if(empty($weixinInfo)){
			$msg = "��ǰδ���ù��ںţ�����ӹ��ں���Ϣ��";
		}else{
			$isWeixinInfoExist = true;
			if(!isset($_SESSION['weixinID'])){
				$thisWeixinID = $weixinInfo[0]['id'];
				//追加session中的weixinID
				$_SESSION['weixinID'] = $thisWeixinID;
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

		//���������Ϣ
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
	 * ��ȡ���û����п��õĹ��ںŵĻ���Ϣ
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
	 * ȡ�øù��ں����õĻlistһ��
	 * @param $weiID
	 * @return mixed
	 */
	private function getEventListByWeiID($weiID){
		$sql = "select * from setEventForAdmin where WEIXIN_ID = $weiID";
		return DB::findOne($sql);

	}

}