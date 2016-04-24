<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$weixinID = addslashes($_GET['weixinID']);
$openid = addslashes($_GET['openid']);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$referrerID = addslashes($_POST['referrerID']);
$thisVipIntegral = addslashes($_POST['thisVipIntegral']);

$sql = "select * from Vip
        where Vip_openid = '$openid'
        and WEIXIN_ID = $weixinID
        and Vip_isDeleted = 0" ;
$thisVipInfo = getlineBySql($sql);

if($thisVipInfo['Vip_comment'] == "referrer"){
    $arr['success'] = -1;
    $arr['msg'] = "你已经补登过推荐人啦！";
    echo json_encode($arr);
    exit;
}

//取得初始化设置表中内容
$config =  getConfigWithMMC($weixinID);
//尚未设置config的情况下 全初始化为0  wujiayu 测试发现
if($config == '' || empty($config)){
    $newVipFirstIntegral = 0;
    $plusIntegral = 0;
    $plusIntegralForNewVip = 0;
    $weixinName = '积分';
}else{
    //成为新会员获得积分数
    $newVipFirstIntegral = $config['CONFIG_INTEGRALINSERT'];
    //存在推荐人的时候，新会员注册成功时，该推荐人可获得积分数
    $plusIntegral = $config['CONFIG_INTEGRALREFERRER'];
    //存在推荐人的时候，新会员注册成功时，新会员也可以获得额外积分数
    $plusIntegralForNewVip = $config['CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP'];
    $weixinName = $config['CONFIG_VIP_NAME'];
}

$nowTime = date("Y-m-d H:i:s",time());

//检查是否存在该推荐人的ID，存在：设置存在人的新积分，并且设置新会员的新积分，并绑定会员
$sql = "Select * FROM Vip
        WHERE Vip_id='$referrerID'
        AND Vip_isDeleted = 0
        AND WEIXIN_ID = $weixinID";
$referrerInfo = getlineBySql($sql);

//不存在推荐人的时候，需要重新输入
if(!$referrerInfo){
    $arr['success'] = -1;
    $arr['msg'] = "不存在该推荐人，请确认！";
    echo json_encode($arr);
    exit;
    
}else{
    //取得推荐人的原始积分数
    $oldVipIntegral = $referrerInfo['Vip_integral'];
    
    //与推荐积分数累加后生成新的积分数，并写入Vip数据表中
    $newVipIntegral = $oldVipIntegral + $plusIntegral;

    $sql = "update Vip
            set Vip_integral = $newVipIntegral,
                Vip_edittime= '$nowTime'
            where Vip_id = '$referrerID'
            AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($sql);

    //追加积分变动时写入记录表中 功能
    $updateIntegralSQL = "insert into integralRecord
                                      (openid,
                                      event,
                                      totalIntegral,
                                      integral,
                                      insertTime
                                      ) VALUE (
                                      '$referrerID',
                                      '补登时推荐人加$weixinName',
                                      $oldVipIntegral,
                                      $plusIntegral,
                                      '$nowTime'
                                      )";
    SaeRunSql($updateIntegralSQL);

    if($errorNo == 0){
        
        //存在推荐人的时候，新积分数 = 新会员初始积分数+额外积分数
        $thisNewVipIntegral = $thisVipIntegral + $plusIntegralForNewVip;
        
        $sql = "update Vip
                set Vip_integral = $thisNewVipIntegral,
                    Vip_edittime= '$nowTime',
                    Vip_comment = 'referrer'
                where Vip_openid = '$openid'
                AND WEIXIN_ID = $weixinID";

        $errorNo = SaeRunSql($sql);
        //追加积分变动时写入记录表中 功能
        $updateIntegralSQL = "insert into integralRecord
                                          (openid,
                                          event,
                                          totalIntegral,
                                          integral,
                                          insertTime
                                          ) VALUE (
                                          '$openid',
                                          '补登时会员加$weixinName',
                                          $thisVipIntegral,
                                          $plusIntegralForNewVip,
                                          '$nowTime'
                                          )";
        SaeRunSql($updateIntegralSQL);
        
    }
}

if($errorNo == 0){

    $arr['success'] = 0;

    //需追加 20151216
    $thisVip_name = $thisVipInfo['Vip_name'];
    $thisVip_sex = $thisVipInfo['Vip_sex'];
    $thisVip_tel = $thisVipInfo['Vip_tel'];

    //根据IP地址取得城市名称 20151215
    $city = getCity();
    $ipE_err = -1; //初始化为-1，只有存在推荐人，而且追加活动表后，取得状态
    //判断是否是台州地区和路桥发布公众号，满足条件写入活动表
    //if(strstr($city,'浙江') && $weixinID == 69){
    if(strstr($city,'浙江')){
        $iphone_sql = "insert into iphoneEvent
                                  (WEIXIN_ID,
                                  ipE_name,
                                  ipE_sex,
                                  ipE_tel,
                                  ipE_openid,
                                  ipE_referee_vipID,
                                  ipE_insertTime
                                  ) VALUE (
                                  $weixinID,
                                  '$thisVip_name',
                                  $thisVip_sex,
                                  '$thisVip_tel',
                                  '$openid',
                                  $referrerID,
                                  '$nowTime'
                                  )";
        $ipE_err = SaeRunSql($iphone_sql);
    }

    if($ipE_err == 0){
        $arr['msg'] = '提交成功！'.PHP_EOL.' 您追加'.$weixinName.'：'.$plusIntegralForNewVip.PHP_EOL.'推荐人追加：'.$plusIntegral.PHP_EOL.'推荐人同时获得一个印章';
    }else{
        $arr['msg'] = '提交成功！'.PHP_EOL.' 您追加'.$weixinName.'：'.$plusIntegralForNewVip.PHP_EOL.'推荐人追加：'.$plusIntegral;
    }
//操作数据库错误时    
}else{
    $arr['success'] = -1;
    $arr['msg'] = "提交失败！";
}	
echo json_encode($arr);
exit;
