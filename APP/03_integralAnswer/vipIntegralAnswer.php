<?php
header("Content-type:text/html;charset=utf-8");

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];
?>
<!DOCTYPE html>
<html>
<head>
<title>答题赢取<?php echo $weixinName;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

<link rel="stylesheet" type="text/css" href="css/common.min.css">
<link href="css/goodstd.min.css?v=26" type="text/css" rel="stylesheet">

</head>
<body>
<?php
//取得questionMaster表中的信息
$NowDate = date("Y-m-d",time());
$sql = "select * from question_master
        where QUESTION_BEGIN_DATE <='$NowDate'
        AND '$NowDate' <= QUESTION_END_DATE
        AND WEIXIN_ID = $weixinID
        AND QUESTION_SATUS = 1";

$questionMasterInfo = getLineBySql($sql);

//取得questionMaster表中的信息
if(!$questionMasterInfo){
    echo '<div id="alright" class="alright"><span class="color">当前尚未有答题活动，敬请期待！</div>';
    exit;
}

$sql = "select COUNT(*) from answer_recorded
        where LEFT(answer_recorded_editTime,10) = '$NowDate'
        AND WEIXIN_ID = $weixinID
        AND answer_recorded_openid = '$openid'
        AND status = 0";
$thisVipTimes = getVarBySql($sql);

if($thisVipTimes >= $questionMasterInfo['QUESTION_MAXTIMES']){
    echo '<div id="alright" class="alright"><span class="color">亲爱的会员，今天您的答题次数已经达到最大值了，明天再来吧！</div>';
    exit;
}
$questionShowCount = $questionMasterInfo['QUESTION_SHOW_COUNT'];
$masterClass = $questionMasterInfo['QUESTION_CLASS'];
$masterID = $questionMasterInfo['MASTER_ID'];

$sql = "select * from  question_tb
        where question_class_title = '$masterClass'
        AND WEIXIN_ID = $weixinID
        AND status = 1
        order by rand() limit"." ".$questionShowCount;
$getAnswerInfo = getDataBySql($sql);

if(!$getAnswerInfo){
 
    echo '<div id="alright" class="alright"><span class="color">当前尚未有答题活动，敬请期待！</div>';
    exit;
}
echo "<script>$(document).ready(function() { $('#problem0').show();});</script>";
$countArr = json_decode($questionMasterInfo[0]['QUESTION_WIN_COUNT']);
$integralArr = json_decode($questionMasterInfo[0]['QUESTION_WIN_INTEGRAL']);
$commentArr = json_decode($questionMasterInfo[0]['QUESTION_WIN_COMMENT']);

$fromTime = date("Y-m-d H:i:s",time());
    
?>
<div id = "main">
    <div id = "headTitle" style = "display:none">
        <section id="gs_intro" style="">
            <p>答出好成绩有奖励哟!</p>
            <p>本活动一共为<?php echo $questionShowCount;?>题!</p>
            <p>
            <?php
                $count = count($countArr);
                for($i = 0; $i<$count; $i++){
                ?>
                    答对<span><?php echo $countArr[$i];?></span>题并奖励<span><?php echo $integralArr[$i];?></span><?php echo $weixinName;?><br>
                <?php       
                }
                ?>
            </p>
        </section>
        <section id="gs_score">
            <div class="gs_tle">
                <p>答 题 结 果：</p>
            </div>
        </section>
        
    </div>	
<?php
    for($i = 0; $i<$questionShowCount; $i++){
        $CorrectAnswerArr[$i] = $getAnswerInfo[$i]['question_true'];
?>		
        <div id="<?php echo "problem".$i?>" style = "display:none">
            <div id="gs_content" >
                <ul>
                <?php
                if (($getAnswerInfo[$i]['question_img'] != "") && ($getAnswerInfo[$i]['question_img'] != "imgPath error") ){
                ?>
                    <img src="<?php echo $getAnswerInfo[$i]['question_img'];?>" height = "220" width = "100%">
                <?php
                }else{
                ?>
                    </br></br></br></br></br></br></br></br>
                <?php
                }
                ?>
                    <li class="topic">
                        <p class="td"><span class="gs_num"><?php echo ($i+1);?>.</span><?php echo $getAnswerInfo[$i]['question_subject'];?></p>
                    </li>
                    <div class="li">
                        <li class="floatLine"><p><input type="radio" name="answer<?php echo ($i+1);?>" size="5"  value="A" /><span class="gs_num"> A：</span><b><?php echo $getAnswerInfo[$i]['question_optionsA'];?></b></p></li>
                    </div>
                    <div class="li">
                        <li class="floatLine"><p><input type="radio" name="answer<?php echo ($i+1);?>" size="5"  value="B" /><span class="gs_num "> B：</span><b><?php echo $getAnswerInfo[$i]['question_optionsB'];?></b></p></li>
                    </div>
                <?php
                    if($getAnswerInfo[$i]['question_optionsC'] != ""){
                ?>
                    <div class="li">
                        <li class="floatLine"><p><input type="radio" name="answer<?php echo ($i+1);?>" size="5"  value="C" /><span class="gs_num"> C：</span><b><?php echo $getAnswerInfo[$i]['question_optionsC'];?></b></p></li>
                    </div>
                <?php
                    }
                    if($getAnswerInfo[$i]['question_optionsD'] != ""){
                ?>
                    <div class="li">
                        <li class="floatLine"><p><input type="radio" name="answer<?php echo ($i+1);?>" size="5"  value="D" /><span class="gs_num "> D：</span><b><?php echo $getAnswerInfo[$i]['question_optionsD'];?></b></p></li>
                    </div>
                <?php
                    }
                ?>
                </ul>
            </div>
        </div>
<?php
    }
    $CorrectAnswerStr = implode('-', $CorrectAnswerArr);
