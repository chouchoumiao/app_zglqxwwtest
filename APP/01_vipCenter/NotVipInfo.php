<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<link href="./css/style.css" rel="stylesheet" type="text/css">
<title>提示</title>
</head>

<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET['openid']);
$weixinID = addslashes($_GET['weixinID']);

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
<body>
    <div data-role="page">  
        <div data-role="content"> 
            <div>
                <label for="textinputViped"><br>您还不是会员，绑定会员，赚取<?php echo $weixinName ;?></br>进行互动游戏，更有机会获大奖</label><br><br>
                <p><a href='VipBD.php?openid=<?php echo $openid ?>&weixinID=<?php echo $weixinID;?>' data-role="button" data-transition="flip" data-ajax="false" >进入会员绑定画面</a></p>
            </div>
        </div>
    </div>
</body>
</html>
