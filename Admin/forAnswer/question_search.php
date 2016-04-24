<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
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
$classANDIDInfo = getDataBySql($sql);

$sql = "select distinct QUESTION_CLASS from question_master
        where WEIXIN_ID = $weixinID
        AND QUESTION_SATUS <> -1";
$classInfo = getDataBySql($sql);
$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];
?>
<body>
<form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
    <fieldset>
    <div class="form-group"  id = "title">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-3">
		<p><h3><span class="label label-info">积  分  答  题  结  果  查  询</span></h3></p></br>
		</div>
	</div>
    <div class="form-group">
        
        <label class="col-sm-2 control-label" for="questionClass">分类：</label>
        <div class="col-sm-3">
            <select class="form-control" id= "questionClass">
                <?php
                $classInfoCount = count($classInfo);
                for($i = 0;$i<$classInfoCount;$i++){
                ?>
                    <option value=<?php echo $classInfo[$i]['QUESTION_CLASS'];?>><?php echo $classInfo[$i]['QUESTION_CLASS'];?></option>
                <?php 
                }
                ?>
            </select>
        </div>
        <label class="col-sm-1 control-label" for="questionClassTitle">主题：</label>
        <div class="col-sm-3">
            <select class="form-control" id= "questionClassTitle">
                <?php
                $classANDIDInfoCount = count($classANDIDInfo);
                for($i = 0;$i<$classANDIDInfoCount;$i++){
                ?>
                    <option value=<?php echo $classANDIDInfo[$i]['MASTER_ID'];?>><?php echo $classANDIDInfo[$i]['QUESTION_TITLE'];?></option>
                <?php 
                }
                ?>
            </select>
        </div>
    </div></br>
    <div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-4">
			&nbsp &nbsp &nbsp &nbsp <button type="button" class="btn btn-primary" id = "BtnByClass">查询(按题库分类)</button>
		</div>
		<div class="col-sm-4">
            &nbsp &nbsp &nbsp &nbsp <button type="button" class="btn btn-primary" id = "BtnByClassAndID">查询(按分类活动)</button>
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
    var question_master_IDArr = [],
        question_master_TitleArr = [],
        vipIDArr = [],
        vipNameArr = [],
        vipTelArr = [],
        totalCountArr = [],
        totalIntegralCountArr = [];

    $('#BtnByClass').click(function(){
        classInfo = $.trim($("#questionClass").val());
        $.ajax({
            url:'question_searchData.php?action=classOnly&weixinID=<?php echo $weixinID?>'//改为你的动态页
            ,type:"POST"
            ,data:{ "info":classInfo}
            ,dataType: "json"
            ,beforeSend:function(XMLHttpRequest){
                $("#createtable").hide();
                $("#loading").html("<div style = 'text-align:center'>正在查询,请稍后...</div>"); 
            }
            ,success:function(json){
                //$("#createtable").hide();
                if(json.success == "OK"){
                    //先清除原来的表格
                    $("#createtable").empty();
                    vipIDArr =  json.vipIDStr.split(',');
                    vipNameArr =  json.vipNameStr.split(',');
                    vipTelArr =  json.vipTelStr.split(',');
                    totalCountArr =  json.totalCountStr.split(',');
                    totalTimeDisArr =  json.totalTimeDisStr.split(',');
                    totalIntegralCountArr =  json.totalIntegralCountStr.split(',');

                    var table=$("<table class='table table-bordered'>");
                    table.appendTo($("#createtable"));
                    var thead = $("<thead><tr><th>名次</th><th>会员昵称</th><th>联系电话</th><th>会员卡号</th><th>答对题数</th><th>总用时</th><th>总<?php echo $weixinName;?></th></tr></thead>");
                    thead.appendTo(table);
                    for(var i=0;i<vipIDArr.length;i++)
                    {
                        var tr=$("<tr></tr>");
                        tr.appendTo(table);
                       
                        var td=$("<td>"+(i+1)+"</td><td>"+vipNameArr[i]+"</td><td>"+vipTelArr[i]+"</td><td>"+vipIDArr[i]+"</td><td>"+totalCountArr[i]+"</td><td>"+totalTimeDisArr[i]+"</td><td>"+totalIntegralCountArr[i]+"</td>");
                        td.appendTo(tr);
                    }
                    tr.appendTo(table);
                    $("#loading").empty();
                    $("#createtable").append("</table>");
                    $("#createtable").show();
                }else{
                    $("#loading").empty();
                    $("#createtable").empty();
                    $("#loading").html("<div style = 'text-align:center'>没有数据</div>");
                }
            } 
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });
			
	});
    $('#BtnByClassAndID').click(function(){
        masterID = $.trim($("#questionClassTitle").val());
        $.ajax({
            url:'question_searchData.php?action=classAndID&weixinID=<?php echo $weixinID?>'//改为你的动态页
            ,type:"POST"
            ,data:{ "info":masterID}
            ,dataType: "json"
            ,beforeSend:function(XMLHttpRequest){
                $("#createtable").hide();
                $("#loading").html("<div style = 'text-align:center'>正在查询,请稍后...</div>"); 
            } 
            ,success:function(json){

                if(json.success == "OK"){
                    //先清除原来的表格
                    $("#createtable").empty();
                    question_master_IDArr =  json.question_master_IDStr.split(',');
                    question_master_TitleArr =  json.question_master_TitleStr.split(',');
                    OKCountArr =  json.OKCountStr.split(',');
                    vipIDArr =  json.vipIDStr.split(',');
                    vipNameArr =  json.vipNameStr.split(',');
                    vipTelArr =  json.vipTelStr.split(',');
                    totalCountArr =  json.totalCountStr.split(',');
                    totalTimeDisArr =  json.totalTimeDisStr.split(',');
                    totalIntegralCountArr =  json.totalIntegralCountStr.split(',');

                    var table=$("<table class='table table-bordered'>");
                    table.appendTo($("#createtable"));
                    var thead = $("<thead><tr><th>名次</th><th>会员昵称</th><th>联系电话</th><th>会员卡号</th><th>主题内容</th><th>答对题数</th><th>总用时</th><th>总<?php echo $weixinName;?></th><th>全答对次数</th></tr></thead>");
                    thead.appendTo(table);
                    for(var i=0;i<question_master_IDArr.length;i++)
                    {
                        var tr=$("<tr></tr>");
                        tr.appendTo(table);
                       
                        var td=$("<td>"+(i+1)+"</td><td>"+vipNameArr[i]+"</td><td>"+vipTelArr[i]+"</td><td>"+vipIDArr[i]+"</td><td>"+question_master_TitleArr[i]+"</td><td>"+totalCountArr[i]+"</td><td>"+totalTimeDisArr[i]+"</td><td>"+totalIntegralCountArr[i]+"</td><td>"+OKCountArr[i]+"</td>");
                        td.appendTo(tr);
                    }
                    tr.appendTo(table);
                    $("#loading").empty();
                    $("#createtable").append("</table>");
                    $("#createtable").show();
                }else{
                    $("#loading").empty();
                    $("#createtable").empty();
                    $("#loading").html("<div style = 'text-align:center'>没有数据</div>");
                }
            } 
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });
			
	})
});
</script>
</body>
</html>