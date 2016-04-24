<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
</HEAD>
<body>
     
<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

//获取问题ID号传入
$adviceID=intval(addslashes($_GET["adviceID"]));
$page=intval(addslashes($_GET["page"]));

//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($adviceID)
{
    $sql = "select * from adviceInfo
            where id = $adviceID
            AND WEIXIN_ID = $weixinID";
    $adviceInfoArr = getlineBySql($sql);
	if(!$adviceInfoArr)
	{
		echo "<script>alert('无此建言信息！');history.back();</Script>";
		exit;
	}
}

?>
<!--页面名称-->
<h3 id = "titel">修改/审核<a href="adviceInfoSearch.php?page=<?php echo $page;?>">返回>></a></h3>
<!--表单开始-->
<div id = "main_set">
    <form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
        <fieldset>
        <div id = "Forminfo">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="advice_Name">姓名/昵称：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $adviceInfoArr["ADVICE_NAME"];?>" name="advice_Name" id = "advice_Name" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="advice_tel">联系方式：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $adviceInfoArr["ADVICE_TEL"];?>" name="advice_tel" id = "advice_tel" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="advice_advice">建言内容(可修改)：</label>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="9" placeholder = "" type="text" name="advice_advice" id = "advice_advice"><?php echo $adviceInfoArr["ADVICE_ADVICE"];?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="advice_createTime">建言时间：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $adviceInfoArr["ADVICE_CREATETIME"];?>" name="advice_createTime" id = "advice_createTime" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-6">
                    <input type="hidden" id="advice_id" value="<?=$adviceInfoArr["id"]?>"></br>
                    <button type="button" class="btn btn-warning"  id = "formSubmitNG">审核不通过</button>&nbsp &nbsp &nbsp &nbsp
                    <button type="button" class="btn btn-danger"  id = "formSubmitOK">审核通过</button>&nbsp &nbsp &nbsp &nbsp
                    <button type="button" class="btn btn-primary"  id = "formSubmitOKANDEvent">审核通过并有抽奖资格</button>&nbsp &nbsp &nbsp &nbsp
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

<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){

        $('#formSubmitNG').click(function(){

            adviceID =  $('#advice_id').val();
            $.ajax({
                url:"adviceSetData.php?action=NG"//改为你的动态页
                ,type:"POST"
                ,data:{"adviceID":adviceID}//调用json.js类库将json对象转换为对应的JSON结构字符串
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

        $('#formSubmitOK').click(function(){
            if($('#advice_advice').val() == ""){
                $('#myMsg').html("建言内容不能为空！");
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }
            newBbsContent = $('#advice_advice').val();
            adviceID =  $('#advice_id').val();
            $.ajax({
                url:"adviceSetData.php?action=ok"//改为你的动态页
                ,type:"POST"
                ,data:{"adviceID":adviceID,"newBbsContent":newBbsContent}//调用json.js类库将json对象转换为对应的JSON结构字符串
                ,dataType: "json"
                ,success:function(json){

                    $('#titel').hide();
                    $('#Forminfo').hide();
                    if(json.success == 1){
                        $('#myOKMsg').html(json.msg);
                        $('#myOKMsg').show();
                        setTimeout("$('#myOKMsg').hide()",2000);
                    }else if (json.success == -1){
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide()",2000);
                    }
                    setTimeout(function(){window.location="adviceInfoSearch.php?page=<?php echo $page;?>";},2000);

                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
        $('#formSubmitOKANDEvent').click(function(){
            if($('#advice_advice').val() == ""){
                $('#myMsg').html("建言内容不能为空！");
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }
            newBbsContent = $('#advice_advice').val();
            adviceID =  $('#advice_id').val();
            $.ajax({
                url:"adviceSetData.php?action=okANDEvent"//改为你的动态页
                ,type:"POST"
                ,data:{"adviceID":adviceID,"newBbsContent":newBbsContent}//调用json.js类库将json对象转换为对应的JSON结构字符串
                ,dataType: "json"
                ,success:function(json){

                    $('#titel').hide();
                    $('#Forminfo').hide();
                    if(json.success == 1){
                        $('#myOKMsg').html(json.msg);
                        $('#myOKMsg').show();
                        setTimeout("$('#myOKMsg').hide()",2000);
                    }else if (json.success == -1){
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide()",2000);
                    }
                    setTimeout(function(){window.location="adviceInfoSearch.php?page=<?php echo $page;?>";},2000);

                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
    });
</script>
</body>
</html>
