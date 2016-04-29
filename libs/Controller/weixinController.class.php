<?php
session_start();
class weixinController{

	function showBaseInfo(){

		VIEW::assign( array(
			'weixinInfo' =>$_SESSION['weixinInfo'],
			'weixinName'=> $_SESSION['weixinName']
		));
		VIEW::display('admin/froIntegralSetDaily/integralSetDaily.html');

	}

	function editBaseInfo(){
		echo json_encode(M('weixin')->editWeixinBaseInfo());
	}

}