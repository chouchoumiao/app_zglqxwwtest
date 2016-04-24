<?php
header("Content-type:text/html;charset=utf-8");

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET['openid']);
$weixinID = addslashes($_GET['weixinID']); //weixin

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");
$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

$integral = addslashes($_GET['integral']);
$signidText = addslashes($_GET['signidText']);

$nowDate = date("Y-m-d",time());
$sql = "select Vip_integral,
               Vip_isSignedDayTime
        from Vip
        where Vip_openid = '$openid'
        AND WEIXIN_ID = $weixinID
        AND Vip_isDeleted = 0";
$vipInfo = getlineBySql($sql);

//取得该会员最新签到日期和积分数
$signedDay = $vipInfo['Vip_isSignedDayTime'];
$integral = intval($vipInfo['Vip_integral']);

if($signedDay == $nowDate){
    echo "<script>alert('你已经签到过啦!');location='VipCennter.php?openid=$openid&weixinID=$weixinID&integral=$integral';</script>";
    exit;
}

if($signidText){
    
    //220150827 去掉每天一个签到码的限制
    //$sql = "select * from vipDailySet where WEIXIN_ID ='$weixinID' and editDate = '$nowDate' and dailyCode = '$signidText'";
    $sql = "select * from vipDailySet
            where WEIXIN_ID ='$weixinID'
            and dailyCode = '$signidText'
            and flag = 1";
    $data = getlineBySql($sql);
    if(!$data['dailyCode']){
        echo "<script>alert('签到码错误，请重新输入');location='vipdaliy.php?openid=$openid&weixinID=$weixinID&integral=$integral';</script>";
        exit;
    }
}

//取得Config信息
$config =  getConfigWithMMC($weixinID);
if($config == '' || empty($config)){
    $plusIntegral = 0;
}else{
    if($data){
        $plusIntegral = $config['CONFIG_DAILYPLUS'];
    }else{
        $plusIntegral = $config['CONFIG_INTEGRALSETDAILY'];
    }
}

$newIntegral = $integral + $plusIntegral;
$lastEditTime  = date("Y-m-d H:i:s",time());
$sql = "update Vip
        set Vip_integral = $newIntegral,
            Vip_isSignedDayTime = '$nowDate',
            Vip_edittime = '$lastEditTime'
        where Vip_openid = '$openid'
        AND WEIXIN_ID = $weixinID";

//追加积分变动时写入记录表中 功能
$updateIntegralSQL = "insert into integralRecord
                                  (openid,
                                  event,
                                  totalIntegral,
                                  integral,
                                  insertTime
                                  ) VALUE (
                                  '$openid',
                                  '签到',
                                  $integral,
                                  $plusIntegral,
                                  '$lastEditTime'
                                  )";
SaeRunSql($updateIntegralSQL);

$errono = SaeRunSql($sql);

if($errono == 0){
    echo "<script>alert('签到成功，$weixinName 加: $plusIntegral');location='VipCennter.php?openid=$openid&weixinID=$weixinID&integral=$integral';</script>";
}else{
    echo "<script>alert('签到失败!');location='VipCennter.php?openid=$openid&weixinID=$weixinID&integral=$integral';</script>";
}
exit;