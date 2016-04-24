<?php
	class adminController{

		public $auth;

		public function __construct(){
			session_start();
			if(!(isset($_SESSION['auth']))&&(PC::$method!='login')){
				$this->gotoUrl(ROOTURL.'admin.php?controller=admin&method=login');
			}else{
				$this->auth = isset($_SESSION['auth'])?$_SESSION['auth']:array();
			}
		}

		//login in
		public function login(){
			if(!isset($_POST['user'])){
				VIEW::display('admin/index.html');
			}else{
				$this->checklogin();
			}
		}

		private function checklogin(){
			if(empty($_POST['user'])||empty($_POST['pass'])){
				//$this->showmessage('登录失败！', 'admin.php?controller=admin&method=login');
				$arr['success'] = 0;
				$arr['msg'] = '登录失败！';
				echo json_encode($arr);
				exit;
			}
			$username = daddslashes($_POST['user']);
			$password = daddslashes($_POST['pass']);

			$authobj = M('auth');
			$auth = $authobj->checkauth($username, $password);
			if($auth){
				$_SESSION['auth'] = $auth;
				$arr['success'] = 1;
				echo json_encode($arr);
				exit;
			}else{
				$arr['success'] = 0;
				$arr['msg'] = '登录失败！';
				echo json_encode($arr);
				exit;
			}
		}

		public function logout(){
			unset($_SESSION['auth']);
			$this->gotoUrl('admin.php?controller=admin&method=login');
		}

		//main
		public function index(){
			VIEW::assign($this->showMain());
			VIEW::display('admin/main.html');
		}

		/**
		 * 显示主页面的所有信息
		 * @return array
		 */
		private function showMain(){

			//初始化各字段
			$weixinName = '积分分';

			$isWeixinInfoExist = false;
			$isEventListExist = false;
			$msg = '';

			if(isset($_SESSION['weixinID'])){
				$thisWeixinID = $_SESSION['weixinID'];
			}else{
				$thisWeixinID = '';
			}
			$username = '';
			$eventNameArr = array();
			$eventUrlArr = array();

			//获取该用户所有可用的公众号的基本信息
			$weixinInfo = $this->getWeiInfoByName();

			//判断该用户是否存在可设置的公众号
			if(empty($weixinInfo)){
				$msg = "当前未设置过公众号，请添加公众号信息！";
			}else{
				$isWeixinInfoExist = true;
				if(!$thisWeixinID){
					$thisWeixinID = $weixinInfo[0]['id'];
					$_SESSION['weixinID'] = $thisWeixinID;
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
		private function getWeiInfoByName(){
			$userName = $this->auth['username'];
			$sql = "select * from AdminToWeiID
					where username = '$userName'
					AND weixinStatus = 1";
			$weixinInfo = DB::findAll($sql);
			if($weixinInfo){
				return $weixinInfo;
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


		public function changeWeixinID(){

			if(isset($_SESSION['auto'])&&isset($_SESSION['weixinID'])){
				$weixinID = addslashes($_POST['weixinID']);

				if(isset($weixinID)){
					$_SESSION['weixinID'] = $weixinID;
					$arr['success'] = 1;
					$arr['msg'] = "OK".$_SESSION['weixinID'];
				}else{
					$arr['success'] = 0;
					$arr['msg'] = "NG";
				}
			}else{
				$arr['success'] = 0;
				$arr['msg'] = "session出错，请重新登录！";
			}
		}


		/**
		 * 跳转函数
		 * @param $url
		 */
		private function gotoUrl($url){
			echo "<script>window.location.href='$url'</script>";
			exit;
		}
	}