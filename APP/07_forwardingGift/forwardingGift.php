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

$config =  getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

isVipByOpenid($openid,$weixinID,"07_forwardingGift/forwardingGift.php");

$sql = "select * from forwardingGift
        where FORWARDINGGIFT_OPENID = '$openid'
        AND WEIXIN_ID = $weixinID
        AND FORWARDINGGIFT_ISOK = 1";
$OKInfo = getDataBySql($sql);

$sql = "select * from forwardingGift
        where FORWARDINGGIFT_OPENID = '$openid'
        AND WEIXIN_ID = $weixinID
        AND FORWARDINGGIFT_ISOK = 2";
$NGInfo = getDataBySql($sql);


?>
<!DOCTYPE html>
<html>
<head>
<title>路桥发布个人中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12" id = "main">
            <div id = "baseInfo">
                <h5>分享有礼</h5>

                <form name="customersUpdateForm" method="POST" action="forwardingGiftData.php?action=submit" enctype="multipart/form-data">
                    <div class="alert alert-info" id = "baseTitle">
                        <p class="text-primary">将”路桥发布“最新一期任何一篇文章进行分享，并截图上传，待审核后，每日最多送20个<?php echo $weixinName;?>。</p>
                    </div>
                    <div class="form-group">
                        <label for="up_img">点击下方图标进行照片上传 :</label>
                        <input type="file" id="up_img" name="up_img" style = "display:none" accept="image/*"/>
                        <div id="imgdiv">
                            <img id="imgShow" src="../../Static/IMG/upload.jpg" class="img-rounded" width="150"/>
                        </div>
                        <p class="text-danger"><small>支持jpg,png，请上传小于5M的图片</small></p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block" id = "referrerSubmit" onclick = "return CheckForm();">提交</button>
                    </div>
                    <div class="form-group" id= "doingDiv" style = "display:none">
                        <button type="button" class="btn btn-block disabled" id = "referrerSubmitDoing">正在提交。。。</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-warning btn-block" id = "resultSearch">查询结果</button>
                    </div>
                    <div>
                        <input type="hidden" name="openid" size="20" value="<?php echo $openid;?>" >
                        <input type="hidden" name="weixinID" size="20" value="<?php echo $weixinID;?>" >
                        <input type="hidden" name="action" size="20" value="submit" >
                    </div>
                 </form>
            </div>
            <div id = "searchInfo" style = "display:none">
                <br>
                <a class="btn btn-warning btn-block" role="button" data-toggle="collapse" data-target="#OK" aria-expanded="false" aria-controls="collapseExample">
                   点击查看审核通过内容
                </a>
                <div class="collapse" id="OK">
                    <?php
                    $OKInfoCount = count($OKInfo);
                    if($OKInfoCount <= 0){
                    ?>
                        <div class="well well-sm">
                            <span class="text-warning"><small>没有数据</small></span>
                        </div>
                    <?php
                    }else{
                        for($i = 0; $i<$OKInfoCount; $i++){
                        ?>
                            <div class="well">
                                <span class="text-warning"><small>提交时间：<?php echo $OKInfo[$i]['FORWARDINGGIFT_CREATETIME']?></small></span><br>
                                <span class="text-warning"><small>审核时间：<?php echo $OKInfo[$i]['FORWARDINGGIFT_EDITETIME']?></small></span><br>
                                <span class="text-warning"><small>获得<?php echo $weixinName;?>：<?php echo $OKInfo[$i]['FORWARDINGGIFT_INTEGRAL']?></small></span>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>
                <br>
                <a class="btn btn-danger btn-block" role="button" data-toggle="collapse" data-target="#NG" aria-expanded="false" aria-controls="collapseExample">
                   点击查看审核未通过内容
                </a>
                <div class="collapse" id="NG">
                    <?php
                    $NGInfoCount= count($NGInfo);
                    if($NGInfoCount <= 0){
                    ?>
                        <div class="well well-sm">
                            <span class="text-danger"><small>没有数据</small></span><br>
                        </div>
                    <?php
                    }else{
                        for($i = 0; $i<$NGInfoCount; $i++){
                        ?>
                            <div class="well">
                                <span class="text-danger"><small>提交时间：<?php echo $NGInfo[$i]['FORWARDINGGIFT_CREATETIME']?></small></span><br>
                                <span class="text-danger"><small>审核时间：<?php echo $NGInfo[$i]['FORWARDINGGIFT_EDITETIME']?></small></span><br>
                                <span class="text-danger"><small>不通过原因：<?php echo $NGInfo[$i]['FORWARDINGGIFT_REPLY']?></small></span>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="myMsg" class="alert alert-warning" style = "display:none"></div>
            <br>
            <div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript" src="../../Static/JS/uploadPreview.js" ></script>
<script>
    window.onload = function () {
        new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
    };
    NoShowRightBtn();

    $(function(){
        $('#imgShow').click(function(){
            $('#up_img').click();
        });
        $("#resultSearch").click(function(){
            $.ajax({
                url:"forwardingGiftData.php?action=resultSearch&openid=<?php echo $openid?>&weixinID=<?php echo $weixinID?>"//改为你的动态页
                ,type:"POST"
                ,data:{}//调用json.js类库将json对象转换为对应的JSON结构字符串
                ,dataType: "json"
                ,success:function(json){

                    if(json.success == 1){

                        $("#baseInfo").hide();
                        $("#searchInfo").show();
                        //$('#myOKMsg').html(json.msg);
                        //$('#myOKMsg').show();
                    }else if (json.success == -1){
                        $('#resultSearch').hide();
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide();$('#resultSearch').show();",2000);

                    }

                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        })
    });
    function CheckForm(){
        var thisImg = document.customersUpdateForm.up_img;

        if(isNull(thisImg.value)){
            alert("尚未选择图片！");
            thisImg.focus();
            return false;
        }
        $('#referrerSubmit').hide();
        $('#resultSearch').hide();
        $('#doingDiv').show();
    }
</script>
</body>
</html>

