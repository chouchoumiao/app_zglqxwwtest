<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$bigWheelID = addslashes($_POST['bigWheelID']);
$bigWheelID=intval($bigWheelID);

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

//根据openid取得该会员信息
$VipArr = vipInfo($openid,$weixinID);
if(!$VipArr){
    $arr["status"]= "noData";
    $j_arr=json_encode($arr);
    echo $j_arr;
    exit;
}
$sql = "select * from bigWheel_main
        where bigWheel_isDeleted = 0
        AND bigWheel_id = $bigWheelID
        AND WEIXIN_ID = $weixinID";
$bigWheelMainInfo = getlineBySql($sql);
if(!$bigWheelMainInfo){
    $arr["status"]= "noData";
    $j_arr=json_encode($arr);
    echo $j_arr;
    exit;
}

$bigWheel_detailInfo_title = json_decode($bigWheelMainInfo["bigWheel_detailInfo_title"]);
$bigWheel_detailInfo_description = json_decode($bigWheelMainInfo["bigWheel_detailInfo_description"]);
$bigWheel_detailInfo_probability = json_decode($bigWheelMainInfo["bigWheel_detailInfo_probability"]);
$bigWheel_detailInfo_count = json_decode($bigWheelMainInfo["bigWheel_detailInfo_count"]);

$tel = $VipArr[0]['Vip_tel'];
$name = $VipArr[0]['Vip_name'];

//设置SN码
$openidCn = "";
$openidCn = substr($openid,-4)."02";

$NowDate = date("Y-m-d",time());
$NowDateTime  = date("Y-m-d H:i:s",time());

//初始设置使用积分大转盘数
$thisIntegral = 0;

//初始设置该会员的积分数
$arr["nowIntegralData"] = -1;

//判断该会员是否已经中过奖
$sql = "select * from bill
        where Bill_type = '002'
        and Bill_item_id = '$bigWheelID'
        and Bill_Status = 0
        and Bill_openid = '$openid'
        and WEIXIN_ID = $weixinID";
