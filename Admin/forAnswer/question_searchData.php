<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = addslashes($_GET['weixinID']);
$action = addslashes($_GET['action']);

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$info = addslashes($_POST["info"]);
if(!isset($info)){
    echo "<script>alert('答题分类名称取得失败');history.back();</Script>";
    exit;
}

//取得前所有信息 并且根据答对题目数降序，用时升序排列
if($action == "classAndID"){
    $sql = "select question_master_ID,
                answer_recorded_openid,
                SUM(answer_recorded_OKCount) AS totalCount,
                SUM(TimeDis) AS totalTimeDis,
                SUM(answer_recorded_OKintegralCount) AS totalIntegralCount
            from answer_recorded
            where WEIXIN_ID = $weixinID
            AND question_master_ID = '$info'
            AND status = 0
            GROUP BY question_master_ID,
                answer_recorded_openid
            order by totalCount DESC,
                totalTimeDis ASC
            LIMIT 20000";
}else{
    $sql = "select answer_recorded_openid,
                SUM(answer_recorded_OKCount) AS totalCount,
                SUM(TimeDis) AS totalTimeDis,
                SUM(answer_recorded_OKintegralCount) AS totalIntegralCount
            from answer_recorded
            where WEIXIN_ID = $weixinID
                AND question_master_class = '$info'
                AND status <> -1
            GROUP BY answer_recorded_openid
            order by totalCount DESC,
                totalTimeDis ASC
            LIMIT 20000";
}

$record = getDataBySql($sql);

//追加判断 20160104
if(!$record || empty($record)){
    $arr['success'] = "NG";
    echo json_encode($arr);
    exit;
}
$arr['success'] = "OK";
$question_master_IDStr = "";
$question_master_TitleStr = "";
$vipIDStr = "";
$vipNameStr = "";
$vipTelStr = "";
$totalCountStr = "";
$totalTimeDisStr = "";
$totalIntegralCountStr = "";
$OKCountStr = 0;

$recordCount = count($record);
for($i=0;$i<$recordCount;$i++){
    
    $sql = "select Vip_id,
                   Vip_name,
                   Vip_tel
            from Vip
            where Vip_isDeleted = 0
            AND Vip_openid = '".$record[$i]['answer_recorded_openid']."'";
    $getVipData = getLineBySql($sql);
    if($action == "classAndID"){
        $sql = "select QUESTION_TITLE from question_master
                where MASTER_ID=".$record[$i]['question_master_ID'];
        $getMasterTitle = getVarBySql($sql);
        
        $sql = "select count(*) from answer_recorded
                where WEIXIN_ID = $weixinID
                AND question_master_ID = $info
                AND status = 0
                AND answer_recorded_OKCount = 10
                AND answer_recorded_openid = '".$record[$i]['answer_recorded_openid']."'";
        $OKCount = getVarBySql($sql);
    }
    
    
    if($vipIDStr == ""){
        if($action == "classAndID"){
            $question_master_IDStr = $record[$i]['question_master_ID'];
            $question_master_TitleStr = $getMasterTitle;
            $OKCountStr = $OKCount;
        }
        $vipIDStr = $getVipData['Vip_id'];
        $vipNameStr = $getVipData['Vip_name'];
        $vipTelStr = $getVipData['Vip_tel'];
        
        $totalCountStr = $record[$i]['totalCount'];
        $totalTimeDisStr = $record[$i]['totalTimeDis'];
        $totalIntegralCountStr = $record[$i]['totalIntegralCount'];
    }else{
        if($action == "classAndID"){
            $question_master_IDStr = $question_master_IDStr.",".$record[$i]['question_master_ID'];
            $question_master_TitleStr = $question_master_TitleStr.",".$getMasterTitle;
            $OKCountStr = $OKCountStr.",".$OKCount;
        }
        $vipIDStr = $vipIDStr.",".$getVipData['Vip_id'];
        $vipNameStr = $vipNameStr.",".$getVipData['Vip_name'];
        $vipTelStr = $vipTelStr.",".$getVipData['Vip_tel'];
        $totalCountStr = $totalCountStr.",".$record[$i]['totalCount'];
        
        $totalTimeDisStr = $totalTimeDisStr.",".$record[$i]['totalTimeDis'];
        
        $totalIntegralCountStr = $totalIntegralCountStr.",".$record[$i]['totalIntegralCount'];
    }
}
if($action == "classAndID"){
    $arr['question_master_IDStr'] = $question_master_IDStr;
    $arr['question_master_TitleStr'] = $question_master_TitleStr;
    $arr['OKCountStr'] = $OKCountStr;
}
$arr['vipIDStr'] = $vipIDStr;
$arr['vipNameStr'] = $vipNameStr;
$arr['vipTelStr'] = $vipTelStr;
$arr['totalCountStr'] = $totalCountStr;
$arr['totalTimeDisStr'] = $totalTimeDisStr;
$arr['totalIntegralCountStr'] = $totalIntegralCountStr;

echo json_encode($arr);