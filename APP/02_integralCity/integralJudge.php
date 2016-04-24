<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$openidCn = "";

$openidCn = substr($openid,-4)."01";
$this_edittime = date("Y-m-d H:i:s",time());
$nowDate = date("Y-m-d",time());  //20151224

//取得该会员的信息
$vipInfoArr = vipInfo($openid,$weixinID);
if(!$vipInfoArr){
    $arr['success'] = -1;
    $arr['msg'] = '取得会员信息失败';
    echo json_encode($arr);
    exit;
}

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

//取得会员积分
$thsVipIntegralNum = $vipInfoArr[0]["Vip_integral"];
//取得该会员的公众号ID

//接收传过来的商品ID，以便进行查询
$thisIntegralID = addslashes($_POST["fromIntegralID"]);
$sql = "select * from integralCity_config
        where integralCity_isDeleted = 0
        AND integralCity_id = $thisIntegralID
        AND WEIXIN_ID = $weixinID";
$IntegralCtiyInfoByID = getlineBySql($sql);
//取得该商品的积分
$thisIntegralNum = $IntegralCtiyInfoByID["integralCity_integralNum"];

//取得库存 如果库存<=0 则不能进行 20151222
$thisStockCount = $IntegralCtiyInfoByID["integralCity_stockCount"];
if($thisStockCount <= 0){
    $arr['success'] = -1;
    $arr['msg'] = '库存不足，无法兑换！';
    echo json_encode($arr);
    exit;
}

$newVipIntegralNum = $thsVipIntegralNum - $thisIntegralNum;


//追加功能 如果同一商品今天已经兑换 2次则不能在进行兑换
$sql = "select count(*) from bill
        where Bill_item_id = $thisIntegralID
        AND Bill_openid = '$openid'
        AND WEIXIN_ID = $weixinID
        AND DATE_FORMAT(Bill_insertDate, '%Y-%m-%d' )= '$nowDate'";
$billTimes = getVarBySql($sql);
if($billTimes >= 2){
    $arr['success'] = -1;
    $arr['msg'] = '同一商品每天只能兑换两次';
    echo json_encode($arr);
    exit;
}

//当该会员积分总量大于商品积分时，可以进行兑换
if($newVipIntegralNum>=0){
    
    $updateVipSql = "update Vip
                     set Vip_integral = $newVipIntegralNum
                     where Vip_openid = '$openid'
                     AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateVipSql);

    //追加积分变动时写入记录表中 功能
    $updateIntegralSQL = "insert into integralRecord
                                      (openid,
                                      event,
                                      totalIntegral,
                                      integral,
                                      insertTime
                                      ) VALUE (
                                      '$openid',
                                      $weixinName.'兑换后减少的'.$weixinName,
                                      $thsVipIntegralNum,
                                      $thisIntegralNum,
                                      '$this_edittime'
                                      )";
    SaeRunSql($updateIntegralSQL);
    
    //当更新成功后，将商品的库存更新
    if($errorNo == 0){
        //取得该商品库存数
        $thisIntegralCtiyStock = $IntegralCtiyInfoByID["integralCity_stockCount"];
        $newthisIntegralCtiyStock = $thisIntegralCtiyStock - 1;
        
        //将该商品的库存减一
        $updateIntegralSql = "update integralCity_config
                              set integralCity_stockCount = $newthisIntegralCtiyStock,
                                  integralCity_editTime = '$this_edittime'
                              where integralCity_id = $thisIntegralID
                              AND WEIXIN_ID = $weixinID";
        $errorNo = SaeRunSql($updateIntegralSql);
        if($errorNo == 0){
            //生成兑换码
            $cnCode = snMaker($openidCn);
            $thsIntegralCtiyName= $IntegralCtiyInfoByID["integralCity_name"];
            $thsIntegralCtiyBeginDate= $IntegralCtiyInfoByID["integralCity_fromDate"];
            $thsIntegralCtiyEndDate= $IntegralCtiyInfoByID["integralCity_endDate"];
            $thsIntegralCtiyExpirationDate= $IntegralCtiyInfoByID["integralCity_expirationDate"];
            //将兑换记录插入Bill表
            $insertToBillSql = "insert into bill
                                          (WEIXIN_ID,
                                          Bill_type,
                                          Bill_item_id,
                                          Bill_GoodsName,
                                          Bill_GoodsDescription,
                                          Bill_openid,
                                          Bill_insertDate,
                                          Bill_goods_beginDate,
                                          Bill_goods_endDate,
                                          Bill_goods_expirationDate,
                                          Bill_integral,
                                          Bill_SN,
                                          Bill_Status
                                          ) values (
                                          $weixinID,
                                          '001',
                                          $thisIntegralID,
                                          '$thsIntegralCtiyName',
                                          '$thsIntegralCtiyName',
                                          '$openid',
                                          '$this_edittime',
                                          '$thsIntegralCtiyBeginDate',
                                          '$thsIntegralCtiyEndDate',
                                          '$thsIntegralCtiyExpirationDate',
                                           $thisIntegralNum,
                                          '$cnCode',0)";
            $errorNo = SaeRunSql($insertToBillSql);
            if($errorNo == 0){
                $arr['success'] = 1;
                $arr['msg'] = "兑换成功,兑换码:"."\n".$cnCode."\n"."请记下兑换码或者在会员中心画面查询";
                echo json_encode($arr);
                exit;
            }else{
                $arr['success'] = -1;
                $arr['msg'] = '生成交易记录时失败，无法兑换';
                echo json_encode($arr);
                exit;
            }
        }else{
            $arr['success'] = -1;
            $arr['msg'] = '更新库存失败，无法兑换！';
            echo json_encode($arr);
            exit;
        }			
    }else{
        $arr['success'] = -1;
        $arr['msg'] = '更新'.$weixinName.'失败，无法兑换！';
        echo json_encode($arr);
        exit;
    }	
}else{
    $arr['success'] = -1;
    $arr['msg'] = '您的'.$weixinName.'不足兑换该商品';
    echo json_encode($arr);
    exit;
}

//获得兑换码
function snMaker($pre) { 
	$date = date('Ymd'); 
	$rand = rand(1000,9999); 
	$time = mb_substr(time(), 5, 5, 'utf-8'); 
	$serialNumber = $time.$pre.$date.$rand; 
	return $serialNumber; 
}
