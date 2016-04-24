<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = addslashes($_GET['weixinID']);

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$masterID = addslashes($_POST["masterID"]);
if(!isset($masterID)){
    echo "<script>alert('答题分类名称取得失败');history.back();</Script>";
    exit;
}

//取得前所有信息 并且根据答对题目数降序，用时升序排列
$sql = "select distinct answer_recorded_openid from answer_recorded
        where WEIXIN_ID = $weixinID
        AND question_master_ID = '$masterID'
        AND status = 0
        AND answer_recorded_OKCount =10
        order by answer_recorded_editTime ASC
        limit 200";

$record = getDataBySql($sql);

$arr['success'] = "OK";
$vipIDStr = "";
$vipNameStr = "";
$vipTelStr = "";
$dataStr = "";

$recordCount = count($record);
for($i=0; $i<$recordCount; $i++){
    
    $sql = "select Vip_id,Vip_name,Vip_tel from Vip
            where  Vip_isDeleted = 0
            AND Vip_openid = '".$record[$i]['answer_recorded_openid']."'";
    $getVipData = getLineBySql($sql);

    $sql = "select answer_recorded_editTime from answer_recorded
            where WEIXIN_ID = $weixinID
            AND question_master_ID = $masterID
            AND status = 0
            AND answer_recorded_OKCount = 10
            AND answer_recorded_openid = '".$record[$i]['answer_recorded_openid']."'
            order by answer_recorded_editTime ASC";
    $OKCountData = getDataBySql($sql);

    if($vipIDStr == ""){

        $vipIDStr = $getVipData['Vip_id'];
        $vipNameStr = $getVipData['Vip_name'];
        $vipTelStr = $getVipData['Vip_tel'];

        $dataStr = $OKCountData[0]['answer_recorded_editTime'];
    }else{

        $vipIDStr = $vipIDStr.",".$getVipData['Vip_id'];
        $vipNameStr = $vipNameStr.",".$getVipData['Vip_name'];
        $vipTelStr = $vipTelStr.",".$getVipData['Vip_tel'];

        $dataStr = $dataStr.",".$OKCountData[0]['answer_recorded_editTime'];
    }
}

$arr['vipIDStr'] = $vipIDStr;
$arr['vipNameStr'] = $vipNameStr;
$arr['vipTelStr'] = $vipTelStr;
$arr['dataStr'] = $dataStr;

echo json_encode($arr);