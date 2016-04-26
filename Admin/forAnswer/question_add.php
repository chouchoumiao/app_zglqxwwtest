<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</HEAD>
<script>
   window.onload = function () { 
        new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
    }
</script>
<script type="text/javascript"> 

//根据是修改还是新增来显示图片
$(document).ready(function(){
    if($('#question_id').val() == ""){
        $('#imgShow').hide();
        $("#imgShow").attr("src", "../../Static/img/upload.jpg");
        $('#imgShow').show();
    }
}); 

</script> 
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');


$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

//获取问题ID号传入
$question_id=intval(addslashes($_GET["question_id"]));
$page=intval(addslashes($_GET["page"]));

//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($question_id){
    $sql = "select * from question_tb
            where status = 1
            AND question_id = $question_id
            AND WEIXIN_ID = $weixinID";
    $answerInfoArr = getlineBySql($sql);
    
	if(!$answerInfoArr){
		echo "<script>alert('无此题目信息');history.back();</Script>";
		exit;
	}
}

$action= addslashes($_GET["action"]);

if($action=="edit"){
	switch($answerInfoArr["question_true"]){
		case "A":
			$question_trueToNum = 1;
			break;
		case "B":
			$question_trueToNum = 2;
			break;
		case "C":
			$question_trueToNum = 3;
			break;
		case "D":
			$question_trueToNum = 4;
			break;	
		default:
	}
}    
?>    
<script type="text/javascript">
	$(document).ready(function(){
        
        $("#questionClass").val('<?php echo $answerInfoArr["question_class_title"];?>');
		$("#question_true").get(0).selectedIndex=<?php echo $question_trueToNum?>;
	});
</script>

<body>
<form action="question_data.php?weixinID=<?php echo $weixinID;?>&action=update" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
	<fieldset>
    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-7">
        <p><h2><span class="label label-info"> &nbsp &nbsp &nbsp题&nbsp &nbsp &nbsp &nbsp目&nbsp &nbsp &nbsp &nbsp添&nbsp &nbsp &nbsp &nbsp加&nbsp &nbsp &nbsp &nbsp修&nbsp &nbsp &nbsp &nbsp改&nbsp &nbsp &nbsp </span></h2></p></br>
        </div>
    </div></br>
	<div class="form-group">
		<label class="col-sm-3 control-label" for="question_subject">题目内容：</label>
		<div class="col-sm-5">
			<input type="text" placeholder="题目名称" class="form-control" id="question_subject" name="question_subject" value ='<?php echo $answerInfoArr["question_subject"];?>'>
		</div>
	</div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="questionClass">所属分类：</label>
        <div class="col-sm-4">
            <?php 
            if($question_id){
            ?>
                <select class="form-control" id= "questionClass" name= "questionClass" disabled="disabled" >
            <?php
            }else{
            ?>
                <select class="form-control" id= "questionClass" name= "questionClass">
            <?php
            }
            ?>
            
                <option value="">请选择(没有情况下，请在主题信息中设置分类)</option>
                <?php 
                $sql = "select * from question_class where WEIXIN_ID = $weixinID";
                $classInfo = getDataBySql($sql);
                $classInfoCount = count($classInfo);
                for($i = 0;$i<$classInfoCount;$i++){
                ?>
                    <option value=<?php echo $classInfo[$i]['question_class_title'];?>><?php echo $classInfo[$i]['question_class_title'];?></option>
                <?php 
                }
                ?>
            </select>
        </div>
    </div>
	<div class="form-group">
		<label class="col-sm-3 control-label" for="question_optionsA">选项A(必须输入)：</label>
		<div class="col-sm-5">
			<input type="text" placeholder="题目选项A答案" class="form-control" id="question_optionsA" name="question_optionsA" value = '<?php echo $answerInfoArr["question_optionsA"];?>'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" for="question_optionsB">选项B(必须输入)：</label>
		<div class="col-sm-5">
			<input type="text" placeholder="题目选项B答案" class="form-control" id="question_optionsB" name="question_optionsB" value ='<?php echo $answerInfoArr["question_optionsB"];?>'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" for="question_optionsC">选项C(可选)：</label>
		<div class="col-sm-5">
			<input type="text" placeholder="题目选项C答案" class="form-control" id="question_optionsC" name="question_optionsC" value ='<?php echo $answerInfoArr["question_optionsC"];?>'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" for="question_optionsD">选项D(可选)：</label>
		<div class="col-sm-5">
			<input type="text" placeholder="题目选项D答案" class="form-control" id="question_optionsD" name="question_optionsD" value = '<?php echo $answerInfoArr["question_optionsD"];?>'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label" for = "question_true">正确答案：</label>
		<div class="col-sm-3">
            <select class="form-control" name="question_true" id = "question_true">
                <option value="">请选择正确答案</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
		</div>
	</div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="up_img">选择图片:</label>
        <input type="file" id="up_img" name="up_img" style = "display:none" accept="image/*"/>
        <div id="imgdiv" class="col-sm-5">
            <?php 
                if(($answerInfoArr['question_img'] == "") || ($answerInfoArr['question_img'] == "imgPath error")){
            ?>
                    <img id="imgShow" src="../../Static/IMG/upload.jpg" width="200"/>
            <?php 
                }else{
            ?>
                    <img id="imgShow" value = "<?php echo $answerInfoArr['question_img'];?>" src=<?php echo $answerInfoArr['question_img'];?> width="280"/>
            <?php 
                }
            ?>
        </div>
    </div></br>
	<div class="form-group">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-5">
			<input type="hidden" id = "question_id" name="question_id" value="<?php echo $answerInfoArr["question_id"]?>">
			<input type="hidden" name="page" value="<?php echo $page;?>">
			<input type="hidden" name="question_img" value="<?php echo $answerInfoArr["question_img"]?>">
			<button type="submit" class="btn btn-primary btn-block" onclick="return FormCheck();">提交</button>
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

    var question_subject = $.trim($("#question_subject").val());
    var questionClass = $.trim($("#questionClass").val());
    
    var question_optionsA = $.trim($("#question_optionsA").val());
    var question_optionsB = $.trim($("#question_optionsB").val());
    var question_optionsC = $.trim($("#question_optionsC").val());
    var question_optionsD = $.trim($("#question_optionsD").val());
    var question_true = $.trim($("#question_true").val());
    
    var msg = "";
    if(isNull(question_subject)){
        msg = "【题目内容】不能为空";
        showMsg(msg);
        return false;
    }
    if(isNull(questionClass)){
        
        msg = "请选择【所属分类】";
        showMsg(msg);
        return false;
    }
    if(isNull(question_optionsA)){
        msg = "【选项A】不能为空";
        showMsg(msg);
        return false;
    }
    if(isNull(question_optionsB)){
        msg = "【选项B】不能为空";
        showMsg(msg);
        return false;
    }
    if((isNull(question_optionsC)) && (!isNull(question_optionsD))){
        msg = "必须按顺序输入【选项C】和【选项D】";
        showMsg(msg);
        return false;
    }
    if(isNull(question_true)){
        msg = "请选择【正确答案】";
        showMsg(msg);
        return false;
    }
    return true;
}
</script>

</body>
</html>
