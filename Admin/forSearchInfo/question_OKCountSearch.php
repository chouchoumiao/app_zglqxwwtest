<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</HEAD>   
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$sql = "select * from question_master
        where WEIXIN_ID = $weixinID
        AND QUESTION_SATUS <> -1 ";
$classInfo = getDataBySql($sql);  
?>
<body>
<form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
    <fieldset>
    <div class="form-group"  id = "title">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-3">
		<p><h3><span class="label label-info">前  200  名  全  答  对  信  息  查  询</span></h3></p></br>
		</div>
	</div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="questionClassTitle">主题：</label>
        <div class="col-sm-3">
            <select class="form-control" id= "questionClassTitle">
                <?php
                $classInfoCount = count($classInfo);
                for($i = 0;$i<$classInfoCount;$i++){
                ?>
                    <option value=<?php echo $classInfo[$i]['MASTER_ID'];?>><?php echo $classInfo[$i]['QUESTION_TITLE'];?></option>
                <?php 
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="BtnByClassAndID"></label>
		<div class="col-sm-3">
            &nbsp &nbsp &nbsp &nbsp <button type="button" class="btn btn-primary btn-block" id = "BtnByClassAndID">查询</button>
		</div>
	</div>
    <div id="createtable"></div>
    <div id="loading"></div>
	</fieldset>
</form>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(function(){
    var i;
    var vipIDArr = [],
        vipNameArr = [],
        vipTelArr = [],
        dataArr = [];
    $('#BtnByClassAndID').click(function(){
        masterID = $.trim($("#questionClassTitle").val());
        $.ajax({
            url:'question_OKCountSearchData.php?weixinID=<?php echo $weixinID?>'//改为你的动态页
            ,type:"POST"
            ,data:{ "masterID":masterID}
            ,dataType: "json"
            ,beforeSend:function(XMLHttpRequest){
                $("#createtable").hide();
                $("#loading").html("<div style = 'text-align:center'>正在查询,请稍后...</div>"); 
            } 
            ,success:function(json){
                $("#loading").empty();
                $("#createtable").show();
                if(json.success == "OK"){
                    //先清除原来的表格
                    $("#createtable").empty();
                    vipIDArr =  json.vipIDStr.split(',');
                    vipNameArr =  json.vipNameStr.split(',');
                    vipTelArr =  json.vipTelStr.split(',');
                    dataArr =  json.dataStr.split(',');

                    var table=$("<table class='table table-bordered'>");
                    table.appendTo($("#createtable"));
                    var thead = $("<thead><tr><th>名次</th><th>会员昵称</th><th>联系电话</th><th>会员卡号</th><th>答对时间(只含最早一次)</th></tr></thead>");
                    thead.appendTo(table);
                    if(vipIDArr.length > 1){
                        for(var i=0;i<vipIDArr.length;i++)
                        {
                            var tr=$("<tr></tr>");
                            tr.appendTo(table);

                            var td=$("<td>"+(i+1)+"</td><td>"+vipNameArr[i]+"</td><td>"+vipTelArr[i]+"</td><td>"+vipIDArr[i]+"</td><td>"+dataArr[i]+"</td>");
                            td.appendTo(tr);
                        }
                    }
                    tr.appendTo(table);
                    $("#createtable").append("</table>");                 
                }
            } 
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });
			
	})
});
</script>
</body>
</html>