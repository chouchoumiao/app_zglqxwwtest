<html>
<head>
<title>“建言献策”活动</title>    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
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

<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="http://res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2341cc.css">
<![endif]-->
<body id="activity-detail" class="zh_CN ">      
    <div id="head">
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
			<ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
				
			  <!--<li class="active"><a href="./adviceInfo.php">活动介绍</a></li><-->
			  <li class=""><a data-ajax="false" href="adviceMain.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">活动</a></li>
              <li class=""><a data-ajax="false" href="./advice.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">我要建言</a></li>
			  <li class="active"><a href="#">最新建言</a></li>
              <li class=""><a data-ajax="false" href="./adviceScratchcard.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">抽奖</a></li>
            </ul>
		</nav>
	</div>           
<blockquote style="border-width: 0px 0px 0px 10px; font: 14px/25px arial, helvetica, sans-serif; margin: 5px 0px 0px; padding: 10px; border-radius: 4px; text-align: left; color: rgb(255, 255, 255); text-transform: none; text-indent: 0px; letter-spacing: normal; word-spacing: 0px; border-left-color: rgb(255, 0, 17); border-left-style: solid; white-space: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important; font-size-adjust: none; font-stretch: normal; box-shadow: 2px 2px 4px rgb(153,153,153); text-shadow: 0px 1px 0px rgb(34,95,135); background-color: rgb(55, 57, 57); -webkit-text-stroke-width: 0px;" data-mce-style="margin: 5px 0px 0px; padding: 10px; max-width: 100%; orphans: 2; widows: 2; line-height: 25px; font-family: arial, helvetica, sans-serif; text-shadow: #225f87 0px 1px 0px; color: #ffffff; border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; box-shadow: #999999 2px 2px 4px; border-left-width: 10px; border-left-style: solid; border-left-color: #fdd000; background-color: #373939; word-wrap: break-word !important;">
    <span style="margin: 0px; padding: 0px; font-style: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;"></span>
    <span style="margin: 0px; padding: 0px; font-size: 18px; font-style: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
    <strong style="margin: 0px; padding: 0px; font-style: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
        <span style="margin: 0px; padding: 0px; font-style: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">针对各自关心的热点问题，来看网民们都怎么说。
    </span>
    </strong>
    </span>
</blockquote>
</br>
<?php 
    
    //$isOKFlag = 1;
    $sql = "select * from adviceInfo
            where ADVICE_ISOK IN (1,3)
            AND WEIXIN_ID = $weixinID
            order by ADVICE_EDITETIME DESC";
    //echo $sql;
    //exit;
    $adviceInfoIsOK = getDataBySql($sql);
    $adviceInfoIsOKCount = count($adviceInfoIsOK);
    if($adviceInfoIsOKCount == 0){
    ?>
    <fieldset style="padding: 0px; border: 0px currentColor; border-image: none; color: rgb(62, 62, 62); line-height: 25px; font-family: 微软雅黑; margin-left: 10px; white-space: normal; max-width: 100%; box-sizing: border-box !important; background-color: rgb(255, 255, 255);">
            </br>
            <section class="main2" style="border: 1px solid rgb(0, 187, 236); border-image: none; font-size: 1em; margin-top: -1.5em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                <section style="padding: 1.4em 1em 1em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                    <span style="text-align: inherit; color: rgb(253, 176, 0); line-height: 1.2; font-family: inherit; font-size: 1em; text-decoration: inherit; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;"></span>
                    <span style="text-align: inherit; color: rgb(51, 51, 51); line-height: 1.2; font-family: inherit; font-size: 1em; text-decoration: inherit; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">尚未有网友建言！</span>
                </section>
            </section>
        </fieldset>
    <?php
    }
    for ($i = 0;$i<$adviceInfoIsOKCount;$i++) {
?>
        <fieldset style="padding: 0px; border: 0px currentColor; border-image: none; color: rgb(62, 62, 62); line-height: 25px; font-family: 微软雅黑; margin-left: 10px; white-space: normal; max-width: 100%; box-sizing: border-box !important; background-color: rgb(255, 255, 255);">
            <section style="line-height: 1.4em; margin-left: -0.5em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                <section class="main" style="padding: 0.2em 0.5em; border-radius: 0.3em; text-align: center; color: white; font-size: 0.8em; display: inline-block; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important; background-color: rgb(0, 187, 236); -webkit-transform: rotateZ(-10deg); -webkit-transform-origin: 0% 100%;">
                    网友昵称：<?php echo $adviceInfoIsOK[$i]['ADVICE_NAME'];?>
                </section>
            </section>
            <section class="main2" style="border: 1px solid rgb(0, 187, 236); border-image: none; font-size: 1em; margin-top: -1.5em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                <section style="padding: 1.4em 1em 1em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                    <span style="text-align: inherit; color: rgb(253, 176, 0); line-height: 1.2; font-family: inherit; font-size: 1em; text-decoration: inherit; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;"></span>
                    <span style="text-align: inherit; color: rgb(51, 51, 51); line-height: 1.2; font-family: inherit; font-size: 1em; text-decoration: inherit; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;"><?php echo $adviceInfoIsOK[$i]['ADVICE_ADVICE'];?></span>
                </section>
            </section>
            </br>
        <?php 
        if(trim($adviceInfoIsOK[$i]['ADVICE_REPLY']) != ""){
        ?>
                <section class="main2" style="border: 1px solid rgb(255, 0, 0); border-image: none; font-size: 1em; margin-top: -1.5em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                    <section style="padding: 1em 1em 1em; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">
                        <span style="text-align: inherit; color: rgb(253, 176, 0); line-height: 1.2; font-family: inherit; font-size: 1em; text-decoration: inherit; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">回复：</span>
                        <span style="text-align: inherit; color: rgb(51, 51, 51); line-height: 1.2; font-family: inherit; font-size: 1em; text-decoration: inherit; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;"><?php echo $adviceInfoIsOK[$i]['ADVICE_REPLY'];?></span>
                    </section>
                </section>
        <?php
        }
        ?>
            </fieldset>
        </br>
<?php
    }
?>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
</body>
</html>