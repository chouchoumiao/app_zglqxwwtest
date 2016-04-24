<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$nowtime=date("Y/m/d H:i:s",time());

$action = addslashes($_GET['action']);
$bbsID = addslashes($_POST["bbsID"]);

$weixinID = $_SESSION['weixinID'];

if($action == "Reply"){
    
    $newBbsReply = addslashes($_POST["newBbsReply"]);
    
    //设置未通过审核
    $updateBill = "update bbsInfo
                   set BBS_REPLY = '$newBbsReply',
                   BBS_REPLYTIME = '$nowtime'
                   where id = $bbsID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【回复线索内容】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【回复线索内容】设置失败，请重新设置！";
    }
}

//BBS_ISOK:  -1:黑帮 1:红榜 2:审核不通过

if($action == "NG"){
    //设置未通过审核
    $updateBill = "update bbsInfo
                   set BBS_ISOK = 2,
                   BBS_EDITETIME = '$nowtime'
                   where id = $bbsID
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
if($action == "good"){
    
    $bbsContent = addslashes($_POST["newBbsContent"]);
    
    $updateBill = "update bbsInfo
                   set BBS_ADVICE = '$bbsContent',
                   BBS_ISOK = 1,
                   BBS_EDITETIME = '$nowtime'
                   where id = $bbsID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【审核为红榜】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【审核为红榜】设置失败，请重新设置！";
    }
}
//取得set页面传递过来的数据
if($action == "bad"){
    
    $bbsContent = addslashes($_POST["newBbsContent"]);
    
    $updateBill = "update bbsInfo
                   set BBS_ADVICE = '$bbsContent',
                   BBS_ISOK = -1,
                   BBS_EDITETIME = '$nowtime'
                   where id = $bbsID
                   AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateBill);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【审核为黑榜】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【审核为黑榜】设置失败，请重新设置！";
    }
}
echo json_encode($arr);
