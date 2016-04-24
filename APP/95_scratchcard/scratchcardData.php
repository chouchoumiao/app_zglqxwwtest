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

$NowDate = date("Y-m-d",time());
$nowTime  = date("Y-m-d H:i:s",time());

$scratchcard_id = intval(addslashes($_POST['scratchcard_id']));

$sql = "select * from scratchcard_main
        where scratchcard_isDeleted = 0
        AND scratchcard_id = $scratchcard_id
        AND WEIXIN_ID = $weixinID";
$scratchcardMainInfo = getlineBySql($sql);

if(!$scratchcardMainInfo){
    $arr["status"]= "noData";
    echo $j_arr=json_encode($arr);
    exit;
}

//判断是否还有刮奖次数
//取得建言献策的抽奖次数
$adviceSql = "select count(*) from adviceInfo
              where WEIXIN_ID = $weixinID
              AND ADVICE_OPENID = '$openid'
              AND ADVICE_EVENT = 1";
$adviceCount = intval(getVarBySql($adviceSql));

if($adviceCount <= 0){
    $arr["status"]= "NoEnoughIntegral";
    echo json_encode($arr);
    exit;
}

//取得刮刮卡使用次数

$sql = "select scratchcard_userCount from scratchcard_user
        where scratchcard_userIsAllow = 1
        AND scratchcard_userOpenid = '$openid'
        AND scratchcard_id = 159
        AND scratchcard_id = $scratchcard_id";

$times = intval(getVarBySql($sql));

$isFirst = 0;
//初次进行刮刮卡活动的追加记录
if($times <= 0){
    $sql = "insert into scratchcard_user
                        (scratchcard_id,
                        WEIXIN_ID,
                        scratchcard_userOpenid,
                        scratchcard_userCount,
                        scratchcard_userEditDate,
                        scratchcard_userIsAllow
                        ) values (
                        $scratchcard_id,
                        $weixinID,
                        '$openid',
                        1,
                        '$nowTime',
                        1
                        )";
    SaeRunSql($sql);
    $scratchcardedTimes = 0;
    $isFirst = 1;
}else{
    $scratchcardedTimes = $times;
}

$isScratchcardTimes = $adviceCount - $scratchcardedTimes;

if($isScratchcardTimes <= 0){
    $arr["status"]= "NoEnoughIntegral";
    echo json_encode($arr);
    exit;
}
    
$scratchcard_detail_name = json_decode($scratchcardMainInfo["scratchcard_detail_name"]);
$scratchcard_detail_description = json_decode($scratchcardMainInfo["scratchcard_detail_description"]);
$scratchcard_detail_probability = json_decode($scratchcardMainInfo["scratchcard_detail_probability"]);
$scratchcard_detail_count = json_decode($scratchcardMainInfo["scratchcard_detail_count"]);

//初始化奖品对应的分数为0
$thisIntegral = 0;

//设置SN码
$openidCn = "";
$openidCn = substr($openid,-4)."03";

//设置尚未中奖的flag = NO
$isOK = "NO";
$scratchcard_detail_nameCount = count($scratchcard_detail_name);
for($i = 0; $i<$scratchcard_detail_nameCount;$i++){
    if($scratchcard_detail_count[$i] >0){
    //根据取得的随机值，判断是否中奖
        if (rand(1, 100) <= $scratchcard_detail_probability[$i]){
            //arr数组是中奖后需返回的数据
            $cnCode = snMaker($openidCn);
            $arr["status"]= "ok";
            $arr["prizelevel"]=$scratchcard_detail_name[$i];
            $arr["prizedescription"]=$scratchcard_detail_description[$i];
            $arr["SN"]= $cnCode;
            $arr["adress"] = $scratchcardMainInfo['scratchcard_address'];
            $arr["expirationDate"] = $scratchcardMainInfo['scratchcard_expirationDate'];

            //中奖后新追加Bill交易表
            $bill_GoodsName = $scratchcard_detail_name[$i];
            $bill_GoodsDescription = $scratchcard_detail_description[$i];
            $mainMaxTimes = $scratchcardMainInfo['scratchcard_times'];
            //追加20141201
            $bill_beginDate = $scratchcardMainInfo['scratchcard_beginDate'];
            $bill_endDate = $scratchcardMainInfo['scratchcard_endDate'];
            $bill_expirationDate = $scratchcardMainInfo['scratchcard_expirationDate'];

            //将中奖信息写入bill表
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
                                            '003',
                                            '$scratchcard_id',
                                            '$bill_GoodsName',
                                            '$bill_GoodsDescription',
                                            '$openid',
                                            '$nowTime',
                                            '$bill_beginDate',
                                            '$bill_endDate',
                                            '$bill_expirationDate',
                                            $thisIntegral,
                                            '$cnCode',0
                                            )";
            $resultOfInsertBill = SaeRunSql( $insertToBillSql );
            if($resultOfInsertBill == 0){
                //该奖品库存减一
                $scratchcard_detail_count[$i] =  strval(intval($scratchcard_detail_count[$i]) - 1);
                $newCount = json_encode($scratchcard_detail_count);
                $sqlUpdateDeatilCount = "update scratchcard_main
                                         set scratchcard_detail_count = '$newCount',
                                             scratchcard_editTime = '$nowTime'
                                         where scratchcard_id = $scratchcard_id";
                SaeRunSql($sqlUpdateDeatilCount);
                //中奖后设置flag=YES
                $isOK = "YES";
            }
        }
        //如果已经中奖，则跳出for循环
        if($isOK == "YES"){
            break;
        }
    }
}

if($isFirst == 0){
    //抽奖结束后 刮奖次数加1
    $sql = "update scratchcard_user
              set scratchcard_userCount = scratchcard_userCount + 1,
                  scratchcard_userEditDate = '$nowTime'
              where scratchcard_userIsAllow = 1
              AND scratchcard_userOpenid = '$openid'
              AND WEIXIN_ID = $weixinID
              AND scratchcard_id = $scratchcard_id";

    SaeRunSql($sql);
    
}

//返回数组
echo json_encode($arr);

//获得兑换码
function snMaker($pre) { 
	$date = date('Ymd'); 
	$rand = rand(1000,9999); 
	$time = mb_substr(time(), 5, 5, 'utf-8'); 
	$serialNumber = $time.$pre.$date.$rand; 
	return $serialNumber; 
}