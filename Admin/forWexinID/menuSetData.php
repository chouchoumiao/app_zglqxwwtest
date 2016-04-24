<?php
session_start();
header('Content-Type: text/html; charser=UTF-8');
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$ccfs = addslashes($_REQUEST['ccfs']);
if($ccfs == 'tj'){
    $status = 2;
}else if($ccfs == 'bf'){
    $status = 1;
}else{
    $status = 0;
}

$weixinID = addslashes($_GET['weixinID']);

$sql = "select * from AdminToWeiID
        where weixinStatus = 1
        AND id =$weixinID";

$weixinInfo = getLineBySql($sql);

$appID = $weixinInfo['weixinAppId'];
$appSecret = $weixinInfo['weixinAppSecret'];

if(($appID == "") || ($appSecret == "")){
    $msg = "请先设置该公众号的appID和appSecret";
    echoInfo($msg);
    exit;
}
$nowtime=date("Y/m/d H:i:s",time());

//小按钮分类（最大五个） 属于按钮分类1
$arrSunBtnNameForBtn1 = array();
$arrSunBtnTypeForBtn1 = array();
$arrSunBtnContentForBtn1 = array();

//小按钮分类（最大五个） 属于按钮分类2
$arrSunBtnNameForBtn2 = array();
$arrSunBtnTypeForBtn2 = array();
$arrSunBtnContentForBtn2 = array();

//小按钮分类（最大五个） 属于按钮分类3
$arrSunBtnNameForBtn3 = array();
$arrSunBtnTypeForBtn3 = array();
$arrSunBtnContentForBtn3 = array();

//获取大按钮分类名称，种类，内容
if(addslashes($_REQUEST['titleName1'])){
    $arrBtnName1 = addslashes($_REQUEST['titleName1']);
}
if(addslashes($_REQUEST['menutype1'])){
    $arrBtnType1 = addslashes($_REQUEST['menutype1']);
}
if(addslashes($_REQUEST['menutype1']) == "view"){
    $arrBtnContent1 = addslashes($_REQUEST['linkName1']);
}else if(addslashes($_REQUEST['menutype1']) == "click"){
    $arrBtnContent1 = addslashes($_REQUEST['clickName1']);
}

if(addslashes($_REQUEST['titleName2'])){
    $arrBtnName2 = addslashes($_REQUEST['titleName2']);
}
if(addslashes($_REQUEST['menutype2'])){
    $arrBtnType2 = addslashes($_REQUEST['menutype2']);
}
if(addslashes($_REQUEST['menutype2']) == "view"){
    $arrBtnContent2 = addslashes($_REQUEST['linkName2']);
}else if(addslashes($_REQUEST['menutype2']) == "click"){
    $arrBtnContent2 = addslashes($_REQUEST['clickName2']);
}

if(addslashes($_REQUEST['titleName3'])){
    $arrBtnName3 = addslashes($_REQUEST['titleName3']);
}
if(addslashes($_REQUEST['menutype3'])){
    $arrBtnType3 = addslashes($_REQUEST['menutype3']);
}
if(addslashes($_REQUEST['menutype3']) == "view"){
    $arrBtnContent3 = addslashes($_REQUEST['linkName3']);
}else if(addslashes($_REQUEST['menutype3']) == "click"){
    $arrBtnContent3 = addslashes($_REQUEST['clickName3']);
}

//获取各个大按钮分类的小分类的 名称，种类，内容
for($j = 1; $j<=15; $j++){
    if((addslashes($_REQUEST['subTitleName'.$j])) && (addslashes($_REQUEST['subMenutype'.$j]))){
        if($j>=1 && $j <=5){
            $arrSunBtnNameForBtn1[] = addslashes($_REQUEST['subTitleName'.$j]);
            $arrSunBtnTypeForBtn1[] = addslashes($_REQUEST['subMenutype'.$j]);
            if(addslashes($_REQUEST['subMenutype'.$j]) == "view"){
                $arrSunBtnContentForBtn1[] = addslashes($_REQUEST['subLinkName'.$j]);
            }else if(addslashes($_REQUEST['subMenutype'.$j]) == "click"){
                $arrSunBtnContentForBtn1[] = addslashes($_REQUEST['subClickName'.$j]);
            }
        }else if($j>=6 && $j <=10){
            $arrSunBtnNameForBtn2[] = addslashes($_REQUEST['subTitleName'.$j]);
            $arrSunBtnTypeForBtn2[] = addslashes($_REQUEST['subMenutype'.$j]);
            if(addslashes($_REQUEST['subMenutype'.$j]) == "view"){
                $arrSunBtnContentForBtn2[] = addslashes($_REQUEST['subLinkName'.$j]);
            }else if(addslashes($_REQUEST['subMenutype'.$j]) == "click"){
                $arrSunBtnContentForBtn2[] = addslashes($_REQUEST['subClickName'.$j]);
            }
        }else{
            $arrSunBtnNameForBtn3[] = addslashes($_REQUEST['subTitleName'.$j]);
            $arrSunBtnTypeForBtn3[] = addslashes($_REQUEST['subMenutype'.$j]);
            if(addslashes($_REQUEST['subMenutype'.$j]) == "view"){
                $arrSunBtnContentForBtn3[] = addslashes($_REQUEST['subLinkName'.$j]);
            }else if(addslashes($_REQUEST['subMenutype'.$j]) == "click"){
                $arrSunBtnContentForBtn3[] = addslashes($_REQUEST['subClickName'.$j]);
            }
        }
    }else if((addslashes($_REQUEST['subTitleName'.$j]) == "") && (addslashes($_REQUEST['subMenutype'.$j]) == "")){
    }else{
        $msg = "设置第".$j."个分按钮时错误，分按钮的名称和内容必须同时设置".addslashes($_REQUEST['subTitleName'.$j]).addslashes($_REQUEST['subMenutype'.$j]);
        echoInfo($msg);
        exit;
    }
}

