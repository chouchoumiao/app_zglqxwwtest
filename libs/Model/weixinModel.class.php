<?php
class weixinModel{

	private $weixinID;

	function __construct(){
		$this->weixinID = $_SESSION['weixinID'];
	}

	/**
	 * 更新微信的基础信息，并且更新session
	 * @return mixed
	 */
	function editWeixinBaseInfo(){

		if($this->updateInfo()){

			//更新session的值
			if(!$this->getNewInfo()){
				$arr['success'] = "NG";
				$arr['msg'] = "更新session失败！2秒后返回...";

				return $arr;
			}

			$_SESSION['weixinInfo'] = $this->getNewInfo();
			$arr['success'] = "OK";
			$arr['msg'] = "设置成功！2秒后返回...";
		}else{
			$arr['success'] = "NG";
			$arr['msg'] = "设置失败！2秒后返回...";
		}

		return $arr;
	}

	/**
	 * 更新微信的基础信息
	 * @return mixed
	 */
	private function updateInfo(){
		$thisIntegral = addslashes($_POST["thisIntegral"]);
		$dailyCodeIntegral = addslashes($_POST["dailyCodeIntegral"]);

		$sql = "update ConfigSet
            set CONFIG_INTEGRALSETDAILY  = $thisIntegral,
                CONFIG_DAILYPLUS =  $dailyCodeIntegral
            where WEIXIN_ID = $this->weixinID";
		return DB::query($sql);
	}

	/**
	 * 取出更新后的数据
	 * @return mixed
	 */
	private function getNewInfo(){
		//取得最新数据并更新缓存内容
		$sql = "select CONFIG_INTEGRALINSERT,
                       CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
                       CONFIG_INTEGRALREFERRER,
                       CONFIG_INTEGRALSETDAILY,
                       CONFIG_DAILYPLUS,
                       CONFIG_VIP_NAME
                from ConfigSet
                where WEIXIN_ID = $this->weixinID";
		return DB::findOne($sql);

	}
	//function getBaseInfo(){
    //
	//	//取得该微信号的基础数据
	//	$configLineData = $this->getWeixinBaseInfo();
    //
	//	//如果取得不存在 则初始化为 0,0,0,0,0,'积分'
	//	if (!$configLineData) {
	//		if (!$this->addWeixinBaseInfo()) {
	//			return array(
	//				"CONFIG_INTEGRALINSER" => 0,
	//				"CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP" => 0,
	//				"CONFIG_INTEGRALREFERRER" => 0,
	//				"CONFIG_INTEGRALSETDAILY" => 0,
	//				"CONFIG_DAILYPLUS" => 0,
	//				"CONFIG_VIP_NAME" => '积分'
	//			);
	//		} else {
	//			return array();
	//		}
	//	}
	//	return $configLineData;
	//}
    //
	///**
	// * 取得该微信号的基础数据
	// * @return mixed
	// */
	//private function getWeixinBaseInfo(){
	//	$sql = "select CONFIG_INTEGRALINSERT,
	//		   CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
	//		   CONFIG_INTEGRALREFERRER,
	//		   CONFIG_INTEGRALSETDAILY,
	//		   CONFIG_DAILYPLUS,
	//		   CONFIG_VIP_NAME from ConfigSet
	//	where WEIXIN_ID = $this->weixinID";
	//	return DB::findOne($sql);
	//}
    //
	///**
	// * 没有设置的情况下下默认初始化为 0,0,0,0,0,'积分'
	// * @return mixed
	// */
	//private function addWeixinBaseInfo(){
	//	$sql = "insert into ConfigSet
	//					(WEIXIN_ID,
	//					CONFIG_INTEGRALINSERT,
	//					CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
	//					CONFIG_INTEGRALREFERRER,
	//					CONFIG_INTEGRALSETDAILY,
	//					CONFIG_DAILYPLUS,
	//					CONFIG_VIP_NAME
	//					) values (
	//					$this->weixinID,
	//					0,
	//					0,
	//					0,
	//					0,
	//					0,
	//					'积分'
	//					)";
	//	return DB::query($sql);
	//}

}