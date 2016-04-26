<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<style>
.divcss5{position:relative;
    left:190px;
    top:10px;
    width:70%;
    height:100%;
    border:1px solid #ccc;
    background:#ebebeb;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
}
</style>
</head>
<script>
   window.onload = function () { 
        new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
    }
</script>
<script type="text/javascript"> 

//根据是修改还是新增来显示图片
$(document).ready(function(){
    if($('#hongbao_id').val() == ""){
        $('#imgShow').hide();
        $("#imgShow").attr("src", "../../Static/img/upload.jpg");
        $('#imgShow').show();
    }
}); 

</script> 
<body>
     
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//获取问题ID号传入
$hongbaoID=intval(addslashes($_GET["hongbaoID"]));

$page=intval(addslashes($_GET["page"]));

$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$action=addslashes($_GET["action"]);

//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($action == "edit")
{
    echo "<script>showThisImg();</Script>";
    
    $sql = "select * from hongbaoInfo
            where hongbao_Status = 1
            AND hongbao_id = $hongbaoID
            AND WEIXIN_ID = $weixinID";
    $hongbaoInfoArr = getlineBySql($sql);
	if(!$hongbaoInfoArr)
	{
		echo "<script>alert('无此活动信息');history.back();</Script>";
		exit;
	}else{
        $eventReplySql = "select * from replyInfo where hongbao_id = $hongbaoID";
        $hongbaoReply = getlineBySql($eventReplySql);
        if(!$hongbaoReply){
            echo "<script>alert('未设置红包回复信息！');history.back();</Script>";
            exit;
        }
    }
}

?>
<!--页面名称-->
<h3>信息添加/修改<a href="hongbaoInfoSearch.php?page=<?php echo $page;?>">返回>></a></h3>
<!--表单开始-->
<div id = "main_set">
    <form action="hongbaoSetData.php?weixinID=<?php echo $weixinID;?>" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal shadow" role="form">
        <fieldset>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="hongbao_title">红包主题：</label>
            <div class="col-sm-5">
                <input class="form-control" placeholder = "请输入该活动的标题" type="text" value="<?php echo $hongbaoInfoArr["hongbao_title"];?>" name="hongbao_title" id = "hongbao_title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="hongbao_password">红包密码：</label>
            <div class="col-sm-5">
                <input class="form-control" placeholder = "必须是8位数字" type="text" value="<?php echo $hongbaoInfoArr["hongbao_password"];?>" name="hongbao_password" id = "hongbao_password">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="hongbao_beginTime">开始时间:</label>
            <div class="col-sm-9">
                <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                    <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $hongbaoInfoArr["hongbao_beginTime"];?>" name="hongbao_beginTime" id = "hongbao_beginTime" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="hongbao_endTime">截止时间:</label>
            <div class="col-sm-9">
                <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                    <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $hongbaoInfoArr["hongbao_endTime"];?>" name="hongbao_endTime" id = "hongbao_endTime" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        
        <div class="form-group" >
            <label class="col-sm-2 control-label" for="reply_intext">入口关键字:</label>
            <div class="col-sm-5" >
                <input class="form-control" placeholder = "添加入口内容" type="text" value="<?php echo $hongbaoReply["reply_intext"];?>" name="reply_intext" id = "reply_intext">
                <span class="label label-default">根据此处设置的关键字直接进入红包</span>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label">回复内容:</label>
        </div>
        <div class = "divcss5">
            <div class="form-group">
                <label class="col-sm-2 control-label"><h3>添加回复:</h3></label>
            </div>
            <hr style="border:0;background-color:#cccccc;height:1px;">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="reply_title">标题:</label>
                <div class="col-sm-9">
                    <input class="form-control" placeholder = "添加标题内容" type="text" value="<?php echo $hongbaoReply["reply_title"];?>" name="reply_title" id = "reply_title">
                </div>
            </div>

            <div class="form-group" id = "newImg">
                <label class="col-sm-2 control-label" for="up_img">选择封面:</label>
                <input type="file" id="up_img" name="up_img" style = "display:none" accept="image/*"/>
                <div id="imgdiv" class="col-sm-5">
                    <img id="imgShow" value = "<?php echo $hongbaoReply['reply_ImgUrl'];?>" src=<?php echo $hongbaoReply['reply_ImgUrl'];?> class="img-rounded" width="150"/>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label" for="reply_description">描述:</label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="3" placeholder = "添加描述内容" type="text" name="reply_description" id = "reply_description"><?php echo $hongbaoReply["reply_description"];?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="reply_content">正文:</label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="6" placeholder = "填写正文内容" type="text" name="reply_content" id = "reply_content"><?php echo $hongbaoReply["reply_content"];?></textarea>
                </div>
            </div>                              
        </div>
        </br>
        <div class="form-group"> 
            <label  class="col-sm-3 control-label"></label>
            <div class="col-sm-6">
                <input type="hidden" name="hongbao_id" id="hongbao_id" value="<?php echo $hongbaoInfoArr["hongbao_id"]?>">
                <button type="submit" class="btn btn-primary btn-block" id = "formSubmit" name = "formSubmit" onclick="return FormCheck();">提 &nbsp;&nbsp; 交</button>
            </div>
        </div>
        </fieldset>
    </form>
</div>

<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datetimepicker.js"></script>
<script src="../js/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="../../Static/JS/uploadPreview.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        
        $('#imgShow').click(function(){
            $('#up_img').click();
        })
    });
    //判断Main表中的数值正确性
    function FormCheck(){
        if(isNull($('#hongbao_title').val())){
            alert("【红包主题】不能为空");
            return false;
        }
        if(isNull($('#hongbao_password').val())){
            alert("【红包密码】不能为空");
            return false;
        }
        if(!isNumber($('#hongbao_password').val())){
            alert("【红包密码】必须是数字");
            return false;
        }
        if($('#hongbao_password').val().length < 6){
            alert("【红包密码】必须六位及以上数字");
            return false;
        }
        if(isNull($('#hongbao_beginTime').val())){
            alert("【开始时间】不能为空");
            return false;
        }
        if(!isDate($('#hongbao_beginTime').val(),"yyyy-MM-dd")){
            alert("【开始时间】格式不正确");
            return false;
        }
        if(isNull($('#hongbao_endTime').val())){
            alert("【截止时间】不能为空");
            return false;
        }
        if(!isDate($('#hongbao_endTime').val(),"yyyy-MM-dd")){
            alert("【截止时间】格式不正确");
            return false;
        }
        if(!checkTwoDate($("#hongbao_beginTime").val(),$("#hongbao_endTime").val(),"活动开始","活动结束")){
            return false;
        }
        //replyForm
        if(isNull($('#reply_intext').val())){
            alert("【入口关键字】不能为空");
            return false;
        }
        if(isNull($('#reply_title').val())){
            alert("【标题】不能为空");
            return false;
        }
        if(isNull($('#hongbao_id').val())){
            if(isNull($('#up_img').val())){
                alert("请选择图片");
                return false;
            }
        }
        
        if(isNull($('#reply_description').val())){
            alert("【描述】不能为空");
            return false;
        }
        if(isNull($('#reply_content').val())){
            alert("【正文】不能为空");
            return false;
        }
        return true;
    };
    $('.form_date').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 0,
        format: 'yyyy-mm-dd hh:ii:00',
        autoclose: 1,
    });
</script>
</body>
</html>
