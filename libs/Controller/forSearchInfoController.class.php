<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once('commonController.class.php');
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoController extends commonController{

	function showVipInfoList(){

		VIEW::assign(array(
			'retArr'=>M('forSearchInfo')->getVipList(),
			'weixinName' => $_SESSION['weixinName']
		));
		VIEW::display('admin/forSearchVipInfo/searchVipInfo.html');
	}

	function delVipInfoByID(){
		echo json_encode(parent::delInfoByID('forSearchInfo','delVipByID'));
	}
}