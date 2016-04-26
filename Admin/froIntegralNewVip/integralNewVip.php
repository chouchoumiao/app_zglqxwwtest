<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新追加会员初始化设置</title>

<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

    $weixinID = $_SESSION['weixinID'];

    //取得Config信息
    $config =  getConfigWithMMC($weixinID);
    if($config == '' || empty($config)){
        $integralNewInsert = "尚未设置";
        $integralReferrerForNewVip = "尚未设置";
        $integralReferrer = "尚未设置";
        $weixinName = '积分';
    }else{
        $integralNewInsert = $config['CONFIG_INTEGRALINSERT'];
        $integralReferrerForNewVip = $config['CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP'];
        $integralReferrer = $config['CONFIG_INTEGRALREFERRER'];
        $weixinName = $config['CONFIG_VIP_NAME'];
    }
?>
<div>
<form action="?" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<fieldset>
    <div id = "mainInfo">
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-7">
            <p><h3><span class="label label-info">本&nbsp画&nbsp面&nbsp为&nbsp新&nbsp会&nbsp员&nbsp绑&nbsp定&nbsp时，&nbsp各&nbsp种&nbsp初&nbsp始&nbsp化&nbsp设&nbsp置</span></h3></p></br>
            <span>注意：三项必须输入至少一项，不输入的项目表示不改变</span>
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label class="col-sm-5 control-label" for="integralNewInsert">新绑定会员可获得<?php echo $weixinName;?>数：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "当前状态：<?php echo $integralNewInsert;?> <?php echo $weixinName;?>！" type="text" id = "integralNewInsert"></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label" for="integralReferrerForNewVip">存在推荐人时，新会员可获得额外<?php echo $weixinName;?>数：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "当前状态：<?php echo $integralReferrerForNewVip;?> <?php echo $weixinName;?>！" type="text" id = "integralReferrerForNewVip"></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label" for="integralReferrer">存在推荐人时，推荐人可获得额外<?php echo $weixinName;?>数：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "当前状态：<?php echo $integralReferrer;?> <?php echo $weixinName;?>！" type="text" id = "integralReferrer"></br>
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

            var integralNewInsert = $('#integralNewInsert').val();
            var integralReferrerForNewVip = $('#integralReferrerForNewVip').val();
            var integralReferrer = $('#integralReferrer').val();
            var msg = "";

            if((isNull(integralNewInsert))&&(isNull(integralReferrerForNewVip))&&(isNull(integralReferrer))){
                msg = "三项不能都为空，不然设置就木有意义啦！";
            }else if(!isNull(integralNewInsert)){
                if(!isNumber(integralNewInsert)){
                    msg = "【新绑定会员可获得<?php echo $weixinName;?>数】只能为数字";
                }else  if(integralNewInsert<0 || integralNewInsert>999){
                    msg = "【新绑定会员可获得<?php echo $weixinName;?>数】只能为1到999之间的整数";
                }
            }else if(!isNull(integralReferrerForNewVip)){
                if(!isNumber(integralReferrerForNewVip)){
                    msg = "【新会员可获得额外<?php echo $weixinName;?>数】只能为数字";
                }else  if(integralReferrerForNewVip<0 || integralReferrerForNewVip>999){
                    msg = "【新会员可获得额外<?php echo $weixinName;?>数】只能为1到999之间的整数";
                }
            }else if(!isNull(integralReferrer)){
                if(!isNumber(integralReferrer)){
                    msg = "【推荐人可获得额外<?php echo $weixinName;?>数】只能为数字";
                }else  if(integralReferrer<0 || integralReferrer>999){
                    msg = "【推荐人可获得额外<?php echo $weixinName;?>数】只能为1到999之间的整数";
                }
            }
            if(msg != ""){
                $('#myMsg').html(msg);
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }

            $.ajax({
                url:'integralNewVipData.php'//改为你的动态页
                ,type:"POST"
                ,data:{"integralNewInsert":integralNewInsert,"integralReferrerForNewVip":integralReferrerForNewVip,"integralReferrer":integralReferrer}
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