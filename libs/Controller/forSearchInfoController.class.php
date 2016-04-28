<?php
header("Content-Type: text/html; charset=UTF-8");
class forSearchInfoController{

	function showVipInfoList(){

		VIEW::assign(array(
			'retArr'=>M('forSearchInfo')->getVipList(),
			'weixinName' => $_SESSION['weixinName']
		));
		VIEW::display('admin/forSearchVipInfo/searchVipInfo.html');
	}
}