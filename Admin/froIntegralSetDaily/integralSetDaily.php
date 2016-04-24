<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>每日签到设置</title>

<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

    $weixinID = $_SESSION['weixinID'];

    //取得Config信息
    $config =  getConfigWithMMC($weixinID);
    if($config == '' || empty($config)){
        $IntegralDaily = "尚未设置";
        $IntegralDailyCode = "尚未设置";
    }else{
        $IntegralDaily = $config['CONFIG_INTEGRALSETDAILY'];
        $IntegralDailyCode = $config['CONFIG_DAILYPLUS'];
    }
    $config = getConfigWithMMC($weixinID);
    //判断基础信息是否取得成功
    if($config == '' || empty($config)){
        echo "取得配置信息失败，请确认！";
        exit;
    }
    $weixinName = $config['CONFIG_VIP_NAME'];
    
?>
<div>
<form action="?" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<fieldset>
    <div id = "mainInfo">
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-3">
            <p><h3><span class="label label-info">本画面为每日签到时，能获得的<?php echo $weixinName;?>设置</span></h3></p></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="integralSet">每日签到<?php echo $weixinName;?>：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "当前状态：<?php echo $IntegralDaily;?> <?php echo $weixinName;?>！" type="text" name="integralSet" id = "integralSet"></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="integralDailyCode">签到码签到<?php echo $weixinName;?>：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "当前状态：<?php echo $IntegralDailyCode;?> <?php echo $weixinName;?>！" type="text" name="integralDailyCode" id = "integralDailyCode"></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <button type="button" class="btn btn-success"  id = "OKBtn">点击进行设置</button>
            </div>
        </div>
    </div>    
    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-4">
            <div id="myMsg" class="alert alert-warning" style = "display:none"></div>
            <div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
        </div>
	</div>
	
	</fieldset>
</form>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>

<script type="text/javascript">
    $(function(){
        $('#OKBtn').click(function(){

            var thisIntegral = $('#integralSet').val();
            var dailyCodeIntegral = $('#integralDailyCode').val();
            var msg = "";

            if(isNull(thisIntegral)){
                msg = "【每日签到<?php echo $weixinName;?>】不能为空";
            }else if(!isNumber(thisIntegral)){
                msg = "【每日签到<?php echo $weixinName;?>】只能为数字";
            }else if(thisIntegral<0 || thisIntegral>999){
                msg = "【每日签到<?php echo $weixinName;?>】只能为1到999之间的整数";
            }

            if(msg != ""){
                $('#myMsg').html(msg);
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }

            if(isNull(dailyCodeIntegral)){
                msg = "【签到码签到<?php echo $weixinName;?>】不能为空";
            }else if(!isNumber(dailyCodeIntegral)){
                msg = "【签到码签到<?php echo $weixinName;?>】只能为数字";
            }else if(dailyCodeIntegral<0 || dailyCodeIntegral>999){
                msg = "【签到码签到<?php echo $weixinName;?>】只能为1到999之间的整数";
            }

            if(msg != ""){
                $('#myMsg').html(msg);
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }

            $.ajax({
                url:'integralSetDailyData.php'//改为你的动态页
                ,type:"POST"
                ,data:{"thisIntegral":thisIntegral,
                    "dailyCodeIntegral":dailyCodeIntegral
                }
                ,dataType: "json"
                ,success:function(json){
                    if(json.success == "OK"){

                        $('#mainInfo').hide();
                        $('#myOKMsg').html(json.msg);
                        $('#myOKMsg').show();
                    }else{
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });

        })
    });
</script>
</body>
</html>