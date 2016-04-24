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
    <div class="form-group">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-8">
			&nbsp &nbsp &nbsp &nbsp <button type="button" class="btn btn-primary" id = "dailyCodeBtn">查询最新签到码</button> &nbsp &nbsp
        </div>
	</div>
    <div class="form-group">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-4">
			
		</div>
	</div>
    <div id="createtable" style="display:none"></div>
	</fieldset>
</form>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js?v=20150410"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="../js/bootstrap-datetimepicker.js"></script>
<script src="../js/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    $(function(){
        $('#dailyCodeBtn').click(function(){
            $.ajax({
                url:'getDailyCodeData.php?weixinID=<?php echo $weixinID?>'//改为你的动态页
                ,type:"POST"
                ,data:{}
                ,dataType: "json"
                ,success:function(json){
                    if(json.success == "OK"){
                        //先清除原来的表格
                        $("#createtable").empty();

                        var table=$("<table class='table table-bordered'>");
                        table.appendTo($("#createtable"));
                        var thead = $("<thead><tr><th>有效签到码</th><th>日期</th></tr></thead>");
                        thead.appendTo(table);
                        var tr=$("<tr></tr>");
                        tr.appendTo(table);
                        var td=$("<td>"+json.msg+"</td><td>"+json.date+"</td>");
                        td.appendTo(tr);
                        tr.appendTo(table);
                        $("#createtable").append("</table>");
                        $("#createtable").show();
                    }else{
                        $("#createtable").empty();

                        var table=$("<table class='table table-bordered'>");
                        table.appendTo($("#createtable"));
                        var thead = $("<thead><tr><th>有效签到码</th><th>日期</th></tr></thead>");
                        thead.appendTo(table);
                        var tr=$("<tr></tr>");
                        tr.appendTo(table);
                        var td=$("<td>未能取得数据</td><td>未能取得数据</td>");
                        td.appendTo(tr);
                        tr.appendTo(table);
                        $("#createtable").append("</table>");
                        $("#createtable").show();
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });

        })
    });
</script>
</body>
</html>
