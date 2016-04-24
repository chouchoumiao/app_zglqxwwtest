<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

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
    if($('#replyID').val() == ""){
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
$weixinID=intval(addslashes($_GET["weixinID"]));

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}
?>
<!--表单开始-->
<div id = "main_set">
    <form action="eventReplySetData.php?weixinID=<?php echo $weixinID;?>&action=setReply" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal shadow" role="form">
        <fieldset>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-7">
            <p><h2><span class="label label-info">设&nbsp &nbsp &nbsp置&nbsp &nbsp &nbsp回&nbsp &nbsp &nbsp复&nbsp &nbsp &nbsp内&nbsp &nbsp &nbsp容</span></h2></p></br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="eventTypeText">选择功能：</label>
            <div class="col-sm-3">
                <select class="form-control" name = "eventTypeText" id = "eventTypeText" onchange="getNowData();">

                    <?php 
                    $sql = "select * from setEventForAdmin where WEIXIN_ID = $weixinID";
                    $eventInfo = getDataBySql($sql);
                    if($eventInfo){
                    ?>
                        <option value="">请选择</option>
                    <?php
                        $eventListText = explode(",",$eventInfo[0]['eventNameList']);
                        $eventListTextCount = count($eventListText);
                        for($i = 0;$i<$eventListTextCount;$i++){
                        ?>
                            <option value = <?php echo $eventListText[$i];?>><?php echo $eventListText[$i];?></option>
                    <?php
                        }
                    }else{
                        echo "<script>
                                $(document).ready(function(){
                                    $('#btn').hide();
                                })
                            </script>";
                    ?>
                        <option value="">尚未设置活动，请先设置</option>

                    <?php
                    }
                    ?>
                </select>
            </div>
        </div></br>
        <div class="form-group" >
            <label class="col-sm-2 control-label" for="reply_intext">入口关键字:</label>
            <div class="col-sm-5" >
                <input class="form-control" placeholder = "添加入口内容" type="text" value="<?php echo $replyInfoArr["reply_intext"];?>" name="reply_intext" id = "reply_intext">
                <span class="label label-default">根据此处设置的关键字直接进入活动</span>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label">回复内容:</label>
        </div>
        <div class = "divcss5">
            <div class="form-group">
                <label class="col-sm-2 control-label"><h4>添加回复:</h4></label>
            </div>
            <hr style="border:0;background-color:#cccccc;height:1px;">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="reply_title">标题:</label>
                <div class="col-sm-9">
                    <input class="form-control" placeholder = "添加标题内容" type="text"  name="reply_title" id = "reply_title">
                </div>
            </div>
            <div class="form-group" id = "newImg">
                <label class="col-sm-2 control-label" for="up_img">选择封面:</label>
                <input type="file" id="up_img" name="up_img" style = "display:none" accept="image/*"/>
                <div id="imgdiv" class="col-sm-5">
                    <img id="imgShow"  class="img-rounded" width="150"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="reply_description">描述:</label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="3" placeholder = "添加描述内容" type="text" name="reply_description" id = "reply_description"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="reply_content">正文:</label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="6" placeholder = "填写正文内容" type="text" name="reply_content" id = "reply_content"></textarea>
                </div>
            </div>                              
        </div>
        </br>
        <div class="form-group"> 
            <label  class="col-sm-3 control-label"></label>
            <div class="col-sm-6" id = "btn">
                <input type="text" class="form-control" id="replyID" name = "replyID" style = "display:none">
                <button type="submit" class="btn btn-primary btn-block" id = "formSubmit" name = "formSubmit" onclick="return FormCheck();">提交</button>
            </div>
        </div>
        </fieldset>
    </form>
</div>

<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../../Static/JS/uploadPreview.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        $('#imgShow').click(function(){
            $('#up_img').click();
        })
    });
    //判断Main表中的数值正确性
    function FormCheck(){
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
    function getNowData(){
        
        var eventText =  $('#eventTypeText').val();

        $.ajax({
            url:"eventReplySetData.php?action=getNowData&weixinID=<?php echo $weixinID?>"//改为你的动态页
            ,type:"POST"
            ,data:{"eventText":eventText}//调用json.js类库将json对象转换为对应的JSON结构字符串
            ,dataType: "json"
            ,success:function(json){
               $('#reply_intext').val(json.reply_intext);
               $('#reply_title').val(json.reply_title);
               $("#imgShow").attr("src", json.reply_ImgUrl);
               $('#reply_description').val(json.reply_description);
               $('#reply_content').val(json.reply_content);
               $('#replyID').val(json.replyID);
            } 
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });
    }
</script>
</body>
</html>
