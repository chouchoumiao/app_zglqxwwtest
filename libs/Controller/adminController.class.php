<?php
class adminController{

	public $auth;

	public function __construct(){
		session_start();
		if(!(isset($_SESSION['auth']))&&(PC::$method!='login')){
			gotoUrl(ROOTURL.'admin.php?controller=admin&method=login');
		}else{
			$this->auth = isset($_SESSION['auth'])?$_SESSION['auth']:array();
		}
	}

	/**
	 * 后台用户密码登录
	 *
	 */
	public function login(){
		if(!isset($_POST['user'])){
			VIEW::display('admin/login.html');
		}else{
			$this->checklogin();
		}
	}

	/**
	 * 判断用户登录逻辑
	 * AJAX返回
	 *
	 */
	private function checklogin(){
		if(empty($_POST['user'])||empty($_POST['pass'])){
			$arr['success'] = 0;
			$arr['msg'] = '登录失败！';
			echo json_encode($arr);
			exit;
		}
		$username = daddslashes($_POST['user']);
		$password = daddslashes($_POST['pass']);

		$authobj = M('login');
		$auth = $authobj->checkauth($username, $password);
		if($auth){
			$_SESSION['auth'] = $auth;
			$authobj->updateAdminInfo($auth);
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

	/**
	 * 用户退出登录
	 *
	 */
	public function logout(){
		unset($_SESSION['auth']);
		unset($_SESSION['weixinID']);
		gotoUrl('admin.php?controller=admin&method=login');
	}

	/**
	 * 显示主页面
	 *
	 */
	public function index(){

		VIEW::assign(M('login')->showMain($this->auth['username']));
		VIEW::display('admin/index.html');
	}

	/**
	 * 点击不同公众号及时更新该公众号的微信ID
	 * AJAX返回
	 */
	public function changeWeixinID(){

		if(isset($_POST['weixinID'])){
			$_SESSION['weixinID'] = intval($_POST['weixinID']);
			$arr['success'] = 1;
		}else{
			$arr['success'] = 0;
		}
		echo json_encode($arr);
	}

	/**
	 * 取得用户信息并显示
	 *
	 */
	public function showUserInfo(){
		//VIEW::assign(M('admin')->getUserByAdmin());
		VIEW::assign(array(
					'class_list'=>M('admin')->getUserByAdmin()
					));
		VIEW::display('admin/adminUserSearch.html');

	}

	public function delUserInfoByID(){

		if(!isset($_POST["id"])){
			return false;
		}
		$id = intval(addslashes($_POST["id"]));

		return M('admin')->delUserByID($id);

	}


	/**
	 * 用户管理
	 *
	 */
	public function adminEdit(){
		$rst = M('adminEdit')->editAdmin($_POST);
		switch ($rst){
			case 0:
				$arr['success'] = 0;
				$arr['msg'] = 'session出错，请重新登录！';
			case 1:
				$arr['success'] = 1;
				$arr['msg'] = '更新新密码成功！';
				break;
			case -1:
				$arr['success'] = 0;
				$arr['msg'] = '更新新密码失败！';
				break;
			case 2:
				$arr['success'] = 1;
				$arr['msg'] = '新用户追加成功！';
				break;
			case -2:
				$arr['success'] = 0;
				$arr['msg'] = '新用户追加失败！';
			case 3:
				$arr['success'] = 1;
				$arr['msg'] = '设置成功！';
				break;
			case -3:
				$arr['success'] = 0;
				$arr['msg'] = '设置失败！';
				break;
			case -4:
				$arr['success'] = 0;
				$arr['msg'] = '已有该用户名了，请更换！';
			case -5:
				$arr['success'] = 0;
				$arr['msg'] = '已经存在该公众号的设置信息了，请确认！';
			default:
				break;
		};
		echo json_encode($arr);
	}
}