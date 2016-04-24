<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<head>
<title>五水共治“红黑榜”线索征集</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
<link type="text/css" href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="../css/bbsPhotoWallAdvice/common.css" rel="stylesheet">

<style>  
    div.ui-slider-switch { width: 4em }
    textarea.ui-input-text { min-height: 130px;  }
    .divLeft
    {  
        float:left;
    }  
    .divRight  
    {  
        float:right;  
    }
</style> 
</head>
<script>
       window.onload = function () { 
            new uploadPreview({ UpBtn: "up_img1", DivShow: "imgdiv1", ImgShow: "imgShow1" });
            new uploadPreview({ UpBtn: "up_img2", DivShow: "imgdiv2", ImgShow: "imgShow2" });
            new uploadPreview({ UpBtn: "up_img3", DivShow: "imgdiv3", ImgShow: "imgShow3" });
            new uploadPreview({ UpBtn: "up_img4", DivShow: "imgdiv4", ImgShow: "imgShow4" });
        }
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

?>
<body>
<div id = "navBBS">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; min-height: 0;">
        <ul class="nav nav-pills" style="display: table; width:auto;margin-left: auto;margin-right: auto;">
            <li class=""><a data-ajax="false" href="bbsMain.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">活动介绍</a></li>
            <li class=""><a data-ajax="false" href="./bbsShowGood.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">红榜</a></li>
            <li class=""><a data-ajax="false" href="./bbsShowBad.php?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>">黑榜</a></li>
            <li class="active"><a href="#">我要建言</a></li>
        </ul>
    </nav>
</div>
<div data-role="page" data-theme="c">
<div data-role="content"> 
    <form name="customersUpdateForm"  id = "formSubmit" method="POST" action="bbsData.php?weixinID=<?php echo $weixinID?>" data-ajax="false" data-transition="flip" enctype="multipart/form-data">
        <div>  
            </br></br>
            <label for="textinputName">姓名/昵称 :</label>
            <input id = "textinputName" type="text" name="textinputName" placeholder = "请输入您的姓名或者昵称" size="10">
            
        </div>
        <div>  
            <label for="textinputTel">联系方式(可选) :</label>
            <input id = "textinputTel" type="text" name="textinputTel" size="20" placeholder = "请输入您的联系方式">
        </div>
        <div  data-role="fieldcontain">  
            <label for="textinputAdvice">您的线索 :</label>
            <textarea id = "textinputAdvice" name="textinputAdvice" placeholder = "请输入您的线索！"></textarea>
        </div>
        <div>
        <div>
            <div class = "divLeft" >
                <label for="up_img1">上传图(最多4张)</label>
                <input type="file" id="up_img1" name="up_img1" style = "display:none" accept="image/*"/>
                <div id="imgdiv1">
                    <img id="imgShow1" src="../../Static/img/upload.jpg" class="img-rounded" width="130" height = "150"/>
                </div>
            </div>
            <div class = "divRight">
                <label for="up_img2">上传图(最多4张)</label>
                <input type="file" id="up_img2" name="up_img2" style = "display:none" accept="image/*"/>
                <div id="imgdiv2">
                    <img id="imgShow2" src="../../Static/img/upload.jpg" class="img-rounded" width="130" height = "150"/>
                </div>
            </div>  
        </div>
        <div>
            <div class = "divLeft">
                <label for="up_img3">上传图(最多4张)</label>
                <input type="file" id="up_img3" name="up_img3" style = "display:none" accept="image/*"/>
                <div id="imgdiv3">
                    <img id="imgShow3" src="../../Static/img/upload.jpg" class="img-rounded" width="130" height = "150"/>
                </div>
            </div>
            <div class = "divRight">
                <label for="up_img4">上传图(最多4张)</label>
                <input type="file" id="up_img4" name="up_img4" style = "display:none" accept="image/*"/>
                <div id="imgdiv4">
                    <img id="imgShow4" src="../../Static/img/upload.jpg" class="img-rounded" width="130" height = "150"/>
                </div>
            </div>
        </div>
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <div>
            <tr valign="top" height="10">
                <td> <input type="hidden" name="thisVip_openidField" size="20" value="<?php echo $openid;?>" >  </td>
                <td> <input type="hidden" name="thisVip_weixinID" size="20" value="<?php echo $weixinID;?>" >  </td>
            </tr>
        </div>
        <div  id ="submitDiv">
            <p align="center"> 
              <input type="submit" id = "submitBtn" name="submitUpdateCustomersForm" value="提交" onclick="return CheckForm();" >
            </p>
        </div>  
        <div id = "resetDiv">
            <p align="center"> 
              <input type="reset" name="resetForm" id="resetForm" value="重填">
            </p>
        </div>
    </form>
    </br>
    <div id= "doingDiv" style = "display:none">
        <button type="button" name="doingBtn" id="doingBtn" >正在提交,请稍等...</button>
    </div>    

</div>
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="../../Static/JS/uploadPreview.js" type="text/javascript"></script>

<script type="text/javascript">
    NoShowRightBtn();
    $(function(){
        $('#imgShow1').click(function(){
            $('#up_img1').click();
        });
        $('#imgShow2').click(function(){
            $('#up_img2').click();
        });
        $('#imgShow3').click(function(){
            $('#up_img3').click();
        });
        $('#imgShow4').click(function(){
            $('#up_img4').click();
        })
    });
    function CheckForm(){
        
        var thisName = document.customersUpdateForm.textinputName;
        var thisTel = document.customersUpdateForm.textinputTel;
        var thisReferrer = document.customersUpdateForm.textinputAdvice;
        var thisImg1 = document.customersUpdateForm.up_img1;
        var thisImg2 = document.customersUpdateForm.up_img2;
        var thisImg3 = document.customersUpdateForm.up_img3;
        var thisImg4 = document.customersUpdateForm.up_img4;
        
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
        if(isNull(thisReferrer.value)){
            alert("建言内容不能为空哟！");
            thisReferrer.focus();
            return false;
        }
        if((isNull(thisImg1.value)) && (isNull(thisImg2.value)) &&(isNull(thisImg3.value)) && (isNull(thisImg4.value))){
            alert("至少选择一张图片哟！");
            return false;
        }
        //$('#submitDiv').hide();
        //$('#resetDiv').hide();
        $('#navBBS').hide();
        $('#formSubmit').hide();
        $('#doingDiv').show();
    }
</script>
</body>
</html>
