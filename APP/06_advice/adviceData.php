<?php
header("Content-type:text/html;charset=utf-8");
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_REQUEST['thisVip_openidField']);
$weixinID = addslashes($_REQUEST['thisVip_weixinID']);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$thisVip_name = addslashes($_REQUEST['textinputName']);
$thisVip_tel = addslashes($_REQUEST['textinputTel']);
$thisVip_Advice = addslashes($_REQUEST['textinputAdvice']);

//判断已经有同样的献策，有的话提示“不能重复建言”
$sql="select COUNT(*) from  adviceInfo
      where ADVICE_ADVICE = '$thisVip_Advice'
      AND WEIXIN_ID = $weixinID
      AND ADVICE_OPENID = '$openid'";

$SameAdviceCount = getVarBySql($sql);

if($SameAdviceCount>0){
    echoWarning('已经有相同内容的建言了，请不要重复提交!');
    exit;
}

$nowDate = date("Y-m-d",time());
//每天只能提交两次建言
$sql="select COUNT(*) from  adviceInfo
      where WEIXIN_ID = $weixinID
      AND ADVICE_OPENID = '$openid'
      AND DATE_FORMAT( ADVICE_CREATETIME , '%Y-%m-%d' ) = '$nowDate'";

$dayCount = getVarBySql($sql);

if($dayCount >=2){
    echoWarning('每天只能建言2次!');
    exit;
}

$nowtime = date("Y-m-d H:i:s",time());

$insertSql = "insert into adviceInfo
                          (WEIXIN_ID,
                          ADVICE_OPENID,
                          ADVICE_NAME,
                          ADVICE_TEL,
                          ADVICE_ADVICE,
                          ADVICE_CREATETIME,
                          ADVICE_EDITETIME,
                          ADVICE_ISOK
                          ) values (
                          $weixinID,
                          '$openid',
                          '$thisVip_name',
                          '$thisVip_tel',
                          '$thisVip_Advice',
                          '$nowtime',
                          '$nowtime',
                          0
                          )";
$errorNo = SaeRunSql($insertSql);

if($errorNo == 0){
    echoInfo('感谢您的建言献策，我们会认真详读，然后审核');
}else {
    echoWarning('提交时出现错误，请重新提交！');
}