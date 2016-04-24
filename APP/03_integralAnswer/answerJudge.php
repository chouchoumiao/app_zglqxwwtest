<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$vipInfoArr = vipInfo($openid,$weixinID);

if($vipInfoArr){
    
    $SubmitAnswersStr = addslashes($_POST["answerStr"]);
    $SubmitAnswersArr=explode("-",$SubmitAnswersStr);

    $CorrectAnswerStr = addslashes($_GET["CorrAns"]);
    $CorrectAnswerArr=explode("-",$CorrectAnswerStr);
    
    $returnDataOKCount = 0;

    $CorrectAnswerArrCount = count($CorrectAnswerArr);
    for($i = 0;$i < $CorrectAnswerArrCount; $i++){
        if($SubmitAnswersArr[$i] == $CorrectAnswerArr[$i]){
            $returnDataOKCount++;
        }	
    }
    $NowDate = date("Y-m-d",time());
    $sql = "select * from question_master
            where QUESTION_BEGIN_DATE <='$NowDate'
            AND '$NowDate' <= QUESTION_END_DATE
            AND WEIXIN_ID = $weixinID
            AND QUESTION_SATUS = 1";
    $questionMasterInfo = getLineBySql($sql);
    
    /*
    $sql = "select COUNT(*) from answer_recorded where LEFT(answer_recorded_editTime,10) = '$NowDate' AND WEIXIN_ID = $weixinID AND answer_recorded_openid = '$openid'";
    $thisVipTimes = getVarBySql($sql);
    
    if($thisVipTimes >= $questionMasterInfo['QUESTION_MAXTIMES']){
        $arr['success'] = 0;
        $arr['msg'] = "亲爱的会员，今天您的答题次数已经达到最大值了，明天再来吧！";
        echo json_encode($arr);
        exit;
    }*/
    
    $countArr = json_decode($questionMasterInfo['QUESTION_WIN_COUNT']);
    $integralArr = json_decode($questionMasterInfo['QUESTION_WIN_INTEGRAL']);
    $commentArr = json_decode($questionMasterInfo['QUESTION_WIN_COMMENT']);
    
    
    $integralCount = 0;
    $count = count($countArr);
    for($i = 0;$i<$count;$i++){
        $questionWinCount = $countArr[$i];
        $questionWinIntegral = $integralArr[$i];
        $questionWinComment = $commentArr[$i];
        if($returnDataOKCount == $questionWinCount){
            $integralCount = $questionWinIntegral;
            $msg = $questionWinComment;
        }
    }
    
    //取得当前时间
    $nowTime  = date("Y-m-d H:i:s",time());
    
    //积分<=0：不做Vip更新
    if($integralCount <= 0){
        $msg = "亲，要多温习功课了哟！";
    //积分不为0：更新Vip表的积分
    }else{
        $config = getConfigWithMMC($weixinID);
        //判断基础信息是否取得成功
        if($config == '' || empty($config)){
            echo "取得配置信息失败，请确认！";
            exit;
        }
        $weixinName = $config['CONFIG_VIP_NAME'];

        //取得会员的积分
        $ThisVip_integral = $vipInfoArr[0]["Vip_integral"];
        
        $newIntegral = $ThisVip_integral + $integralCount;
        $sqlVip = "update Vip
                   set Vip_integral = $newIntegral,
                       Vip_edittime = '$nowTime'
                   where Vip_openid = '$openid'";
        $errornoForVip = SaeRunSql($sqlVip);
        if($errornoForVip != 0){
            $arr['success'] = 0;
            $arr['msg'] = "累计".$weixinName."时出错！";
            echo json_encode($arr);
            exit;
        }
        //追加积分变动时写入记录表中 功能
        $updateIntegralSQL = "insert into integralRecord
                                          (openid,
                                          event,
                                          totalIntegral,
                                          integral,
                                          insertTime
                                          ) VALUE (
                                          '$openid',
                                          '答题活动追加的'.$weixinName,
                                          $ThisVip_integral,
                                          $integralCount,
                                          '$nowTime'
                                          )";
        SaeRunSql($updateIntegralSQL);
    }
    //取得答题活动出传入的ID和Class
    $masterID = addslashes($_GET["masterID"]);
    $masterClass = addslashes($_GET["masterClass"]);
    $fromTime = addslashes($_GET["fromTime"]);
    $timeDis = strtotime($nowTime) - strtotime($fromTime);
    
    //将新答题记录插入 answer_recorded表
    $sqlAnswer = "insert into answer_recorded
                              (answer_recorded_openid,
                              WEIXIN_ID,
                              answer_recorded_OKCount,
                              answer_recorded_OKintegralCount,
                              question_master_ID,
                              question_master_class,
                              answer_recorded_editTime,
                              TimeDis,
                              status
                              ) values (
                              '$openid',
                              $weixinID,
                              $returnDataOKCount,
                              $integralCount,
                              $masterID,
                              '$masterClass',
                              '$nowTime',
                              $timeDis,
                              0
                              )";
    $errornoForAns = SaeRunSql($sqlAnswer);
    if($errornoForAns != 0)
    {	
        $arr['success'] = 0;
        $arr['msg'] = "追加新答题信息时出错！";
    }else{
        $arr['success'] = 1;
        $arr['msg'] = $msg;
        $arr['OkCount'] = $returnDataOKCount;
        $arr['integra'] = $integralCount;
        $arr['fromTime'] = $fromTime;
        $arr['toTime'] = $nowTime;
    }

}else{
    $arr['success'] = 0;
    $arr['msg'] = "取不到该会员的".$weixinName."值。";
}
echo json_encode($arr);