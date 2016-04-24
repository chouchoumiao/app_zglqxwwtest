<?php

//判断是否取得openid 和 是否为会员判定
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$weixinID = addslashes($_GET["weixinID"]);
$openid = addslashes($_GET["openid"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$NowDate = date("Y-m-d",time());
$sql = "select * from question_master
        where QUESTION_BEGIN_DATE <='$NowDate'
        AND '$NowDate' <= QUESTION_END_DATE
        AND WEIXIN_ID = $weixinID
        AND QUESTION_SATUS = 1";
$questionMasterInfo = getDataBySql($sql);

if(!$questionMasterInfo){
    
    $arr['success'] = -1;
    //$arr['msg'] =  '未取得排行榜信息！！';
    $arr['msg'] =  '当前尚未设置活动！！</br>原先排行榜信息 请在“路桥发布”界面输入“八月排名榜”进行查询！！';
    echo json_encode($arr);
    exit;
}else{
    $masterClass = $questionMasterInfo[0]['QUESTION_CLASS'];
    $masterID = $questionMasterInfo[0]['MASTER_ID'];
}

$sql = "select * from answer_recorded
        where question_master_ID =$masterID
        AND question_master_class = '$masterClass'
        AND answer_recorded_openid = '$openid'
        AND WEIXIN_ID = $weixinID
        AND status = 0
        order by answer_recorded_id Desc";

$recodeData = getDataBySql($sql);

if(!$recodeData){
    $arr['success'] = -1;
    $arr['msg'] =  '未取得排行榜信息！';
    echo json_encode($arr);
    exit;
}

//取得该会员的总分名次
$sql = "SELECT rowno,Sum,TimeDisSum
        FROM (
        SELECT answer_recorded_openid, Sum,TimeDisSum, (
        @rowno := @rowno +1
        ) AS rowno
        FROM (
        SELECT answer_recorded_openid, SUM( answer_recorded_OKCount ) AS Sum,SUM( TimeDis ) AS TimeDisSum
        FROM answer_recorded
        WHERE question_master_ID =$masterID
        AND question_master_class =  '$masterClass'
        AND WEIXIN_ID =$weixinID
        AND status =  0
        GROUP BY answer_recorded_openid
        ORDER BY Sum DESC,TimeDisSum ASC
        LIMIT 0 , 30000
        )t, (
        SELECT (
        @rowno :=0
        )
        )b
        ORDER BY Sum DESC,TimeDisSum ASC
        )tt
        WHERE answer_recorded_openid =  '$openid'";
$getThisVipRecord = getLineBySql($sql);

//取得前二十名的名次
$sql = "SELECT answer_recorded_openid,Sum,rowno,TimeDisSum
        FROM (
        SELECT answer_recorded_openid, Sum, TimeDisSum,(
        @rowno := @rowno +1
        ) AS rowno
        FROM (
        SELECT answer_recorded_openid, SUM( answer_recorded_OKCount ) AS Sum, SUM( TimeDis ) AS TimeDisSum
        FROM answer_recorded
        WHERE question_master_ID =$masterID
        AND question_master_class =  '$masterClass'
        AND WEIXIN_ID =$weixinID
        AND status =  0
        GROUP BY answer_recorded_openid
        ORDER BY Sum DESC,TimeDisSum ASC 
        LIMIT 0 , 30
        )t, (
        SELECT (
        @rowno :=0
        )
        )b
        ORDER BY Sum DESC,TimeDisSum ASC 
        )tt LIMIT 0 , 20" ;
$getFrist20Data = getDataBySql($sql);

$arr['success'] = 0;

$msg1 = "";
$msg2 = "";

$recodeDataCount = count($recodeData);
$getFrist20DataCount = count($getFrist20Data);
for($i = 0;$i<$recodeDataCount;$i++){
    $msg1 = $msg1.'<strong>时间:</strong> &nbsp <span>'.$recodeData[$i]['answer_recorded_editTime'].'</span><br>
        <strong>答对:</strong> &nbsp <span>'.$recodeData[$i]['answer_recorded_OKCount'].'</span>题<br>
        <strong>用时:</strong> &nbsp <span>'.$recodeData[$i]['TimeDis'].'</span>秒<br><br>';
}
for($i = 0;$i<$getFrist20DataCount;$i++){
    $thisOpenid = $getFrist20Data[$i]['answer_recorded_openid'];
    $sql = "select Vip_name from Vip
            where WEIXIN_ID = $weixinID
            AND Vip_openid  = '$thisOpenid'
            AND Vip_isDeleted = 0";
    $thisVipName = getVarBySql($sql);
    if($thisVipName){
        $msg2 = $msg2.'<strong>昵称:</strong> &nbsp <span>'.$thisVipName.'</span><br>
        <strong>答对:</strong> &nbsp <span>'.$getFrist20Data[$i]['Sum'].'</span>题<br>
        <strong>排名:</strong> &nbsp 第<span>'.$getFrist20Data[$i]['rowno'].'</span>名<br>
        <strong>用时:</strong> &nbsp <span>'.$getFrist20Data[$i]['TimeDisSum'].'</span>秒<br><br>';
    }
}

$arr['msg'] = '<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css">
            <script src="http://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
            <script src="http://apps.bdimg.com/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
            <div data-role="content">
                <h2>亲爱的会员</h2>
                <h3>&nbsp &nbsp 目前您的总答对数为:<span style="color:red">'.$getThisVipRecord['Sum'].'</span>题</h3>
                <h3>&nbsp &nbsp 排名:第<span style="color:red">'.$getThisVipRecord['rowno'].'</span>名</h3></br>
                <div data-role="collapsible" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u">
                    <h1>查看您的答题记录</h1>
                    <p>'.$msg1.'</p>
                </div>
                <div data-role="collapsible" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u">
                    <h1>查看当前活动的前二十名</h1>
                    <p>'.$msg2.'</p>
                </div>
            </div>';
echo json_encode($arr);
exit;