<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/imgEnlarge.css?v=20150519" type="text/css" rel="stylesheet" />

</HEAD>
<body>
     
<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

//获取问题ID号传入
$bbsID=intval(addslashes($_GET["bbsID"]));
$page=intval(addslashes($_GET["page"]));

//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($bbsID)
{
    $sql = "select * from bbsInfo where id = $bbsID AND WEIXIN_ID = $weixinID";
    $bbsInfoArr = getlineBySql($sql);
	if(!$bbsInfoArr)
	{
		echo "<script>alert('无此建言信息！');history.back();</Script>";
		exit;
	}else{
        $imgUrlArr = array();
        $imgUrlArrBig = array();
        $imgUrlArr = json_decode($bbsInfoArr['BBS_IMGURL']);
        $imgUrlArrBig = json_decode($bbsInfoArr['BBS_BIGIMGURL']);
    }
}


?>
<!--页面名称-->
<h3 id = "titel">修改/审核<a href="bbsInfoSearch.php?page=<?php echo $page;?>">返回>></a></h3>
<!--表单开始-->
<div id = "main_set">
    <form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
        <fieldset>
        <div id = "Forminfo">
            
            <div class="form-group">
                <label class="col-sm-2 control-label" for="bbs_Name">姓名/昵称：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $bbsInfoArr["BBS_NAME"];?>" name="bbs_Name" id = "bbs_Name" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="bbs_tel">联系方式：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $bbsInfoArr["BBS_TEL"];?>" name="bbs_tel" id = "bbs_tel" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="bbs_advice">线索内容(可修改)：</label>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="5" placeholder = "" type="text" name="bbs_advice" id = "bbs_advice"><?php echo $bbsInfoArr["BBS_ADVICE"];?></textarea>
                </div>
            </div>
            <?php
            $imgUrlArrCount = count($imgUrlArr);
            for($i = 0;$i<$imgUrlArrCount;$i++){
                
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="bbs_imgUrl">图片<?php echo ($i+1);?>预览：</label>
                <div class="col-sm-5" id="content">
                    <a id="<?php echo "box".($i+1);?>" href=<?php echo $imgUrlArrBig[$i];?>><img id= "bbs_imgUrl" src = <?php echo $imgUrlArr[$i];?> /></a>
                </div>
            </div>
            <?php 
            }
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="bbs_createTime">创建时间：</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?php echo $bbsInfoArr["BBS_CREATETIME"];?>" name="bbs_createTime" id = "bbs_createTime" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-6">
                    <input type="hidden" id="bbs_id" value="<?=$bbsInfoArr["id"]?>"></br>
                    <button type="button" class="btn btn-primary"  id = "formSubmitNG">审核不通过</button>&nbsp &nbsp &nbsp &nbsp
                    <button type="button" class="btn btn-primary"  id = "formSubmitGood">审核为红榜</button>&nbsp &nbsp &nbsp &nbsp
                    <button type="button" class="btn btn-primary"  id = "formSubmitBad">审核为黑榜</button>
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
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.imgbox.pack.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(function(){
    
    	$('#formSubmitNG').click(function(){
       
		bbsID =  $('#bbs_id').val();
		$.ajax({
			url:"bbsSetData.php?action=NG"//改为你的动态页
			,type:"POST"
			,data:{"bbsID":bbsID}//调用json.js类库将json对象转换为对应的JSON结构字符串
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
    
	$('#formSubmitGood').click(function(){
		if($('#bbs_advice').val() == ""){
            $('#myMsg').html("线索内容不能为空！");
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
            return false;
		}
		newBbsContent = $('#bbs_advice').val();
        bbsID =  $('#bbs_id').val();
		$.ajax({
			url:"bbsSetData.php?action=good"//改为你的动态页
			,type:"POST"
			,data:{"bbsID":bbsID,"newBbsContent":newBbsContent}//调用json.js类库将json对象转换为对应的JSON结构字符串
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
    $('#formSubmitBad').click(function(){
		if($('#bbs_advice').val() == ""){
            $('#myMsg').html("线索内容不能为空！");
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
            return false;
		}
		newBbsContent = $('#bbs_advice').val();
        bbsID =  $('#bbs_id').val();
		$.ajax({
			url:"bbsSetData.php?action=bad"//改为你的动态页
			,type:"POST"
			,data:{"bbsID":bbsID,"newBbsContent":newBbsContent}//调用json.js类库将json对象转换为对应的JSON结构字符串
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
    
    var count = <?php echo count($imgUrlArr)?>;
    var countBig = <?php echo count($imgUrlArrBig)?>;
    var i;
    if((count == countBig) && (count != 0)){
        for(i=1;i<=count;i++){
            $("#box"+i).imgbox({
                'speedIn'		: 0,
                'speedOut'		: 0,
                'alignment'		: 'center',
                'overlayShow'	: true,
                'allowMultiple'	: false
            });
        }
    }
});
</script>
</body>
</html>