$billInfo = getlineBySql($sql);
if($billInfo){
	$arr["status"]="isWinning";
	$arr["prizelevel"]=$billInfo['Bill_GoodsName'];
	$arr["prizeDescription"] = $billInfo['Bill_GoodsDescription'];
	$arr["SN"]= $billInfo['Bill_SN'];
	$arr["name"] = $name;
	$arr["tel"] = $tel;
	$arr["adress"] = $bigWheelMainInfo['bigWheel_address'];
	$arr["expirationDate"] = $bigWheelMainInfo['bigWheel_expirationDate'];
	$arr["winnedDatetime"] = $billInfo['Bill_insertDate'];
	$arr["message"] = "您已中过奖啦，凭SN码在领奖过期日期前领奖！";
}else{
    $countNum =0;
	//判断该会员是否有进行过大转盘记录，没有记录就把记录数初始化为1，有就取得该记录数
    $sql = "select * from bigWheel_user
            where bigWheel_userIsAllow = 1
            AND bigWheel_userOpenid = '$openid'
            AND WEIXIN_ID = $weixinID";
    $bigWheelUserArr = getlineBySql($sql);
	if(!$bigWheelUserArr){
		$sqlInertWheelUser = "insert into bigWheel_user
                                          (WEIXIN_ID,
                                          bigWheel_userOpenid,
                                          bigWheel_userCount,
                                          bigWheel_userEditDate,
                                          bigWheel_userIsAllow
                                          ) values (
                                          $weixinID,
                                          '$openid',
                                          1,
                                          '$NowDate',
                                          1
                                          )";
		SaeRunSql($sqlInertWheelUser);
	}else{
		//每天大转盘次数设置 20141202
		$bigWheelUserEditTime = $bigWheelUserArr['bigWheel_userEditDate'];
        
        //判断日期是否为当日
		if ((strtotime($NowDate) - strtotime($bigWheelUserEditTime))/86400 >= 1){
			$sqlNewCountSet = "update bigWheel_user
                               set bigWheel_userCount = 0,
                                   bigWheel_userFreeIsOver = 0,
                                   bigWheel_userEditDate = '$NowDate'
                               where bigWheel_userOpenid = '$openid'
                               AND WEIXIN_ID = $weixinID";
			SaeRunSql($sqlNewCountSet);
		}else{
			$countNum = $bigWheelUserArr['bigWheel_userCount'];
        }	
	}

	//将原来记录数的基础上 + 1
	$countNum = $countNum + 1 ;
    
    //未超过免费次数，则进行正常大转盘
    if($countNum < $bigWheelMainInfo['bigWheel_times']){
        $arr["status"]= "freeOk";
        $sqlUpdateWheelUser = "update bigWheel_user
                               set bigWheel_userCount = $countNum,
                                   bigWheel_userEditDate = '$NowDate'
                               where bigWheel_userOpenid = '$openid'
                               AND WEIXIN_ID = $weixinID";
            
        SaeRunSql($sqlUpdateWheelUser);
    }
    //刚好等于免费次数，则将免费次数Flag设置为1，并正常进行大转盘
    if($countNum == $bigWheelMainInfo['bigWheel_times']){
        $sqlUpdateWheelUser = "update bigWheel_user
                               set bigWheel_userCount = $countNum,
                                   bigWheel_userFreeIsOver = 1,
                                   bigWheel_userEditDate = '$NowDate'
                               where bigWheel_userOpenid = '$openid'
                               AND WEIXIN_ID = $weixinID";
        SaeRunSql($sqlUpdateWheelUser);
    }
    if($countNum == $bigWheelMainInfo['bigWheel_times'] + 1){
        $arr["status"]= "max_times";
        $sqlUpdateWheelUser = "update bigWheel_user
                               set bigWheel_userCount = $countNum,
                                   bigWheel_userEditDate = '$NowDate'
                               where bigWheel_userOpenid = '$openid'
                               AND WEIXIN_ID = $weixinID";
        SaeRunSql($sqlUpdateWheelUser);
        
        //显示目前积分
        $arr["nowIntegralData"]= $VipArr[0]['Vip_integral'];
        $j_arr=json_encode($arr);
        echo $j_arr;
        exit;
    }
    //超过免费次数，则扣除对应积分后，正常进行大转盘
    if($countNum > $bigWheelMainInfo['bigWheel_times'] + 1){
        $arr["status"]= "max_times2";
       
        $VipIntegral = $VipArr[0]['Vip_integral'];
        $thisIntegral = $bigWheelMainInfo['bigWheel_Integral'];
        $NewVipIntegral = $VipIntegral - $thisIntegral;
        
        //显示目前积分
        $arr["nowIntegralData"]= $VipIntegral;
        //判断积分是否不够再次进行，不够则返回
        if($NewVipIntegral < 0){
            $arr["status"]= "NoEnoughIntegral";
            $j_arr=json_encode($arr);
            echo $j_arr;
            exit;
        }
        $sqlUpdateIntergral = "update Vip
                               set Vip_integral = $NewVipIntegral,
                                   Vip_edittime = '$NowDateTime'
                               where Vip_openid = '$openid'
                               AND WEIXIN_ID = $weixinID";
        SaeRunSql($sqlUpdateIntergral);

        //追加积分变动时写入记录表中 功能
        $updateIntegralSQL = "insert into integralRecord
                                          (openid,
                                          event,
                                          totalIntegral,
                                          integral,
                                          insertTime
                                          ) VALUE (
                                          '$openid',
                                          '大转盘每次扣除的分数',
                                          $VipIntegral,
                                          $thisIntegral,
                                          '$NowDateTime'
                                          )";
        SaeRunSql($updateIntegralSQL);
        
        $sqlUpdateWheelUser = "update bigWheel_user
                               set bigWheel_userCount = $countNum,
                                   bigWheel_userEditDate = '$NowDate'
                               where bigWheel_userOpenid = '$openid'
                               AND WEIXIN_ID = $weixinID";
        
        SaeRunSql($sqlUpdateWheelUser);
    }
    //进行大转盘抽奖活动
    $isOK = "NO";
    $bigWheel_detailInfo_titleCount = count($bigWheel_detailInfo_title);
    for($i = 0; $i<$bigWheel_detailInfo_titleCount;$i++){
        if(intval($bigWheel_detailInfo_count[$i]) >0){
            //根据取得的随机值，判断是否中奖
            if (rand(1, 100) <= intval($bigWheel_detailInfo_probability[$i])){
                //arr数组是中奖后需返回的数据
                $cnCode = snMaker($openidCn);
                $arr["status"]= "ok";
                $arr["prizelevel"]=$bigWheel_detailInfo_title[$i];
                $arr["prizeDescription"] = $bigWheel_detailInfo_description[$i];
                $arr["SN"]= $cnCode;
                $arr["name"] = $name;
                $arr["tel"] = $tel;
                $arr["adress"] = $bigWheelMainInfo['bigWheel_address'];
                $arr["expirationDate"] = $bigWheelMainInfo['bigWheel_expirationDate'];
                $arr["message"] = "您已中奖，请保存SN兑奖码，凭SN码在领奖过期日期前领奖！";
                $arr["fotDeg"] = $i;
                
                //中奖后新追加Bill交易表
                $bill_GoodsName = $bigWheel_detailInfo_title[$i];
                $bill_GoodsDescription = $bigWheel_detailInfo_description[$i];
                $nowTime = date("Y-m-d H:i:s",time());
                //追加20141201
                $bill_beginDate = $bigWheelMainInfo['bigWheel_beginDate'];
                $bill_endDate = $bigWheelMainInfo['bigWheel_endDate'];
                $bill_expirationDate = $bigWheelMainInfo['bigWheel_expirationDate'];
                
                //将中奖信息写入bill表
                $insertToBillSql = "insert into bill
                                              (WEIXIN_ID,
                                              Bill_type,
                                              Bill_item_id,
                                              Bill_GoodsName,
                                              Bill_GoodsDescription,
                                              Bill_openid,Bill_insertDate,
                                              Bill_goods_beginDate,
                                              Bill_goods_endDate,
                                              Bill_goods_expirationDate,
                                              Bill_integral,Bill_SN,
                                              Bill_Status
                                              ) values (
                                              $weixinID,
                                              '002',
                                              '$bigWheelID',
                                              '$bill_GoodsName',
                                              '$bill_GoodsDescription',
                                              '$openid',
                                              '$nowTime',
                                              '$bill_beginDate',
                                              '$bill_endDate',
                                              '$bill_expirationDate',
                                              $thisIntegral,'$cnCode',
                                              0
                                              )";
                $resultOfInsertBill = SaeRunSql( $insertToBillSql );
                
                //该奖品库存减一
                $bigWheel_detailInfo_count[$i] =  strval(intval($bigWheel_detailInfo_count[$i]) - 1);
                
                $newCount = json_encode($bigWheel_detailInfo_count);
                $sqlUpdateDeatilCount = "update bigWheel_main
                                         set bigWheel_detailInfo_count = '$newCount',
                                             bigWheel_editTime = '$NowDateTime'
                                         where bigWheel_id = $bigWheelID";
                $arr["aaa"] = $sqlUpdateDeatilCount;                      
                SaeRunSql($sqlUpdateDeatilCount);
                
                //中奖后设置flag=YES
                $isOK = "YES";
            }
            //如果已经中奖，则跳出for循环
            if($isOK == "YES"){
                break;
            }
        }	
    }
}
//返回数组
$j_arr=json_encode($arr);
echo $j_arr;


//获得兑换码
function snMaker($pre) { 
	$date = date('Ymd'); 
	$rand = rand(1000,9999); 
	$time = mb_substr(time(), 5, 5, 'utf-8'); 
	$serialNumber = $time.$pre.$date.$rand; 
	return $serialNumber; 
}