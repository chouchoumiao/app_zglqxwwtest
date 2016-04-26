<!DOCTYPE html> 
<html>
<head>
<title>照片墙</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
<link type="text/css" href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

<style>  
    div.ui-slider-switch { width: 4em }
    textarea.ui-input-text { min-height: 130px;  }
</style> 

<style type="text/css">
body{ font-size:14px;}
input{ vertical-align:middle; margin:0; padding:0}
.file-box{ position:relative;width:340px}
.txt{ height:22px; border:1px solid #cdcdcd; width:180px;}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:20px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;}
</style>
</head>
<script>
       window.onload = function () { 
            new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
        }
</script>
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
<body>
<div>
    <div id="head">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
            <ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
                
              <!--<li class="active"><a href="./bbsInfo.php">活动介绍</a></li><-->
              <li class=""><a data-ajax="false" href="photoWallMain.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">活动介绍</a></li>
              <li class=""><a data-ajax="false" href="photoWallShow.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">照片墙</a></li>
              <li class="active"><a href="#">我要参加</a></li>
              
            </ul>
        </nav>
    </div>
</div>
<div data-role="page" data-theme="c">
<div data-role="content"> 
    <form name="customersUpdateForm" method="POST" action="photoWallData.php?weixinID=<?php echo $weixinID?>" data-ajax="false" data-transition="flip" enctype="multipart/form-data">
        <div>  
            </br></br>
            <label for="textinputName">姓名/昵称 :</label>
            <input id = "textinputName" type="text" name="textinputName" placeholder = "请输入您的姓名或者昵称" size="10">
            
        </div>
        <div>  
            <label for="textinputTel">联系方式(可选) :</label>
            <input id = "textinputTel" type="text" name="textinputTel" size="20" placeholder = "请输入您的联系方式">
        </div>
        <div>
            <label for="up_img">上传图片 :</label>
            <input type="file" id="up_img" name="up_img" style = "display:none" accept="image/*"/>
            <div id="imgdiv">
                <img id="imgShow" src="../../Static/IMG/upload.jpg" class="img-rounded" width="150"/>
            </div>
        </div>
    
        <br>    
        <div>
            <tr valign="top" height="10">
                <td> <input type="hidden" name="thisVip_openidField" size="20" value="<?php echo $openid;?>" >  </td> 
            </tr>
        </div>
        <div  id ="submitDiv">
            <p align="center"> 
              <input type="submit" id = "submitBtn" name="submitUpdateCustomersForm" value="提交" onclick="return CheckForm();" >
            </p>
        </div>
        <div id= "doingDiv" style = "display:none">
            <button type="button" name="doingBtn" id="doingBtn" >正在提交,请稍等...</button>
        </div>    
        <div id = "resetDiv">
            <p align="center"> 
              <input type="reset" name="resetForm" id="resetForm" value="重填">
            </p>
        </div>
    </form>
</div>  
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="../../Static/JS/uploadPreview.js" type="text/javascript"></script>

<script type="text/javascript">
    NoShowRightBtn();
    $(function(){
        $('#imgShow').click(function(){
            $('#up_img').click();
        })
    });
    function CheckForm(){
        
        var thisName = document.customersUpdateForm.textinputName;
        var thisTel = document.customersUpdateForm.textinputTel;
        var thisImg = document.customersUpdateForm.up_img;

        if(isNull(thisName.value)){
            alert("姓名不能为空");
            thisName.focus();
            return false;
        }else{
            if(!isChinaOrNumbOrLett(thisName.value)){
                alert("姓名只能是汉族，字母，数字组成");
                thisName.focus();
                return false;
            }
        }	
        if(!isNull(thisTel.value)){
            if (checkMobile(thisTel.value) == false){
                alert("手机号码格式不正确");
                thisTel.focus();
                return false;
            }  
        }
        if(isNull(thisImg.value)){
            alert("尚未选择图片！");
            thisImg.focus();
            return false;
        }
        $('#submitDiv').hide();
        $('#resetDiv').hide();
        $('#doingDiv').show();
    }
</script>

</body>
</html>
