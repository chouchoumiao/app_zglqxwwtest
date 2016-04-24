<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/imgEnlarge.css?v=20150519" type="text/css" rel="stylesheet" />

</HEAD>
<body>
     
<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

//获取问题ID号传入
$str=addslashes($_GET["str"]);

$getArr = explode(',',$str);

$id = $getArr[0];
$page = $getArr[1];
$name = $getArr[2];
$tel = $getArr[3];
$imgUrl = $getArr[4];
$bigImgUrl = $getArr[5];
$integral = $getArr[6];
$reply = $getArr[7];
$creatTime = $getArr[8];
$openid = $getArr[9];
//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($id)
{
    $sql = "select * from forwardingGift where id = $id AND WEIXIN_ID = $weixinID";
    $forwardingGiftInfoArr = getlineBySql($sql);
	if(!$forwardingGiftInfoArr)
	{
		echo "<script>alert('无此信息！');history.back();</Script>";
		exit;
	}
}

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];
?>
<!--页面名称-->
<h3 id = "titel">修改/审核<a href="forwardingGiftInfoSearch.php?page=<?php echo $page;?>">返回>></a></h3>
<!--表单开始-->
<div id = "main_set">
    <form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
        <fieldset>
        <div id = "Forminfo">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="forwardingGift_Name">姓名/昵称：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $name;?>" name="forwardingGift_Name" id = "forwardingGift_Name" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="forwardingGift_tel">联系方式：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $tel;?>" name="forwardingGift_tel" id = "forwardingGift_tel" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="forwardingGiftImg">上传图片预览：</label>
                <div class="col-sm-5">
                    <a id="forwardingGift_imgUrl" href=<?php echo $bigImgUrl;?>><img id="forwardingGiftImg" src = <?php echo $imgUrl;?>  width="200" /></a>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="forwardingGift_createTime">上传时间：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $creatTime;?>" name="forwardingGift_createTime" id = "forwardingGift_createTime" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="forwardingGift_Reply">回复内容：</label>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="5" placeholder = "" type="text" name="forwardingGift_Reply" id = "forwardingGift_Reply"><?php echo $reply;?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"  for="add_integral">追加<?php echo $weixinName;?>：</label>
                <div class="col-sm-5">
                <select class="form-control" name="add_integral" id = "add_integral">
                    <option value="0" selected>0</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-5">
                    <input type="hidden" id="forwardingGiftID" value="<?php $id;?>"></br>
                    <button type="button" class="btn btn-danger btn-block"  id = "formSubmitNG">审核不通过(不加<?php echo $weixinName;?>)</button><br>
                    <button type="button" class="btn btn-success btn-block"  id = "formSubmit">审核通过（加<?php echo $weixinName;?>）</button>
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
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/jquery.imgbox.pack.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
$(function(){
    $('#formSubmitNG').click(function(){
        var reply = $('#forwardingGift_Reply').val();
        var integral = $('#add_integral').val();
    
        $.ajax({
			url:"forwardingGiftSetData.php?action=NG&id=<?php echo $id?>&openid=<?php echo $openid?>"//改为你的动态页
			,type:"POST"
			,data:{"reply":reply,
                   "integral":integral
            }//调用json.js类库将json对象转换为对应的JSON结构字符串
			,dataType: "json"
			,success:function(json){
                alert(json.msg);
                $('#titel').hide();
                $('#Forminfo').hide();
                if(json.success == 1){
                    $('#myOKMsg').html(json.msg);
                    $('#myOKMsg').show();
                }else if (json.success == -1){
                    $('#myMsg').html(json.msg);
                    $('#myMsg').show();
                }
                
			} 
			,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
		});
	});
    
	$('#formSubmit').click(function(){
        var reply = $('#forwardingGift_Reply').val();
        var integral = $('#add_integral').val();
    
		$.ajax({
			url:"forwardingGiftSetData.php?action=OK&id=<?php echo $id?>&openid=<?php echo $openid?>"//改为你的动态页
			,type:"POST"
			,data:{"reply":reply,
                   "integral":integral
            }//调用json.js类库将json对象转换为对应的JSON结构字符串
			,dataType: "json"
			,success:function(json){
                
                $('#titel').hide();
                $('#Forminfo').hide();
                if(json.success == 1){
                    $('#myOKMsg').html(json.msg);
                    $('#myOKMsg').show();
                }else if (json.success == -1){
                    $('#myMsg').html(json.msg);
                    $('#myMsg').show();
                }
                
			} 
			,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
		});
	});
    //图片点击放大JS
    $("#forwardingGift_imgUrl").imgbox({
        'speedIn'		: 0,
        'speedOut'		: 0,
        'alignment'		: 'center',
        'overlayShow'	: true,
        'allowMultiple'	: false
    });
});
</script>
</body>
</html>
