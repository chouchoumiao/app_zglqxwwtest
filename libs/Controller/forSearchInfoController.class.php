<?php
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoController{

	public $auth;

	//public function __construct(){
	//	session_start();
	//	if(!(isset($_SESSION['auth']))&&(PC::$method!='login')){
	//		gotoUrl(ROOTURL.'admin.php?controller=admin&method=login');
	//	}else{
	//		$this->auth = isset($_SESSION['auth'])?$_SESSION['auth']:array();
	//	}
	//}
	function showVipInfoList(){

		VIEW::assign(array(
			'retArr'=>M('forSearchInfo')->getVipList()
		));
		VIEW::display('admin/forSearchVipInfo/searchVipInfo.html');
	}
}