<?php
class weixinModel{

	private $weixinID;
	private $weixinName;

	function __construct(){
		$this->weixinID = $_SESSION['weixinID'];
		$this->weixinName = $_SESSION['weixinInfo']['CONFIG_VIP_NAME'];
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


	/**
	 * 更新会员积分相关信息
	 * @return mixed
	 */
	function editVipBaseInfo(){

		//如果存在新增会员增加积分数的设置，则更新数据库
		if(!$this->updateIntegralNew()){
			$arr['success'] = "NG";
			$arr['msg'] = "更新【成为会员初次".$this->weixinName."数】失败！2秒后返回...";
			return $arr;
		}

		//如果存在新增会员增加积分数的设置，则更新数据库
		if(!$this->updateIntegralReferrerNew()){
			$arr['success'] = "NG";
			$arr['msg'] = "更新【有推荐人的情况下新会员获得额外的".$this->weixinName."数】失败！2秒后返回...";
			return $arr;
		}

		//如果存在推荐本人积分数的设置，则更新数据库
		if(!$this->updateReferrer()){
			$arr['success'] = "NG";
			$arr['msg'] = "更新【推荐人获得".$this->weixinName."】失败！2秒后返回...";
			return $arr;
		}

		//更新后将最新的信息替换session
		$_SESSION['weixinInfo'] = $this->getNewInfo();

		$arr['success'] = "OK";
		$arr['msg'] = "更新成功！2秒后返回...";
		return $arr;
	}


	/**
	 * 如果存在新增会员增加积分数的设置，则更新数据库
	 * @return mixed
	 */
	private function updateIntegralNew(){
		$integralNewInsert = addslashes($_POST["integralNewInsert"]); //成为会员初次积分
		if($integralNewInsert){
			$sql = "update ConfigSet
					set CONFIG_INTEGRALINSERT = $integralNewInsert
					where WEIXIN_ID = $this->weixinID";
			return DB::query($sql);
		}
		return true;
	}

	/**
	 * 如果存在有推荐人额外获得积分数的设置，则更新数据库
	 * @return bool
	 */
	private function updateIntegralReferrerNew(){
		$integralReferrerForNewVip = addslashes($_POST["integralReferrerForNewVip"]);//有推荐人的情况下新会员获得额外的积分数
		if($integralReferrerForNewVip){
			$sql = "update ConfigSet
                set CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP = $integralReferrerForNewVip
                where WEIXIN_ID = $this->weixinID";
			return DB::query($sql);
		}
		return true;
	}

	/**
	 * 如果存在推荐本人积分数的设置，则更新数据库
	 * @return bool
	 */
	private function updateReferrer(){
		$integralReferrer = addslashes($_POST["integralReferrer"]);//推荐人获得积分
		if($integralReferrer){
			$sql = "update ConfigSet
                set CONFIG_INTEGRALREFERRER = $integralReferrer
                where WEIXIN_ID = $this->weixinID";
			return DB::query($sql);
		}
		return true;
	}

}