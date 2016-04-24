<?php
session_start();
$user = $_SESSION['user'];

if(isset($user)){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

	$weixinID = $_SESSION['weixinID'];
    //取得Config信息
    $config =  getConfigWithMMC($weixinID);
    //判断基础信息是否取得成功
    if($config == '' || empty($config)){
        $arr['success'] = "NG";
        $arr['msg'] = "取得配置信息失败，请确认！";
        echo json_encode($arr);
        exit;
    }

    $thisIntegral = addslashes($_POST["thisIntegral"]);
    $dailyCodeIntegral = addslashes($_POST["dailyCodeIntegral"]);

    $sql = "update ConfigSet
            set CONFIG_INTEGRALSETDAILY  = $thisIntegral,
                CONFIG_DAILYPLUS =  $dailyCodeIntegral
            where WEIXIN_ID = $weixinID";
	$errono = SaeRunSql($sql);
    if($errono == 0){
        $arr['success'] = "OK";
        $arr['msg'] = "设置成功！";

        //取得最新数据并更新缓存内容
        $sql = "select CONFIG_INTEGRALINSERT,
                       CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
                       CONFIG_INTEGRALREFERRER,
                       CONFIG_INTEGRALSETDAILY,
                       CONFIG_DAILYPLUS,
                       CONFIG_VIP_NAME
                from ConfigSet
                where WEIXIN_ID = $weixinID";
        $configLineData = getLineBySql($sql);
        $mmc = memcache_init();
        memcache_set($mmc,$weixinID.'config',$configLineData,0,6000);
    }else{
        $arr['success'] = "NG";
        $arr['msg'] = "设置失败！";
    }
}else{
    $arr['success'] = "NG";
	$arr['msg'] = "session出错，请重新登录！";
}

echo json_encode($arr);