<!DOCTYPE html> 
<html>
<head>
<title>建言献策</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
<link type="text/css" href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="../css/bbsPhotoWallAdvice/common.css" rel="stylesheet">

<style>  
    div.ui-slider-switch { width: 4em }
    textarea.ui-input-text { min-height: 130px;  }
</style> 
</head>
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

$sql = "select Vip_name,Vip_tel from Vip
        where Vip_openid = '$openid'
        and WEIXIN_ID = $weixinID";
$vipInfo = getlineBySql($sql);

if(!$vipInfo){
    echo '取得会员信息失败，请重新进入！';
    exit;
}
$name = $vipInfo['Vip_name'];
$tel = $vipInfo['Vip_tel'];
?>
<body>
<div>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
        <ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
            
          <!--<li class="active"><a href="./adviceInfo.php">活动介绍</a></li><-->
          <li class=""><a data-ajax="false" href="adviceMain.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">活动</a></li>
          <li class="active"><a href="#">我要建言</a></li>
          <li class=""><a data-ajax="false" href="./adviceShow.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">最新建言</a></li>
          <li class=""><a data-ajax="false" href="./adviceScratchcard.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">抽奖</a></li>
          
        </ul>
    </nav>
</div>
<div data-role="page" data-theme="c">
<div data-role="content"> 
    <form name="customersUpdateForm" method="POST" action="adviceData.php" data-ajax="false" data-transition="flip">
        <div>  
            </br></br>
            <label for="textinputName">姓名/昵称 :</label>
            <input readonly = "readonly"  id = "textinputName" type="text" name="textinputName" value= "<?php echo $name;?>" size="10">

        </div>
        <div>  
            <label for="textinputTel">联系方式 :</label>
            <input readonly = "readonly"  id = "textinputTel" type="text" name="textinputTel" size="20" value="<?php echo $tel;?>">
        </div>
        <div  data-role="fieldcontain">  
            <label for="textinputAdvice">您的建言 :</label>
            <textarea id = "textinputAdvice" name="textinputAdvice" placeholder = "请输入您的建议！"></textarea>
        </div>
        <br>
        <div>
            <tr valign="top" height="10">
                <td> <input type="hidden" name="thisVip_openidField" size="20" value="<?php echo $openid;?>" >  </td>
                <td> <input type="hidden" name="thisVip_weixinID" size="20" value="<?php echo $weixinID;?>" >  </td>
            </tr>
        </div>
        <div>
            <p align="center" id ="submitDiv"> 
              <input type="submit" name="submitUpdateCustomersForm" value="提交" onclick="return CheckForm();" >
            </p>
        </div>
        <div id= "doingDiv" style = "display:none">
            <button type="button" name="doingBtn" id="doingBtn" >正在提交,请稍等...</button>
        </div>  
        <div id = "resetDiv">
            <p align="center"> 
              <input type="reset" name="resetForm" value="重填">
            </p>
        </div>
    </form>
</div>  
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
    function CheckForm(){
        
        var thisReferrer = document.customersUpdateForm.textinputAdvice;

        if(isNull(thisReferrer.value)){
            alert("建言内容不能为空哟！");
            thisReferrer.focus();
            return false;
        }
        $('#submitDiv').hide();
        $('#resetDiv').hide();
        $('#doingDiv').show();
    }
</script>
</body>
</html>
