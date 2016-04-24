<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

$nowtime=date("Y/m/d H:i:s",time());

$action = addslashes($_GET['action']);
$photoWallID = addslashes($_POST["photoWallID"]);

if($action == "NG"){
    //设置未通过审核
    $updateSql = "update photoWall
                  set PHOTOWALL_ISOK = 2,
                      PHOTOWALL_EDITETIME = '$nowtime'
                  where id = $photoWallID
                  AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateSql);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【未通过审核】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【未通过审核】设置失败，请重新设置！";
    }
}

//取得set页面传递过来的数据
if($action == "OK"){
    
    $updateSql = "update photoWall
                  set PHOTOWALL_ISOK = 1,
                      PHOTOWALL_EDITETIME = '$nowtime'
                  where id = $photoWallID
                  AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateSql);
    if($errorNo == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【通过审核】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【通过审核】设置失败，请重新设置！";
    }
}
echo json_encode($arr);