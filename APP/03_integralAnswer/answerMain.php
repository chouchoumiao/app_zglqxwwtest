<!DOCTYPE html>
<html>
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

isVipByOpenid($openid,$weixinID,"03_integralAnswer/answerMain.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 

<link rel="stylesheet" type="text/css" href="css/common.min.css">
<link href="css/goodstd.min.css?v=4" type="text/css" rel="stylesheet">

<style type="text/css">
    body{ 
        background:url(./img/bg.jpg);
        background-position:center; 
        background-repeat:repeat;
    }
    .wrapper{
        width: 320px;
        position: relative;
        margin: 0 auto;
        max-width: 500px;
    }
    .header{
        position:absolute;
        top:3px;
        width:100%;
        z-index:11;
    } 
    .bg{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: -1;
    }
    .desc-cont{
        position:relative;
        background: rgba(255,255,255,0.3);
        z-index: 1;top: 70px;
        width:215px;
        margin: 0px auto 50px;
        word-break: break-word;
        padding: 5px 10px 20px;
        font-size:15px;
        line-height: 24px;
        left: -1px;
    }
    .start{
        display: block;
        width: 70px;
        z-index: 9;
        margin-top:3px;
        margin: 0 auto;
        border:none;
    }
    .top{
        position:fixed;
        bottom: 0px;
        z-index: 10;
        border:none;
    }
</style>
</head>
<body>
<div class="wrapper" id = "wrapper">
    <img class = "bg" src="./img/bg2.png" />
    <img class ="header" src="./img/header.png" />
    <div class="desc-cont">
        <h3>活动说明：</h3>
        <p>号外，号外，“路桥发布”将举行“路桥百晓”知识竞答活动。你对路桥的人文历史知晓不？南官河治污用什么方式？石浜山区块拆违后要建什么？路桥有哪些主要产业？作为一名公民应该怎样遵守文明礼仪？遇到电梯故障该怎么避险？如果你对这些都比较了解，快来参加我们的答题，有丰富的奖品哦！</p>
        <br>
        <h3><strong>活动规则：</strong></h3>
        <p></p>
        <p style="color:#666666;font-family:'Microsoft YaHei', Lato, 'Helvetica Neue', Helvetica, Arial, sans-serif;background-color:#FCFCFC;">
            <span style="line-height:1.5;">1、每天只能参加一次，系统将随机产生10道题目</span>
        </p>
        </br><p style="color:#666666;font-family:'Microsoft YaHei', Lato, 'Helvetica Neue', Helvetica, Arial, sans-serif;background-color:#FCFCFC;">
            2、本赛季活动时间为8月12日-8月18日，结束时将根据本周活动总答对题目数进行排名，答对题目数相同者会自动按照答题时间长短进行排名（答题时间较短者排名靠前）
        </p>
        </br><p style="color:#666666;font-family:'Microsoft YaHei', Lato, 'Helvetica Neue', Helvetica, Arial, sans-serif;background-color:#FCFCFC;">
            3、月冠军将根据本月总答对题数进行排名，排名规则和周赛一致
        </p></br>
        <h3><strong>本次活动奖品：</strong></h3>
        <p style="color:#666666;font-family:'Microsoft YaHei', Lato, 'Helvetica Neue', Helvetica, Arial, sans-serif;background-color:#FCFCFC;">
            周赛奖品：</br>
            第1名：iPod shuffle 播放器 一台</br>
            第2-4名：小米（MI）手环 一个</br>
            第5-20名：手机充值卡（50元，限移动）</br>
            百分百先生奖：10元话费（限前100名答对当天所有题目者）</br>
            月赛奖品：</br>
            第1名：凯立德高清夜视1296P行车记录仪带电子测速狗一体机（价值900元左右）</br>
            第2-3名：Kindle电子书阅读器(入门版，价值500元左右）</br>
        </p>
        <p></p>
        </br>
        <a href="javascript:location.href='./vipIntegralAnswer.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>'">
            <img class="start" src="./img/button.png">
        </a>

    </div>
    <div class ="top" id = "list">
        <img src="./img/top.png" />
    </div>
</div>
<div id = "pageone" style = "display:none"></div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
    $(function(){
        $("#list").click(function(){
            $.ajax({
                url:'vipIntegralAnswerList.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID ?>'//改为你的动态页
                ,type:"POST"
                ,data:{}
                ,dataType:"json"
                ,success:function(json){
                    if(json.success == 0){
                        $("body").css("background","none")
                        $("#wrapper").hide();
                        $('<div id="topMsg" />').html(json.msg).appendTo('#pageone');
                        $("#pageone").show();
                    }else{
                        $("body").css("background","none")
                        $("#wrapper").hide();
                        $('<div id="topMsg" />').html("<p></br></p><h1>"+json.msg+"</h1>").css("color","#FF0000").appendTo('#pageone');
                        $("#pageone").show();
                    }
                } 
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
    });
</script>
</body>
</html>

