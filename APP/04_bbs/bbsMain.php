<html class="js cssanimations">
<head>
<title>五水共治“红黑榜”线索征集</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">

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

?>
<style type="text/css">
#contents{
    background-color:#FFFFFF; 
    box-shadow: 0 1px 6px rgba(124, 124, 124, 0.42);
}
#news_main_img{ 
    width: 98%;
    margin: 0.5% 1% 0.5% 1%;
}
#news_main_img img{ 
    width: 100%;   
}
#wl_news_detail{
    height: 300px;
    padding: 15px; 
}
</style>
</head>
<body id="listhome">
<div id="head">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
        <ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
            <li class="active"><a href="#">活动介绍</a></li>
            <li class=""><a data-ajax="false" href="./bbsShowGood.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">红榜</a></li>
            <li class=""><a data-ajax="false" href="./bbsShowBad.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">黑榜</a></li>
            <li class=""><a data-ajax="false" href="./bbs.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">我要建言</a></li>
        </ul>
    </nav>
</div>
<div id="contents">
    <div id="news_main_img">
        <img src="img/bbsMain.jpg" />
    </div>
</div>    
<div id="wl_news_detail">
	<div style="margin-left: auto; margin-right: auto; text-align: left;">
        <span style="color: blue; font-size: 20px; display:inline-block">&nbsp &nbsp &nbsp &nbsp路桥区五水共治办现开通“红黑榜“线索征集平台，邀请广大市民图说治水，晒成效、揭短板。吐槽你们所看到的问题，倒逼河长守河尽责；点赞身边变美的河道，给尽责的治水人一些掌声，让治水正能量得以弘扬！</span>
    </div>
</div>
</body>
</html>