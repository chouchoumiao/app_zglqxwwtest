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

//取得本会员的印章数

$thisVipID = $vipInfoArr[0]['Vip_id'];
$sql = "select count(*) from iphoneEvent where ipE_referee_vipID = $thisVipID";
$beforeBill = getVarBySql($sql);

//从bill表中获取已经中奖的印章数
$sqlFromBill = "select SUM(Bill_integral) from bill
                where Bill_openid = '$openid'
                AND WEIXIN_ID = $weixinID
                AND bill_type = '004'";
$afterBill = getVarBySql($sqlFromBill);

$flowerCount = $beforeBill - $afterBill;

//接收传过来的商品ID，以便进行查询
$thisIntegralID = addslashes($_POST["fromIntegralID"]);
$sql = "select * from flowerCity_config
        where flowerCity_isDeleted = 0
        AND id = $thisIntegralID
        AND WEIXIN_ID = $weixinID";
$flowerCityInfoByID = getlineBySql($sql);

//取得库存 如果库存<=0 则不能进行 20151222
$thisStockCount = $flowerCityInfoByID["flowerCity_stockCount"];
if($thisStockCount <= 0){
    $arr['success'] = -1;
    $arr['msg'] = '库存不足，无法兑换！';
    echo json_encode($arr);
    exit;
}

//追加功能 如果同一商品只能兑换一次
$sql = "select count(*) from bill
        where Bill_item_id = $thisIntegralID
        AND Bill_openid = '$openid'
        AND WEIXIN_ID = $weixinID
        AND bill_type = '004'";
$billTimes = getVarBySql($sql);
if($billTimes >= 1){
    $arr['success'] = -1;
    $arr['msg'] = '您已经领取过该商品，不能再次领取！';
    echo json_encode($arr);
    exit;
}

//取得该商品的印章
$thisFlowerNum = $flowerCityInfoByID["flowerCity_flowerNum"];
$newVipFlowerNum = $flowerCount - $thisFlowerNum;
//当该会员印章总量大于商品印章时，可以进行兑换
if($newVipFlowerNum>=0){

    //取得该商品库存数
    $thisIntegralCtiyStock = $flowerCityInfoByID["flowerCity_stockCount"];
    $newthisIntegralCtiyStock = $thisIntegralCtiyStock - 1;

    //将该商品的库存减一
    $updateIntegralSql = "update flowerCity_config
                          set flowerCity_stockCount = $newthisIntegralCtiyStock,
                          flowerCity_editTime = '$this_edittime'
                          where id = $thisIntegralID
                          AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateIntegralSql);
    if($errorNo == 0){
        //生成兑换码
        $cnCode = snMaker($openidCn);
        $thsIntegralCtiyName= $flowerCityInfoByID["flowerCity_name"];
        $thsIntegralCtiyBeginDate= $flowerCityInfoByID["flowerCity_fromDate"];
        $thsIntegralCtiyEndDate= $flowerCityInfoByID["flowerCity_endDate"];
        $thsIntegralCtiyExpirationDate= $flowerCityInfoByID["flowerCity_expirationDate"];
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
                                      Bill_integral,Bill_SN,Bill_Status) values ($weixinID,'004',$thisIntegralID,'$thsIntegralCtiyName',
                                      '$thsIntegralCtiyName','$openid','$this_edittime','$thsIntegralCtiyBeginDate',
                                      '$thsIntegralCtiyEndDate','$thsIntegralCtiyExpirationDate',$thisFlowerNum,'$cnCode',0)";
        $errorNo = SaeRunSql($insertToBillSql);
        if($errorNo == 0){

            //追加印章变动时写入记录表中 功能
            $updateIntegralSQL = "insert into integralRecord (openid,event,totalIntegral,integral,
                          insertTime) VALUE ('$openid','印章兑换记录(不减印章）',$thsVipFlowerNum,
                          $thisFlowerNum,'$this_edittime')";
            SaeRunSql($updateIntegralSQL);

            $arr['success'] = 1;
            $arr['msg'] = "兑换成功,兑换码:"."\n".$cnCode."\n"."请记下兑换码或者在会员中心画面查询".$flowerCount;
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
    $arr['msg'] = '您的印章数不足兑换该商品';
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
