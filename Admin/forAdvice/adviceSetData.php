<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
header("Content-type:text/html;charset=utf-8");
$nowtime=date("Y/m/d H:i:s",time());

$action = addslashes($_GET['action']);
$adviceID = addslashes($_POST["adviceID"]);

$weixinID = $_SESSION['weixinID'];

if($action == "Reply"){
    
    $newBbsReply = addslashes($_POST["newBbsReply"]);
    
    //设置未通过审核
    $updateBill = "update adviceInfo
                   set ADVICE_REPLY = '$newBbsReply',
                   ADVICE_REPLYTIME = '$nowtime'
                   where id = $adviceID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【回复建言内容】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【回复建言内容】设置失败，请重新设置！";
    }
}

//ADVICE_ISOK:  -1:黑帮 1:红榜 2:审核不通过

if($action == "NG"){
    //设置未通过审核
    $updateBill = "update adviceInfo
                   set ADVICE_ISOK = 2,
                   ADVICE_EDITETIME = '$nowtime',
                   ADVICE_EVENT = 0
                   where id = $adviceID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【未通过审核】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【未通过审核】设置失败，请重新设置！";
    }
}

//取得set页面传递过来的数据
if($action == "ok"){
    
    $adviceContent = addslashes($_POST["newBbsContent"]);
    
    $updateBill = "update adviceInfo
                   set ADVICE_ADVICE = '$adviceContent',
                   ADVICE_ISOK = 1,
                   ADVICE_EDITETIME = '$nowtime',
                   ADVICE_EVENT = 0
                   where id = $adviceID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【通过审核】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【通过审核】设置失败，请重新设置！";
    }
}

//取得set页面传递过来的数据
if($action == "okANDEvent"){

    $adviceContent = addslashes($_POST["newBbsContent"]);

    $updateBill = "update adviceInfo
                   set ADVICE_ADVICE = '$adviceContent',
                   ADVICE_ISOK = 3,
                   ADVICE_EDITETIME = '$nowtime',
                   ADVICE_EVENT = 1
                   where id = $adviceID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);

    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【通过审核并有抽奖资格】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【通过审核并有抽奖资格】设置失败，请重新设置！";
    }
}


echo json_encode($arr);
