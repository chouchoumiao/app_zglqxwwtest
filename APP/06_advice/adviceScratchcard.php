<html>
<head>
<title>“建言献策”活动</title>    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">

<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
<link type="text/css" href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="../css/bbsPhotoWallAdvice/common.css" rel="stylesheet">

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


//取得建言献策的抽奖次数
$adviceSql = "select count(*) from adviceInfo
              where WEIXIN_ID = $weixinID
              AND ADVICE_OPENID = '$openid'
              AND ADVICE_EVENT = 1";
$adviceCount = getVarBySql($adviceSql);

//取得刮刮卡使用次数
$sql = "select scratchcard_userCount from scratchcard_user
        where scratchcard_userIsAllow = 1
        AND scratchcard_userOpenid = '$openid'
        AND scratchcard_id = 159
        AND WEIXIN_ID = $weixinID";
$scratchcardedTimes = intval(getVarBySql($sql));

//$adviceCount = 5;
$adviceCount = $adviceCount - $scratchcardedTimes;


?>   

<body id="activity-detail" class="zh_CN ">      
    <div id="head">
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
			<ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
                <li class=""><a data-ajax="false" href="adviceMain.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">活动</a></li>
                <li class=""><a data-ajax="false" href="./advice.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">我要建言</a></li>
                <li class=""><a data-ajax="false" href="./adviceShow.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">最新建言</a></li>
                <li class="active"><a data-ajax="false" href="#">抽奖</a></li>
            </ul>
		</nav>
	</div>
    <br/>
    <?php
    if($adviceCount > 0){
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-warning btn-block" onclick = "location.href='../95_scratchcard/scratchcard.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>'">建言献策抽奖<?php echo $adviceCount;?>次</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }else{
    ?>
        </br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <span class="text-warning"><small>您还没有抽奖机会</small></span><br>
                        <span class="text-warning"><small>赶紧进行活动，建言通过了就有一次抽奖机会</small></span><br>
                        <span class="text-warning"><small>加油吧！</small></span>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</body>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
</html>