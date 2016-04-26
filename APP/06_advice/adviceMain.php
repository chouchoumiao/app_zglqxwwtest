<html class="js cssanimations">
<head>
<title>“建言献策”活动</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">

<link type="text/css" href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
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

//追加是否是会员判断
isVipByOpenid($openid,$weixinID,"06_advice/adviceMain.php");

?>
<style type="text/css">
#wl_news_detail { padding: 15px; }
#news_main_img { width: 98%; margin: 0.5% 1% 0.5% 1%;}
	#news_main_img img { width: 100%;   }
#news_content { margin: 0.5% 1% 0.5% 1%; width: 100%; color: #494949; font-size: 14px; line-height: 24px; word-break: break-all; word-wrap: word-wrap; }
	#news_content .form_ele { margin-bottom: 10px; }
	#news_content p {  margin-bottom: 0; font-size: 16px; line-height: 25px;  }
	#news_content input {  height: 28px; width: 85%;}
	#news_content textarea { width: 85%; }
#bottom_remark { width: 98%; margin: 0.5% 1% 0.5% 1%; border-top: 2px dashed #77A73C; font-size: 14px; line-height: 24px; padding-top: 10px; }
</style>
</head>
<body id="listhome">
	<div id="head">
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
			<ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
				
			  <!--<li class="active"><a href="./adviceInfo.php">活动介绍</a></li><-->
			  <li class="active"><a href="#">活动</a></li>
			  <li class=""><a data-ajax="false" href="./advice.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">我要建言</a></li>
              <li class=""><a data-ajax="false" href="./adviceShow.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">最新建言</a></li>
			  <li class=""><a data-ajax="false" href="./adviceScratchcard.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">抽奖</a></li>

			</ul>
		</nav>
	</div>
	<div id="contents" style="background-color: #FFFFFF; box-shadow: 0 1px 6px rgba(124, 124, 124, 0.42);">
		<div id="news_main_img">
			<img src="img/adviceMain2.jpg" />
		</div>
    </div>    
<style>
    .one_group { margin: 0; padding: 0;}
    .one_group label { color: #008000}
    .one_group p{ 
        text-indent: 2em; /*em是相对单位，2em即现在一个字大小的两倍*/ 
        margin: 0;
        padding: 0;
    } 
    .one_group .highlight {color: #008000 }
</style>
<div id="wl_news_detail" style="height: 300px;">
	<div style="margin-left: auto; margin-right: auto; text-align: left;">
        <span style="color: red; font-size: 20px; display:inline-block">&nbsp &nbsp &nbsp &nbsp1月14日-15日，路桥区将隆重召开中共台州市路桥区第五届代表大会第五次会议！为更好地倾听民意、集中民智，“路桥发布”特推出“喜迎党代会、五年话愿景”网络互动平台。我们诚挚邀请广大网友积极建言献策，对“十三五”期间路桥的新一轮发展提出自己的愿景和期许。</span>
        <!--<span style="color: red; font-size: 20px; display:inline-block">&nbsp &nbsp &nbsp &nbsp为更好地倾听民意、集中民智，“路桥发布”开通“路桥两会我有话说”网络平台，邀请广大网友为“两会”建言献策，助力路桥发展。“两会”闭幕后，“路桥发布”编辑部将开展“金点子”评选活动。奖项设置一等奖1个，二、三等奖若干名，分别给予IPADmini3，IPODshuffle和价值100元的手机充值卡奖励。</span>-->
	</div>
</div>

</body>
</html>