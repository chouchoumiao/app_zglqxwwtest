<?php
	function C($name, $method){
		require_once('/libs/controller/'.$name.'Controller.class.php');
		eval('$obj = new '.$name.'Controller();$obj->'.$method.'();');
	}

	function M($name){
		require_once('/libs/Model/'.$name.'Model.class.php');
		//$testModel = new testModel();
		eval('$obj = new '.$name.'Model();');
		return $obj;
	}
	
	function V($name){
		require_once('/libs/View/'.$name.'View.class.php');
		//$testView = new testView();
		eval('$obj = new '.$name.'View();');
		return $obj;
	}
	
	function ORG($path, $name, $params=array()){// path 是路径  name是第三方类名 params 是该类初始化的时候需要指定、赋值的属性，格式为 array(属性名=>属性值, 属性名2=>属性值2……)
		require_once('libs/ORG/'.$path.$name.'.class.php');
		//eval('$obj = new '.$name.'();');
		$obj = new $name();
		if(!empty($params)){
		foreach($params as $key=>$value){
				//eval('$obj->'.$key.' = \''.$value.'\';');
				$obj->$key = $value;
			}
		}
		return $obj;
	}

	function daddslashes($str){
		return (!get_magic_quotes_gpc())?addslashes($str):$str;
	}

function getConfigWithMMC($weixinID){
	//取得config的数据并存入缓存
	$sql = "select CONFIG_INTEGRALINSERT,
				   CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
				   CONFIG_INTEGRALREFERRER,
				   CONFIG_INTEGRALSETDAILY,
				   CONFIG_DAILYPLUS,
				   CONFIG_VIP_NAME from ConfigSet
			where WEIXIN_ID = $weixinID";
	$configLineData = DB::findOne($sql);

	//如果取得不存在 则初始化为 0,0,0,0,0,'积分'
	if(!$configLineData){
		$sql = "insert into ConfigSet
							(WEIXIN_ID,
							CONFIG_INTEGRALINSERT,
							CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
							CONFIG_INTEGRALREFERRER,
							CONFIG_INTEGRALSETDAILY,
							CONFIG_DAILYPLUS,
							CONFIG_VIP_NAME
							) values (
							$weixinID,
							0,
							0,
							0,
							0,
							0,
							'积分'
							)";
		$isOK = DB::query($sql);
		if(!$isOK){
			return array(
				"CONFIG_INTEGRALINSER" =>0,
				"CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP"=>0,
				"CONFIG_INTEGRALREFERRER"=>0,
				"CONFIG_INTEGRALSETDAILY"=>0,
				"CONFIG_DAILYPLUS"=>0,
				"CONFIG_VIP_NAME"=>'积分'
			);
		}else{
			return array();
		}
	}
	return $configLineData;
}