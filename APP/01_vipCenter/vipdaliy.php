<!DOCTYPE html>
<html>
<head>
<title>会员签到</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET['openid']);
$weixinID = addslashes($_GET['weixinID']);

$config =  getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$integral = addslashes($_GET['integral']);
?>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12" id = "main">
            <h5>会员签到</h5>
            <div class="alert alert-info" id = "baseTitle">
                <p class="text-primary">直接签到可获得1个<?php echo $weixinName;?>，输入签到码签到可获得5个<?php echo $weixinName;?>，签到码详见路桥发布最新一期文章下方</p>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">签到码</span>
                    <input type="text" class="form-control input-lg" placeholder = "输入签到码多得<?php echo $weixinName;?>喔" id = "signIn">
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-warning btn-block" id = "signInNoCodeBtn">不输入签到码直接签到</button>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success btn-block" id = "signInWithCodeBtn">输入签到码签到</button>
            </div>
            
        </div>        
    </div>        
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script>
$(function(){
    $("#signInNoCodeBtn").click(function(){
        
        if(!confirm("签到码可在最新文章下方查询!")){
            return false;
        }       
        location.href="signData.php?openid=<?php echo $openid;?>&integral=<?php echo $integral;?>&weixinID=<?php echo $weixinID?>";
    });

    $("#signInWithCodeBtn").click(function(){
        var signidText = $("#signIn").val();
        if(isNull(signidText)){
            alert("可在“路桥发布”最近发布的某篇文章底部找到签到码");
            return false;
        }else{
            location.href="signData.php?openid=<?php echo $openid;?>&integral=<?php echo $integral;?>&weixinID=<?php echo $weixinID?>&signidText="+signidText;
        }
    })
})
</script>
</body>
</html>