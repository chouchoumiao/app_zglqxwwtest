<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet">
</HEAD>
<body>    
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];
//获取问题ID号传入
$thisflowerCity_id=intval(addslashes($_GET["flowerCityId"]));
$page=intval(addslashes($_GET["page"]));
	
//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($thisflowerCity_id){
    $sql = "select * from flowerCity_config 
            where flowerCity_isDeleted = 0 
            AND id = $thisflowerCity_id
            AND WEIXIN_ID= $weixinID";
    $integralGoodsInfoArr = getlineBySql($sql);
	if(!$integralGoodsInfoArr){
		echo "<script>alert('无此商品信息');history.back();</Script>";
		exit;
	}
}
?>
<!--页面名称-->
<h3>商品添加/修改<a href="flowerCityManger.php?page=<?php echo $page;?>">返回>></a></h3>
<!--表单开始-->
<form action="flowerCityData.php" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
    <fieldset>
    <div class="form-group">
        <label class="col-sm-2 control-label">商品名称：</label>
        <div class="col-sm-5">
            <input class="form-control" placeholder = "请输入商品名称(请在10个字内，过长会影响图片显示布局)" type="text" value="<?php echo $integralGoodsInfoArr["flowerCity_name"];?>" id="flowerCity_name" name="flowerCity_name">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">商品描述：</label>
        <div class="col-sm-5">
            <textarea class="form-control" placeholder = "请输入商品描述" type="text" id="flowerCity_content" name="flowerCity_content"><?php echo $integralGoodsInfoArr["flowerCity_content"];?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">商品总库存数：</label>
        <div class="col-sm-5">
            <input class="form-control" placeholder = "请输入1-999999之间的数字" type="text" value="<?php echo $integralGoodsInfoArr["flowerCity_stockCount"];?>" id="flowerCity_stockCount" name="flowerCity_stockCount">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">兑换所需印章数：</label>
        <div class="col-sm-5">
            <input class="form-control" placeholder = "请输入1-9999之间的数字" type="text" value="<?php echo $integralGoodsInfoArr["flowerCity_flowerNum"];?>" id="flowerCity_flowerNum" name="flowerCity_flowerNum">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="flowerCity_fromDate">本商品兑换开始日：</label>
        <div class="col-sm-10">
            <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $integralGoodsInfoArr["flowerCity_fromDate"];?>" name="flowerCity_fromDate" id = "flowerCity_fromDate" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="flowerCity_endDate">本商品兑换结束日：</label>
        <div class="col-sm-10">
            <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $integralGoodsInfoArr["flowerCity_endDate"];?>" name="flowerCity_endDate" id = "flowerCity_endDate" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="flowerCity_expirationDate">本商品领取截止日：</label>
        <div class="col-sm-10">
            <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $integralGoodsInfoArr["flowerCity_expirationDate"];?>" name="flowerCity_expirationDate" id = "flowerCity_expirationDate" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>
    <div class="form-group" id = "thisImgShow" style = "display:none">
        <label class="col-sm-2 control-label" for="flowerCity_imgUrl">现图片预览：</label>
        <div class="col-sm-5">
            <img id="flowerCity_imgUrl"  name = "flowerCity_imgUrl" src = <?php echo $integralGoodsInfoArr["flowerCity_imgPath"];?>  width="100"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">选择图片：</label>

        <div class="col-sm-5">
            <input class="input-file" name="filename" id="filename" type="file" accept="image/*" >
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-5">
            <input type="hidden" id="flowerCity_id" name = "flowerCity_id" value="<?=$integralGoodsInfoArr["id"]?>">
            <button type="submit" id = "formSubmit" name = "formSubmit" class="btn btn-success" onclick="return MainInfoCheck();">提交</button>
        </div>
    </div>
    </fieldset>
</form>

<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datetimepicker.js"></script>
<script src="../js/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    //判断Main表中的数值正确性
    function MainInfoCheck(){
        if(isNull($('#flowerCity_name').val())){
            alert("【商品名称】不能为空");
            return false;
        }
        if($('#flowerCity_name').val().length > 10){
            alert("【商品描述】请控制在10个字内！");
            return false;
        }
        if(isNull($('#flowerCity_content').val())){
            alert("【商品描述】不能为空");
            return false;
        }
        if(isNull($('#flowerCity_stockCount').val())){
            alert("【商品总库存数】不能为空");
            return false;
        }else if(!isNumber($('#flowerCity_stockCount').val())){
            alert("【商品总库存数】只能为数字");
            return false;
        }else if($('#flowerCity_stockCount').val()<1 || $('#flowerCity_stockCount').val()>999999){
            alert("【商品总库存数】只能为1到999999之间的整数");
            return false;
        }
        if(isNull($('#flowerCity_flowerNum').val())){
            alert("【兑换该商品所需印章数】不能为空");
            return false;
        }else if(!isNumber($('#flowerCity_flowerNum').val())){
            alert("【兑换该商品所需印章数】只能为数字");
            return false;
        }else if($('#flowerCity_flowerNum').val()<1 || $('#flowerCity_flowerNum').val()>9999){
            alert("【兑换该商品所需印章数】只能为1到99之间的整数");
            return false;
        }
        if(isNull($('#flowerCity_fromDate').val())){
            alert("【开始日】不能为空");
            return false;
        }
        if(!isDate($('#flowerCity_fromDate').val(),"yyyy-MM-dd")){
            alert("【开始日】格式不正确");
            return false;
        }
        if(isNull($('#flowerCity_endDate').val())){
            alert("【结束日】不能为空");
            return false;
        }
        if(!isDate($('#flowerCity_endDate').val(),"yyyy-MM-dd")){
            alert("【结束日】格式不正确");
            return false;
        }
        if(!checkTwoDate($("#flowerCity_fromDate").val(),$("#flowerCity_endDate").val(),"开始","结束")){
            return false;
        }
        if(isNull($('#flowerCity_expirationDate').val())){
            alert("【领奖过期日期】不能为空");
            return false;
        }
        if(!isDate($('#flowerCity_expirationDate').val(),"yyyy-MM-dd")){
            alert("【领奖过期日期】格式不正确");
            return false;
        }
        if(!checkTwoDate($("#flowerCity_endDate").val(),$("#flowerCity_expirationDate").val(),"结束","领奖过期")){
            return false;
        }
        if(isNull($('#flowerCity_id').val())){
            if(isNull($('#filename').val())){
                alert("新增商品时，需要选择图片！");
                return false;
            }
        }
        return true;
    }
    
    function showThisImg(){
        $('#thisImgShow').show();
    }
    $('.form_date').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
<?php
	//获取操作标识传入
	$action = addslashes($_GET["action"]);
    
    if($action == "edit"){
        echo "<script>showThisImg();</Script>";
    }
?>
</body>
</html>
