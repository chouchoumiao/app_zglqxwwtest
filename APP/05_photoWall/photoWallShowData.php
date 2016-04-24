<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$nowtime=date("Y/m/d H:i:s",time());

$weixinID = addslashes($_GET["weixinID"]);
$id = addslashes($_POST["id"]);
$num = addslashes($_POST["num"]);


//设置未通过审核
$updateSql = "update photoWall
              set PHOTOWALL_LIKENUM = '$num',
                  PHOTOWALL_EDITETIME = '$nowtime'
              where id = $id
              AND WEIXIN_ID = $weixinID";

$errorNo = SaeRunSql($updateSql);
if($errorNo == 0){
    $arr['success'] = 1;
}else{
    $arr['success'] = -1;
}
echo json_encode($arr);	
