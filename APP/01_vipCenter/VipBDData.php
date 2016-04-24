<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET['openid']);
$weixinID = addslashes($_GET['weixinID']);
//黑名单过滤 20151105
if($openid == 'oAkRMuLslB9g7EtnzMlPnDLwsRxs' || $openid == 'oY_D7s1ClsSeSwYxTY6E2cGbobro' || $openid == 'oAkRMuPIJjzIwIwkdE_8Tve73wJ4'){
    $arr['success'] = -1;
    $arr['msg'] = "绑定失败！";
    echo json_encode($arr);
    exit;
}

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$sql = "Select * FROM Vip
        WHERE Vip_openid='$openid'
        AND Vip_isDeleted = 0
        AND WEIXIN_ID = $weixinID";
$vipInfo = getlineBySql($sql);

if($vipInfo){
    $arr['success'] = -1;
    $arr['msg'] = "您已经是会员了！";
    echo json_encode($arr);
    exit;
}

//取得Config信息
$config =  getConfigWithMMC($weixinID);
//尚未设置config的情况下 全初始化为0  wujiayu 测试发现
if($config == '' || empty($config)){
    $thisVip_integral = 0;
    $newVipFirstIntegral = 0;
    $plusIntegral = 0;
    $plusIntegralForNewVip = 0;
    $weixinName = '积分';
}else{
    $thisVip_integral = $config['CONFIG_INTEGRALINSERT'];
    //成为新会员获得积分数
    $newVipFirstIntegral = $config['CONFIG_INTEGRALINSERT'];
    //存在推荐人的时候，新会员注册成功时，该推荐人可获得积分数
    $plusIntegral = $config['CONFIG_INTEGRALREFERRER'];
    //存在推荐人的时候，新会员注册成功时，新会员也可以获得额外积分数
    $plusIntegralForNewVip = $config['CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP'];
    $weixinName = $config['CONFIG_VIP_NAME'];
}

$thisVip_name = addslashes($_POST['name']);
$thisVip_tel = addslashes($_POST['tel']);
$thisVip_referrer = addslashes($_POST['referrer']);
$thisVip_sex = addslashes($_POST['sex']);
$nowTime = date("Y-m-d H:i:s",time());

$thisVip_isDeleted = 0;
$thisVip_isSubscribed = 1;
$thisVip_comment = "";
$isInsertOK = "";

$msg = "";

//判断新会员输入的手机号是否VIp数据表中已经存在，存在则提示错误
$sql = "Select * FROM Vip
        WHERE Vip_tel='$thisVip_tel'
        AND Vip_isDeleted = 0
        AND WEIXIN_ID = $weixinID";
$NewVipInfoByTel = getlineBySql($sql);

if($NewVipInfoByTel){
    $arr['success'] = -1;
    $arr['msg'] = "您的联系手机已经被使用，请返回确认！";
    echo json_encode($arr);
    exit;
}

if($thisVip_referrer == ""){
    $sql = "insert into Vip
                (WEIXIN_ID,
                Vip_name,
                Vip_sex,
                Vip_tel,
                Vip_openid,
                Vip_integral,
                Vip_createtime,
                Vip_edittime,
                Vip_isDeleted,
                Vip_isSubscribed,
                Vip_comment
                ) values (
                $weixinID,
                '$thisVip_name',
                $thisVip_sex,
                '$thisVip_tel',
                '$openid',
                $thisVip_integral,
                '$nowTime',
                '$nowTime',
                $thisVip_isDeleted,
                $thisVip_isSubscribed,
                '$thisVip_comment'
                )";
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
                                      '会员绑定无推荐人',
                                      $thisVip_integral,
                                      $thisVip_integral,
                                      '$nowTime'
                                      )";
    SaeRunSql($updateIntegralSQL);
}else{
    //检查是否存在该推荐人的ID，存在：设置存在人的新积分，并且设置新会员的新积分，并绑定会员
    $sql = "Select * FROM Vip
            WHERE Vip_id='$thisVip_referrer'
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
                where Vip_id = '$thisVip_referrer'
                AND WEIXIN_ID = $weixinID";
        $errorNo = SaeRunSql($sql);

        //追加积分变动时写入记录表中 功能
        $updateIntegralSQL = "insert into integralRecord (openid,event,totalIntegral,integral,insertTime) VALUE ('$thisVip_referrer','会员绑定推荐人加$weixinName',$oldVipIntegral,$plusIntegral,'$nowTime')";
        SaeRunSql($updateIntegralSQL);

        if($errorNo == 0){
            
            //存在推荐人的时候，新积分数 = 新会员初始积分数+额外积分数
            $thisVipIntegral = $thisVip_integral + $plusIntegralForNewVip;
            
            $sql = "insert into Vip
                              (WEIXIN_ID,
                              Vip_name,
                              Vip_sex,
                              Vip_tel,
                              Vip_openid,
                              Vip_integral,
                              Vip_createtime,
                              Vip_edittime,
                              Vip_isDeleted,
                              Vip_isSubscribed,
                              Vip_comment
                              ) values (
                              $weixinID,
                              '$thisVip_name',
                              $thisVip_sex,
                              '$thisVip_tel',
                              '$openid',
                              $thisVipIntegral,
                              '$nowTime',
                              '$nowTime',
                              $thisVip_isDeleted,
                              $thisVip_isSubscribed,
                              'referrer'
                              )";
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
                                            '会员绑定存在推荐人会员加$weixinName',
                                            $thisVipIntegral,
                                            $thisVipIntegral,
                                            '$nowTime'
                                            )";
            SaeRunSql($updateIntegralSQL);

            //需追加 20151216
            //根据IP地址取得城市名称 20151215
            $city = getCity();
            $ipE_err = -1; //初始化为-1，只有存在推荐人，而且追加活动表后，取得状态
            //判断是否是台州地区和路桥发布公众号，满足条件写入活动表
            //if(strstr($city,'台州') && $weixinID == 69){
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
                                          $thisVip_referrer,
                                          '$nowTime'
                                          )";
                $ipE_err = SaeRunSql($iphone_sql);
            }

            if($ipE_err == 0){
                $msg = "OKForReferrerANDIpE";//追加推荐得iphone活动的功能 20151215
            }else{
                $msg = "OKForReferrer";
            }
        }
    }
} 
if($errorNo == 0){
    $arr['success'] = 0;
    //存在推荐人
    if($msg == "OKForReferrer"){
        $arr['msg'] = "亲，您已经成功绑定了会员！</br>
                        初次绑定获得$weixinName".$newVipFirstIntegral."</br>
                        存在推荐人额外获得$weixinName:".$plusIntegralForNewVip."</br>
                        推荐人也同时获得额外$weixinName".$plusIntegral."</br>";
    //追加推荐得iphone活动的功能 20151215
    }else if($msg == 'OKForReferrerANDIpE'){
        $arr['msg'] = "亲，您已经成功绑定了会员！</br>
                        初次绑定获得$weixinName".$newVipFirstIntegral."</br>
                        存在推荐人额外获得$weixinName:".$plusIntegralForNewVip."</br>
                        推荐人也同时获得额外$weixinName".$plusIntegral."</br>
                        推荐人还获得一个印章，积攒印章可得大奖"."</br>";
    }else{
        $arr['msg'] = " 亲，您已经成功绑定了会员！</br>
                        初次绑定获得$weixinName".$newVipFirstIntegral;
    }
//操作数据库错误时    
}else{
    $arr['success'] = -1;
    $arr['msg'] = "绑定会员时出错啦！请重新绑定！";
}	
echo json_encode($arr);
exit;
