<?php
/**
 * 设置基础信息
 */
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
    $weixinName = $config['CONFIG_VIP_NAME'];
    $integralNewInsert = addslashes($_POST["integralNewInsert"]); //成为会员初次积分
    if($integralNewInsert){
        $sql = "update ConfigSet
                set CONFIG_INTEGRALINSERT = $integralNewInsert
                where WEIXIN_ID = $weixinID";
        $errono = SaeRunSql($sql);
        if ($errono != 0) {
            $arr['success'] = "NG";
            $arr['msg'] = "更新【成为会员初次".$weixinName."数】失败！";
            echo json_encode($arr);
            exit;
        }
    }

    $integralReferrerForNewVip = addslashes($_POST["integralReferrerForNewVip"]);//有推荐人的情况下新会员获得额外的积分数
    if($integralReferrerForNewVip){
        $sql = "update ConfigSet
                set CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP = $integralReferrerForNewVip
                where WEIXIN_ID = $weixinID";
        $errono = SaeRunSql($sql);
        if($errono != 0){
            $arr['success'] = "NG";
            $arr['msg'] = "更新【有推荐人的情况下新会员获得额外的".$weixinName."数】失败！";
            echo json_encode($arr);	
            exit;
        }
    }

    $integralReferrer = addslashes($_POST["integralReferrer"]);//推荐人获得积分
    if($integralReferrer){
        $sql = "update ConfigSet
                set CONFIG_INTEGRALREFERRER = $integralReferrer
                where WEIXIN_ID = $weixinID";
        $errono = SaeRunSql($sql);
        if($errono != 0){
            $arr['success'] = "NG";
            $arr['msg'] = "更新【推荐人获得".$weixinName."】失败！";
            echo json_encode($arr);	
            exit;
        }
    }

    $arr['success'] = "OK";
    $arr['msg'] = "更新成功！";

    //取得最新数据并更新缓存内容
    $sql = "select CONFIG_INTEGRALINSERT,
                   CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
                   CONFIG_INTEGRALREFERRER,
                   CONFIG_INTEGRALSETDAILY,
                   CONFIG_DAILYPLUS,
                   CONFIG_VIP_NAME from ConfigSet
            where WEIXIN_ID = $weixinID";
    $configLineData = getLineBySql($sql);
    $mmc = memcache_init();
    memcache_set($mmc,$weixinID.'config',$configLineData,0,6000);

}else{
    $arr['success'] = "NG";
	$arr['msg'] = "session出错，请重新登录！";
}

echo json_encode($arr);