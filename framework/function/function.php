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
	
	function ORG($path, $name, $params=array()){// path ��·��  name�ǵ��������� params �Ǹ����ʼ����ʱ����Ҫָ������ֵ�����ԣ���ʽΪ array(������=>����ֵ, ������2=>����ֵ2����)
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
	//ȡ��config�����ݲ����뻺��
	$sql = "select CONFIG_INTEGRALINSERT,
				   CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
				   CONFIG_INTEGRALREFERRER,
				   CONFIG_INTEGRALSETDAILY,
				   CONFIG_DAILYPLUS,
				   CONFIG_VIP_NAME from ConfigSet
			where WEIXIN_ID = $weixinID";
	$configLineData = DB::findOne($sql);

	//���ȡ�ò����� ���ʼ��Ϊ 0,0,0,0,0,'����'
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
							'����'
							)";
		$isOK = DB::query($sql);
		if(!$isOK){
			return array(
				"CONFIG_INTEGRALINSER" =>0,
				"CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP"=>0,
				"CONFIG_INTEGRALREFERRER"=>0,
				"CONFIG_INTEGRALSETDAILY"=>0,
				"CONFIG_DAILYPLUS"=>0,
				"CONFIG_VIP_NAME"=>'����'
			);
		}else{
			return array();
		}
	}
	return $configLineData;
}