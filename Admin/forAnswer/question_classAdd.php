<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet">
</HEAD>   
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');


$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

?>
<body>
<form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
    <fieldset>
    <div class="form-group"  id = "title">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-3">
		<p><h3><span class="label label-info">添 加 答 题 分 类</span></h3></p></br>
		</div>
	</div>
    <div id = "mainInfoSet">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="classAddTitle">答题分类名称：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="classAddTitle">
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
	<div class="form-group">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-5">
			<button type="button" class="btn btn-primary btn-block" id = "OKBtn">提交</button>
		</div>
	</div>
    
    </fieldset>
</form>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js?v=20150410"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datetimepicker.js"></script>
<script src="../js/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    $(function(){
        $('#OKBtn').click(function(){

            //重新取得数据框数据
            classAddTitle = $.trim($("#classAddTitle").val());

            var msg = "";

            if(isNull(classAddTitle)){
                msg = "【答题分类名称】不能为空";
            }
            if(msg != ""){
                $('#myMsg').html(msg);
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }
            $.ajax({
                url:'question_classAddData.php?weixinID=<?php echo $weixinID?>'//改为你的动态页
                ,type:"POST"
                ,data:{ "classAddTitle":classAddTitle}
                ,dataType: "json"
                ,success:function(json){
                    if((json.success == "InsertNG")){
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide()",2000);
                        return false;
                    }
                    if(json.success == "OK"){

                        $('#OKBtn').hide();
                        $('#mainInfoSet').hide();
                        $('#title').hide();
                        $('#myOKMsg').html(json.msg);
                        $('#myOKMsg').show();
                        setTimeout("history.back()",1000); //追加一秒钟后返回 20150714

                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });

        })
    });
</script>
</body>
</html>
