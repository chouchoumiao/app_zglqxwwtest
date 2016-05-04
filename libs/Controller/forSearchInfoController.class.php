<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once('commonController.class.php');
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoController extends commonController{

	/**
	 * 取得会员一览表
	 */
	function showVipInfoList(){

		VIEW::assign(array(
			'retArr'=>M('forSearchInfo')->getVipList(),
			'weixinName' => $_SESSION['weixinName']
		));
		VIEW::display('admin/forVip/searchVipInfo.html');
	}

	/**
	 * 根据ID删除会员信息
	 */
	function delVipInfoByID(){
		echo json_encode(parent::delInfoByID('forSearchInfo','delVipByID'));
	}

	/**
	 * 取得签到码
	 */
	function getDailyCode(){
		echo json_encode(M('forSearchInfo')->getDailyCodeWithWeixinID());
	}


	/**
	 * 显示答题分类列表
	 */
	function showQAClassInfoList(){
		VIEW::assign(array(
			'retArr'=>M('forSearchInfo')->getQAClassInfoList()
		));
		VIEW::display('admin/forSearchInfo/searchQAClassInfo.html');
	}

	/**
	 * 根据ID删除答题分类信息
	 */
	function delQAClassByID(){
		echo json_encode(parent::delInfoByID('forSearchInfo','delQAClassByID'));
	}

}