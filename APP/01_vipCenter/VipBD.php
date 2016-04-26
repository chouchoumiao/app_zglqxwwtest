<!DOCTYPE html> 
<html>
<head>
<title>会员绑定</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">
</head>
<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);
//$refferVipID =  addslashes($_GET["vip_card"]);
////追加推荐人分享功能
//if ($refferVipID && ){
//
//};

$config =  getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}

$weixinName = $config['CONFIG_VIP_NAME'];

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");
$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

$url = addslashes($_GET["url"]);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/APP/'.$url;

?>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12" id = "main">
            <div id = "baseInfo">
                <div class="alert alert-info" id = "baseTitle">
                    <p class="text-primary">注册“路桥发布”会员，参与每日签到、邀请好友、转发有礼等活动可获得“<?php echo $weixinName;?>”，可兑换，可抽奖，好礼多多！</p>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">昵称</span>
                        <input type="text" class="form-control input-lg" placeholder = "请输入您的姓名或者昵称" id = "textinputName">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input type="text" class="form-control input-lg" placeholder = "兑奖时需出示" id = "textinputTel">
                    </div>
                </div>
                <div class="form-group">
                    <div align="center">
                        <input type="checkbox" checked data-toggle="switch" data-on-color="info" data-off-color="danger" data-on-text="男" data-off-text="女" id="custom-switch-08" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">推荐人卡号</span>
                        <input type="text" class="form-control input-lg" placeholder = "不输默认为无推荐人" id = "thisVip_referrer">
                    </div>
                    <span>注：如果存在推荐人，请输入推荐人的会员卡号，绑定成功的话，您和推荐人都可以获得额外的<?php echo $weixinName;?>哟！</span>
                </div>
                <button type="button" class="btn btn-success btn-block" id = "referrerSubmit">提交</button>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-block disabled" id = "referrerSubmitDoing" style="display:none">正在提交。。。</button>
            </div>        
        </div>
        <div class="col-md-12">
            <div id="myMsg" class="alert alert-warning" style = "display:none"></div>
            <br>
            <div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
            <button type="button" class="btn btn-primary btn-block" id = "OKBtn" style = "display:none">点击进入</button>
        </div>    
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js"></script>
<script type="text/javascript" src="./js/application.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
    $(function(){

        var thisName = $('#textinputName');
        var thisTel = $('#textinputTel');
        var thisReferrer = $('#thisVip_referrer');
        
        var thisState = $('#custom-switch-08').bootstrapSwitch('state');
        
        $('#custom-switch-08').on('switchChange.bootstrapSwitch', function(event, state) {
            thisState = state;
        });  
        
            function CheckForm(){
            
            if(isNull(thisName.val())){
                alert("姓名不能为空");
                thisName.focus();
                return false;
            }else{
                if(!isChinaOrNumbOrLett(thisName.val())){
                    alert("姓名只能是汉族，字母，数字组成");
                    thisName.focus();
                    return false;
                }
            }
            if(isNull(thisTel.val())){
                alert("手机号码不能为空");
                thisTel.focus();
                return false;
            }else{
                if (checkMobile(thisTel.val()) == false){
                    alert("手机号码格式不正确");
                    thisTel.focus();
                    return false;
                }
            }

            if(!isNull(thisReferrer.val())){
                    if (thisReferrer.val().length != 8){
                    alert("会员卡号格式错误，请确认！");
                    thisReferrer.focus();
                    return false;
                }
            }else{
                if(!confirm("真的不需要填写推荐人么？")){
                    thisReferrer.focus();
                    return false;
                }
            }
            return true;
        }
       
        $("#referrerSubmit").click(function(){
            
            if(thisState){
                sexName = "1";
            }else{
                sexName = "0";
            }
            
            if(!CheckForm()){
                return false;
            }
            
            $.ajax({
                url:'VipBDData.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID ?>'//改为你的动态页
                ,type:"POST"
                ,data:{
                    "name":thisName.val(),
                    "tel":thisTel.val(),
                    "referrer":thisReferrer.val(),
                    "sex": sexName
                }
                ,dataType:"json"
                ,beforeSend: function(){
                    $("#referrerSubmit").hide();
                    $("#referrerSubmitDoing").show();
                }
                ,success:function(json){
                    $("#referrerSubmitDoing").hide();
                    if(json.success == 0){
                        $("#main").hide();
                        $('#myOKMsg').html(json.msg);
                        $('#myOKMsg').show();
                        $("#OKBtn").show();
                    }else{
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide();$('#referrerSubmit').show()",3000);
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
        $("#OKBtn").click(function(){
            window.location="<?php echo $url?>?openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>";
        })
    });
</script>
</body>
</html>
