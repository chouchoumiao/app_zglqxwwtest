<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once('commonController.class.php');
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoController extends commonController{

	/************************************************会员信息相关部分*********************************************/
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
	/************************************************会员信息相关部分*********************************************/


	/************************************************取得签到码相关部分*********************************************/
	/**
	 * 取得签到码
	 */
	function getDailyCode(){
		echo json_encode(M('forSearchInfo')->getDailyCodeWithWeixinID());
	}
	/************************************************取得签到码相关部分*********************************************/

	/************************************************答题分类相关部分***********************************************/
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
	/**********************************************取得全答对时间排行相关部分*******************************************/

	/**********************************************取得全答对时间排行相关部分*******************************************/
	function showQuestionOkCountList(){
		VIEW::assign(array(
			'retArr'=>M('forSearchInfo')->getQuestionOkCountList()
		));
		VIEW::display('admin/forSearchInfo/question_OKCountSearch.html');
	}

	/**
	 * 获取答对10题的前200名信息
	 */
	function getQuestion200(){
		echo json_encode(M('forSearchInfo')->getQuestionOk200());
	}

	/**********************************************取得全答对时间排行相关部分*******************************************/

}