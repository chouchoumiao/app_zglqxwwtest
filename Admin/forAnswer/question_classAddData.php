<?php
//session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = addslashes($_GET['weixinID']);

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$classAddTitle = addslashes($_POST["classAddTitle"]);
if(!isset($classAddTitle)){
    echo "<script>alert('答题分类名称取得失败');history.back();</Script>";
    exit;
}

$nowTime  = date("Y-m-d H:i:s",time());
$sql = "insert into question_class
                    (WEIXIN_ID,
                    question_class_title,
                    insertTime
                    ) values (
                    $weixinID,
                    '$classAddTitle',
                    '$nowTime'
                    )";
$errono = SaeRunSql($sql);
if($errono != 0){
    $arr['success'] = "InsertNG";
    $arr['msg'] = "追加失败！";
    
    echo json_encode($arr);	
    exit;
}

$arr['success'] = "OK";
$arr['msg'] = "设置成功！一秒钟后返回...";

echo json_encode($arr);