?>
    <div id = "nextBtn">
        <div id="gs_button"><input type="button" value="下一题" class="gs_btn" id="next" ></div>
    </div>
    <div id = "subBtn" style = "display:none">
        <div id="gs_button"><input type="button" value="提交答案"  class="gs_btn" id="sub"></div>
    </div>
    <div id="alright" class="alright"  style="display:none"><span class="color"><h3>本次答题结果：</h3></div>
    <div id="gs_button">
        <input type="button" value="查看答题排行榜"  class="gs_btnSmall" id="list" style = "display:none">
    </div>
</div>
<div id="warningMsg" class="alright"  style="display:none"><span class="color"></div>
<div data-role="page" id="pageone" style = "display:none"></div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
    $(function(){
        var num = 1;
        maxCount = <?php echo $questionShowCount?>;

        //修正Bug - 修正（设置题数为1时显示按钮的错误情况）
        if(num == maxCount){
            $("#nextBtn").hide();
            $("#subBtn").show();
        }

        $("#next").click(function(){

            answerName = "answer"+num;
            var boolCheck=$(":radio[name="+ answerName +"]").is(":checked");
            if(boolCheck==false){
                alert("请选择答案后，再进入下一题");
                return false;
            }else{
                $("#"+"problem"+(num-1)).hide();
                $("#"+"problem"+num).show();
                num ++;
                if(num == maxCount){
                    $("#nextBtn").hide();
                    $("#subBtn").show();
                }
            }
        });

        $("#subBtn").click(function(){

            answerName = "answer"+num;
            var boolCheck=$(":radio[name="+ answerName +"]").is(":checked");
            if(boolCheck==false){
                alert("请选择答案后，再提交答案");
                return;
            }else{
                $("#subBtn").hide();
                $("#gs_foot").hide();
                $("#alright").show();
                var thisAnswerArr=new Array();
                var answerStr;
                $("#"+"problem"+(num-1)).hide();

                for(i=0;i< num;i++){
                    var myObj=document.getElementsByName("answer"+(i+1));
                    for(var j=0;j<myObj.length;j++){
                        if(myObj[j].checked){
                            thisAnswerArr.push(myObj[j].value);
                        }
                    }
                }
                answerStr = thisAnswerArr.join("-");

                $.ajax({
                    url:'answerJudge.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID;?>&CorrAns=<?php echo $CorrectAnswerStr ?>&masterID=<?php echo $masterID;?>&masterClass=<?php echo $masterClass ?>&fromTime=<?php echo $fromTime;?>'//改为你的动态页
                    ,type:"POST"
                    ,data:{"answerStr":answerStr}
                    ,dataType: "json"
                    ,beforeSend: function(){
                            $('<div id="waitMsg" />').html("<p></br></p><p>正在处理，等稍等。。。</p>").css("color","#FF0000").appendTo('.alright');
                            }
                    ,success:function(json){
                        $("#waitMsg").hide();
                        if(json.success == 0){
                            $('<div id="msg" />').html("<p></br></p><p>"+json.msg+"</p>").css("color","#FF0000").appendTo('.alright');
                        }else{
                            if(json.integra > 0){
                                $("#headTitle").show();
                                $('<div id="msg" />').html("<p></br></p><p>"+json.fromTime+"&nbsp开始答题</p></br><p>"+json.toTime+"&nbsp 结束答题</p></br><p>您一共答对了"+json.OkCount+"题</p></br><p>获得"+json.integra+"<?php echo $weixinName;?></p></br><p>"+json.msg+"</p>").css("color","#FF0000").appendTo('.alright');
                            }else{
                                $('<div id="msg" />').html("<p></br></p><p>"+json.fromTime+"&nbsp开始答题</p></br><p>"+json.toTime+"&nbsp 结束答题</p></br><p>您一共答对了"+json.OkCount+"题</p></br><p>"+json.msg+"</p>").css("color","#FF0000").appendTo('.alright');
                            }
                            $("#list").show();
                        }
                    }
                    ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
                });
            }
        });
        $("#list").click(function(){
            $.ajax({
                url:'vipIntegralAnswerList.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID ?>'//改为你的动态页
                ,type:"POST"
                ,data:{}
                ,dataType:"json"
                ,beforeSend: function(){
                    $("#list").hide();
                    $('<div id="waitMsg" />').html("<p></br></p><p>正在提交，等稍等。。。</p>").css("color","#FF0000").appendTo('.alright');
                }
                ,success:function(json){
                    $("#waitMsg").remove();
                    if(json.success == 0){
                        $("#main").hide();
                        $('<div id="topMsg" />').html(json.msg).appendTo('#pageone');
                        $("#pageone").show();
                    }else{
                        $("#main").hide();
                        $("#alright").remove();
                        $("#waitMsg").remove();
                        $("#msg").remove();
                        $("#list").hide();
                        $('<div id="topMsg" />').html("<p></br></p><p>"+json.msg+"</p>").css("color","#FF0000").appendTo('#warningMsg');
                        $("#warningMsg").show();
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
    });
</script>
</body>
</html>


