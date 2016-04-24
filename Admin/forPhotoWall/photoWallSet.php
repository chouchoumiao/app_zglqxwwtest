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
$photoWallID=intval(addslashes($_GET["photoWallID"]));
$page=intval(addslashes($_GET["page"]));

//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($photoWallID)
{
    $sql = "select * from photoWall
            where id = $photoWallID
            AND WEIXIN_ID = $weixinID";
    $photoWallInfoArr = getlineBySql($sql);
	if(!$photoWallInfoArr)
	{
		echo "<script>alert('无此信息！');history.back();</Script>";
		exit;
	}
}


?>
<!--页面名称-->
<h3 id = "titel">修改/审核<a href="photoWallInfoSearch.php?page=<?php echo $page;?>">返回>></a></h3>
<!--表单开始-->
<div id = "main_set">
    <form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
        <fieldset>
        <div id = "Forminfo">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="photoWall_Name">姓名/昵称：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $photoWallInfoArr["PHOTOWALL_NAME"];?>" name="photoWall_Name" id = "photoWall_Name" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="photoWall_tel">联系方式：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $photoWallInfoArr["PHOTOWALL_TEL"];?>" name="photoWall_tel" id = "photoWall_tel" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="photoWall_imgUrl">上传图片预览：</label>
                <div class="col-sm-5">
                    <img id="photoWall_imgUrl"  name = "photoWall_imgUrl" src = <?php echo $photoWallInfoArr["PHOTOWALL_IMGURL"];?>  width="200" height="200"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="photoWall_createTime">上传时间：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $photoWallInfoArr["PHOTOWALL_CREATETIME"];?>" name="photoWall_createTime" id = "photoWall_createTime" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                    <input type="hidden" id="photoWall_id" value="<?=$photoWallInfoArr["id"]?>"></br>
                    <button type="button" class="btn btn-success"  id = "formSubmitNG">审核不通过</button>&nbsp &nbsp &nbsp &nbsp
                    <button type="button" class="btn btn-success"  id = "formSubmit">审核通过</button>
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
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(function(){

        $('#formSubmitNG').click(function(){

            photoWallID =  $('#photoWall_id').val();
            $.ajax({
                url:"photoWallSetData.php?action=NG"//改为你的动态页
                ,type:"POST"
                ,data:{"photoWallID":photoWallID}//调用json.js类库将json对象转换为对应的JSON结构字符串
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

        $('#formSubmit').click(function(){
            photoWallID =  $('#photoWall_id').val();
            $.ajax({
                url:"photoWallSetData.php?action=OK"//改为你的动态页
                ,type:"POST"
                ,data:{"photoWallID":photoWallID}//调用json.js类库将json对象转换为对应的JSON结构字符串
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
        })
    });
</script>
</body>
</html>
