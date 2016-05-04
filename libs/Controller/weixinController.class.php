<?php
session_start();
class weixinController{

	function showBaseInfo(){

		VIEW::assign( array(
			'weixinInfo' =>$_SESSION['weixinInfo']
		));
		VIEW::display('admin/forWeixin/integralSetDaily.html');

	}
	function editBaseInfo(){
		echo json_encode(M('weixin')->editWeixinBaseInfo());
	}


	function showVipBaseInfo(){

		VIEW::assign( array(
			'weixinInfo' =>$_SESSION['weixinInfo']
		));
		VIEW::display('admin/forVip/integralNewVip.html');

	}
	function editVipBaseInfo(){
		echo json_encode(M('weixin')->editVipBaseInfo());
	}

}