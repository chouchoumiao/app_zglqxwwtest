<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
class exchangeController {

	/**********************************************进行兑奖操作相关部分***********************************************/
	function exchangeDoActionCon(){
		echo json_encode(M('exchange')->doActionMod());
	}

	/**********************************************进行兑奖操作相关部分***********************************************/
}