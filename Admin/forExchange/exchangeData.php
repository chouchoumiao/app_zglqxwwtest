<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$action = addslashes($_GET['action']);

$weixinID = addslashes($_GET['weixinID']);

if($action == "searchBill"){
    
    //取得set页面传递过来的数据
    $snCode = addslashes($_POST["SNLast6"]);
    
    //根据SN取得取得该会员的中奖信息
    $billDataArr = getWinningInfoBySNCode($snCode,$weixinID);
    
    //取得中奖信息
    $Bill_type = $billDataArr['Bill_type'];
    
    if($Bill_type == "001"){
        $Bill_type = "积分商城";
    }else if($Bill_type == "002"){
        $Bill_type = "大转盘";
    }else if($Bill_type == "003"){
        $Bill_type = "刮刮卡";
    }else if($Bill_type == "004"){
        $Bill_type = "印章";
    }else{
        //无
    }
    
    $Bill_GoodsName = $billDataArr['Bill_GoodsName'];
    $Bill_GoodsDescription = $billDataArr['Bill_GoodsDescription'];
    $Bill_openid = $billDataArr['Bill_openid'];
    
    //根据Openid取得用户名和手机号
    $vipInfoArr = vipInfo($Bill_openid,$weixinID);
    $bill_Name = $vipInfoArr[0]['Vip_name'];
    $bill_Tel = $vipInfoArr[0]['Vip_tel'];

    $Bill_SN = $billDataArr['Bill_SN'];
    $Bill_insertDate = $billDataArr['Bill_insertDate'];
    $Bill_goods_beginDate = $billDataArr['Bill_goods_beginDate'];
    $Bill_goods_endDate = $billDataArr['Bill_goods_endDate'];
    $Bill_goods_expirationDate = $billDataArr['Bill_goods_expirationDate'];
    $Bill_Status = $billDataArr['Bill_Status'];
    
    if(!$billDataArr){
        $arr['success'] = 0;
        $arr['msg'] = "无该兑换码所对应的中奖信息，请确认！";
    }else{
        $arr['success'] = 1;
        $arr['bill_Name'] = $bill_Name;
        $arr['bill_Tel'] = $bill_Tel;
        $arr['Bill_type'] = $Bill_type;
        $arr['Bill_GoodsName'] = $Bill_GoodsName;
        $arr['Bill_GoodsDescription'] = $Bill_GoodsDescription;
        $arr['Bill_SN'] = $Bill_SN;
        $arr['Bill_insertDate'] = $Bill_insertDate;
        $arr['Bill_goods_beginDate'] = $Bill_goods_beginDate;
        $arr['Bill_goods_endDate'] = $Bill_goods_endDate;
        $arr['Bill_goods_expirationDate'] = $Bill_goods_expirationDate;
        $arr['Bill_Status'] = $Bill_Status;
        
    }
}else if($action == "Awarded"){
    $SNCode = addslashes($_POST['SNCode']);
    $nowtime=date("Y/m/d H:i:s",time());
    $updateBill = "update bill
                   set Bill_editDate = '$nowtime',
                       Bill_Status = 1
                   where Bill_SN = '$SNCode'
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 2;
        $arr['msg'] = "兑换成功！";
        
    }else{
        $arr['success'] = 3;
        $arr['msg'] = "兑换失败，请确认兑换码！";
    }	
    
}else{
}
echo json_encode($arr);