if(!$arrBtnName1 && !$arrBtnName2 && !$arrBtnName3){
    $msg = "请设置主按钮的内容";
    echoInfo($msg);
    exit;
}else{
    if($arrBtnName1){
        if(count($arrSunBtnNameForBtn1) == 0){
            if(!$arrBtnType1){
                $msg = "第一个主按钮设置错误(主按，分按钮都没有设置完)";
                echoInfo($msg);
                exit;
            }
        }else{
            if($arrBtnType1){
                $msg = "第一个主按钮设置错误(设置分按钮时，请将主按钮的链接去除，只留下名称)";
                echoInfo($msg);
                exit;
            }
        }
    }else{
        if($arrBtnType1){
            $msg = "第一个主按钮设置错误(有分按的情况下，需设置主按钮名称)";
            echoInfo($msg);
            exit;
        }
    }
    if($arrBtnName2){
        if(count($arrSunBtnNameForBtn2) == 0){
            if(!$arrBtnType2){
                $msg = "第二个主按钮设置错误(主按，分按钮都没有设置完)";
                echoInfo($msg);
                exit;
            }
        }else{
            if($arrBtnType2){
                $msg = "第二个主按钮设置错误(设置分按钮时，请将主按钮的链接去除，只留下名称)";
                echoInfo($msg);
                exit;
            }
        }
    }else{
        if($arrBtnType2){
            $msg = "第二个主按钮设置错误(有分按的情况下，需设置主按钮名称)";
            echoInfo($msg);
            exit;
        }
    }
    if($arrBtnName3){
        if(count($arrSunBtnNameForBtn3) == 0){
            if(!$arrBtnType3){
                $msg = "第三个主按钮设置错误(主按，分按钮都没有设置完)";
                echoInfo($msg);
                exit;
            }
        }else{
            if($arrBtnType3){
                $msg = "第三个主按钮设置错误(设置分按钮时，请将主按钮的链接去除，只留下名称)";
                echoInfo($msg);
                exit;
            }
        }
    }else{
        if($arrBtnType3){
            $msg = "第三个主按钮设置错误(有分按的情况下，需设置主按钮名称)";
            echoInfo($msg);
            exit;
        }
    }
}

$arrSunBtnNameJson1 = json_encode($arrSunBtnNameForBtn1);
$arrSunBtnTypeJson1 = json_encode($arrSunBtnTypeForBtn1);
$arrSunBtnContentJson1 = json_encode($arrSunBtnContentForBtn1);

$arrSunBtnNameJson2 = json_encode($arrSunBtnNameForBtn2);
$arrSunBtnTypeJson2 = json_encode($arrSunBtnTypeForBtn2);
$arrSunBtnContentJson2 = json_encode($arrSunBtnContentForBtn2);

$arrSunBtnNameJson3 = json_encode($arrSunBtnNameForBtn3);
$arrSunBtnTypeJson3 = json_encode($arrSunBtnTypeForBtn3);
$arrSunBtnContentJson3 = json_encode($arrSunBtnContentForBtn3);

$deleteSql = "delete from menuInfo where WEIXIN_ID = $weixinID";
$isdelete = SaeRunSql($deleteSql);

if($isdelete != 0){
    $msg = "原先存在自定义菜单，删除原先菜单失败，请重试！";
    echoInfo($msg);
    exit;
}

$sql = "insert into menuInfo
                    (WEIXIN_ID,
                    menu_name1,
                    menu_msgType1,
                    menu_content1,
                    menu_name2,
                    menu_msgType2,
                    menu_content2,
                    menu_name3,
                    menu_msgType3,
                    menu_content3,
                    menu_subNameForBtn1,
                    menu_subMsgTypeForBtn1,
                    menu_subContentForBtn1,
                    menu_subNameForBtn2,
                    menu_subMsgTypeForBtn2,
                    menu_subContentForBtn2,
                    menu_subNameForBtn3,
                    menu_subMsgTypeForBtn3,
                    menu_subContentForBtn3,
                    menu_insertTime
                    ) values (
                    $weixinID,
                    '$arrBtnName1',
                    '$arrBtnType1',
                    '$arrBtnContent1',
                    '$arrBtnName2',
                    '$arrBtnType2',
                    '$arrBtnContent2',
                    '$arrBtnName3',
                    '$arrBtnType3',
                    '$arrBtnContent3',
                    '$arrSunBtnNameJson1',
                    '$arrSunBtnTypeJson1',
                    '$arrSunBtnContentJson1',
                    '$arrSunBtnNameJson2',
                    '$arrSunBtnTypeJson2',
                    '$arrSunBtnContentJson2',
                    '$arrSunBtnNameJson3',
                    '$arrSunBtnTypeJson3',
                    '$arrSunBtnContentJson3',
                    '$nowtime'
                    )";

$resultErrorNo = SaeRunSql($sql);

if($resultErrorNo != 0)
{
    $msg = "数据库操作失败！";
}else{
   $menuCode = require_once('menuCreate.php');

   if($menuCode->errcode == 0){
       $msg = "菜单创建成功";
   }else{
        $msg = "菜单创建失败".$menuCode->errmsg;
        $sql = "select * from ErrorCode";
        $errorInfo = getDataBySql($sql);
        if($errorInfo){
            $errorInfoCount = count($errorInfo);
            for($i = 0;$i<$errorInfoCount;$i++){
               if($menuCode->errcode == $errorInfo[$i]['errorCode']){
                   $msg = "菜单创建失败,原因：".$errorInfo[$i]['errorMsg'];
                   break;
               }
           }
        }


    }
}
echoInfo($msg);
exit;