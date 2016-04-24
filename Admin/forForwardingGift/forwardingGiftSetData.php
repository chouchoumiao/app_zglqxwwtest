<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

$nowtime=date("Y/m/d H:i:s",time());

$action = addslashes($_GET['action']);
$id = addslashes($_GET["id"]);
$openid = addslashes($_GET["openid"]);

$reply = addslashes($_POST["reply"]);
$integral = addslashes($_POST["integral"]);


if($action == "NG"){
    //设置未通过审核
    
    //积分为0
    $updateSql = "update forwardingGift
                  set FORWARDINGGIFT_INTEGRAL = 0,
                      FORWARDINGGIFT_ISOK = 2,
                      FORWARDINGGIFT_REPLY = '$reply',
                      FORWARDINGGIFT_EDITETIME = '$nowtime'
                  where id = $id
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
    $sql = "select Vip_integral from Vip
            where Vip_openid = '$openid'
            AND WEIXIN_ID = $weixinID";
    $oldIntegral = getVarBySql($sql);

    $updateSql = "update forwardingGift
                  set FORWARDINGGIFT_INTEGRAL = $integral,
                      FORWARDINGGIFT_REPLY = '$reply',
                      FORWARDINGGIFT_ISOK = 1,
                      FORWARDINGGIFT_EDITETIME = '$nowtime'
                  where id = $id
                  AND WEIXIN_ID = $weixinID";
    $errorNo = SaeRunSql($updateSql);
    $sql = "update Vip
            set Vip_integral = Vip_integral + $integral,
                Vip_edittime = '$nowtime'
            where Vip_openid = '$openid'
            AND WEIXIN_ID = $weixinID";
    $errorNo2 = SaeRunSql($sql);

    //追加积分变动时写入记录表中 功能
    $updateIntegralSQL = "insert into integralRecord (
                          openid,event,totalIntegral,integral,insertTime) VALUE (
                          '$openid','分享有礼审核后追加分数',$oldIntegral,$integral,
                          '$nowtime')";
    SaeRunSql($updateIntegralSQL);
    
    if($errorNo == 0 || $errorNo2 == 0){
        $arr['success'] = 1;
        $arr['msg'] = "【通过审核】设置成功！";
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "【通过审核】设置失败，请重新设置！";
    }
}
echo json_encode($arr);