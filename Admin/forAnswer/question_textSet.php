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

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];
$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$masterID = addslashes($_GET["masterID"]);

//修改的情况下
if($masterID){
    $sql = "select * from question_master
            where MASTER_ID = $masterID
            AND WEIXIN_ID = $weixinID";
    $masterInfo = getLineBySql($sql);
    if($masterInfo){
        $winCount = json_decode($masterInfo['QUESTION_WIN_COUNT']);
    }else{
        echo "<script>alert('数据取得失败！');history.back();</Script>";
        exit;
    }
}

//修改的情况下需要设置下拉框内容
$action = addslashes($_GET["action"]);

if($action=="edit"){
?>	
	<script type="text/javascript">
        //用于显示select的选中事件
		$(document).ready(function(){
			$("#questionSatus ").val(<?php echo $masterInfo['QUESTION_SATUS'];?>);
            $("#questionClass ").val('<?php echo $masterInfo['QUESTION_CLASS'];?>');
            $("#maxTimes ").val('<?php echo $masterInfo['QUESTION_MAXTIMES'];?>');
            
		});
	</script>
	
<?php 	
}
?>  
<body>
<form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
    <fieldset>
    <div class="form-group"  id = "title">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-3">
		<p><h3><span class="label label-info">会 员 答 题 活 动 主 题 设 置</span></h3></p>
		</div>
	</div>
    <div id = "mainInfoSet">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="questionTitle">主题名称：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="questionTitle" value = <?php echo $masterInfo['QUESTION_TITLE']?>>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="questionShowCount">答题数目：</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="questionShowCount" value = <?php echo $masterInfo['QUESTION_SHOW_COUNT']?>>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="questionWinCount">有奖题目数：</label>
            <div class="col-sm-5">
                <input type="text" placeholder="选择从答对几题开始，可以获取<?php echo $weixinName;?>，默认为3题开始获取<?php echo $weixinName;?>" class="form-control" id="questionWinCount" value = <?php echo $winCount[0];?>>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="questionSatus">状态：</label>
            <div class="col-sm-3">
                <select class="form-control" id = "questionSatus" name = "questionSatus">
                    <option value="">请选择</option>
                    <option value="1">开启</option>
                    <option value="0">关闭</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="question_beginDate">开始日期:</label>
            <div class="col-sm-9">
                <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                    <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $masterInfo["QUESTION_BEGIN_DATE"];?>" name="question_beginDate" id = "question_beginDate" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="question_endDate">结束日期:</label>
            <div class="col-sm-9">
                <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                    <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $masterInfo["QUESTION_END_DATE"];?>" name="question_endDate" id = "question_endDate" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="maxTimes">每天可答题次数：</label>
            <div class="col-sm-3">
                <select class="form-control" id= "maxTimes">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="questionClass">答题类型：</label><a href="question_classAdd.php">添加分类</a>
            <div class="col-sm-3">
                <select class="form-control" id= "questionClass">
                    <option value="">请选择</option>
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
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="questionID" value = "<?php echo $masterInfo['MASTER_ID']?>" style = "display:none">
                <button type="button" class="btn btn-primary btn-block" id = "detailInfoSetBtn">各明细设置</button>
            </div>
        </div>
    </div>
    <div id = "detailInfoSet" style = "display:none">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detailCountInfo">有<?php echo $weixinName;?>题目数：</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="content" id= "detailCountInfo" readonly="true">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detailCouInfoIntegral">对应获得<?php echo $weixinName;?>数：</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="content" id= "detailCouInfoIntegral" placeholder = "输入对应的<?php echo $weixinName;?>数，只能是的数字">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detailCouInfoDescription">该种情况时，显示的话语</label>
            <div class="col-sm-5">
                <textarea class="form-control" placeholder = "请输入该种情况时，显示的话语" type="text" id = "detailCouInfoDescription"><?php echo $bigWheelMainInfoArr["bigWheel_description"];?></textarea></br>
            </div>
        </div>
        <div class="form-group" id="detailAdd">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-5">
                <button type="button" class="btn btn-primary btn-block" id = "detailAddBtn">继续追加</button>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-5">
                <button type="button" class="btn btn-primary btn-block" id = "OKBtn" style= "display:none">提交</button>
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
</body>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js?v=20150520"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datetimepicker.js"></script>
<script src="../js/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
//判断Main表中的数值正确性
    function FormCheck(){
        questionTitle = $.trim($("#questionTitle").val());
		questionSatus = $.trim($("#questionSatus").val());
		questionShowCount = $.trim($("#questionShowCount").val());
        questionWinCount = $.trim($("#questionWinCount").val());
		question_beginDate = $.trim($("#question_beginDate").val());
		question_endDate = $.trim($("#question_endDate").val());
		questionClass = $.trim($("#questionClass").val());
        var msg = "";
        
        if(isNull(questionTitle)){
            msg = "【主题名称】不能为空";
            showMsg(msg);
            return false;
        }
        if((isNull(questionShowCount)) || (!isNumber(questionShowCount))){
            msg = "【答题数目】不能为空并且只能为数字";
            showMsg(msg);
            return false;
        }
        if(isNull(questionWinCount) || !isNumber(questionWinCount)){
            msg = "【有奖题目数设置】不能为空并且只能为数字";
            showMsg(msg);
            return false;
        }
        //转化为数值进行比较
        if(parseInt(questionWinCount)>parseInt(questionShowCount)){
            msg = "【有奖题目数设置】不能大于【一次显示的题目数】";
            showMsg(msg);
            return false;
        }
        if(isNull(questionSatus)){
            msg = "请选择【状态】";
            showMsg(msg);
            return false;
        }
        if(isNull(question_beginDate)){
            msg = "【开始日期】不能为空";
            showMsg(msg);
            return false;
        }
        if(isNull(question_endDate)){
            msg = "【结束日期】不能为空";
            showMsg(msg);
            return false;
        }
        //判断日期大小
        var dateMsg = checkTwoDate2(question_beginDate,question_endDate,"开始日期","结束日期");
        
        if(dateMsg != ""){
            showMsg(dateMsg);
            return false;
        }
        if(isNull(questionClass)){
            msg = "请选择【答题类型】";
            showMsg(msg);
            return false;
        }
        return true;
    }

    $('.form_date').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        format: 'yyyy-mm-dd',
        autoclose: 1,
    });
    $(function(){
        var count = [],integral = [],comment = [];
        var questionShowCount,questionWinCount,questionClass,detailCouInfoIntegral,detailCouInfoDescription;
        var questionID = $.trim($("#questionID").val());
        var classinfo,Despinfo;
        var j=1;//修正修改时显示积分，话语错误，原因是下标错误

        var msg = "";

        $('#detailInfoSetBtn').click(function(){
            questionShowCount = $.trim($("#questionShowCount").val());
            questionWinCount = $.trim($("#questionWinCount").val());
            questionClass = $.trim($("#questionClass").val());

            if(!FormCheck()){
                return false;
            }

            $.ajax({
                url:"question_data.php?action=mainInfo&weixinID=<?php echo $weixinID?>"//改为你的动态页
                ,type:"POST"
                ,data:{"questionShowCount":questionShowCount,
                       "questionClass":questionClass,
                       "questionID":questionID
                      }
                ,dataType: "json"
                ,success:function(json){
                    if(json.success == "NoEnoughDate"){
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide()",6000);
                        return false;
                    }
                    if(json.success == "OK"){
                        $("#detailCountInfo").val("答对"+questionWinCount+"题时候设置");
                        if(questionID){
                            classinfo = json.classinfo;
                            Despinfo = json.Despinfo;

                            //修正bug 返回值不是数据的情况，不出错 20150925
                            if(classinfo === ""){
                                $('#detailCouInfoIntegral').val("");
                            }else{
                                $('#detailCouInfoIntegral').val(classinfo[0]);
                            }
                            if(Despinfo === ""){
                                $('#detailCouInfoDescription').val("");
                            }else{
                                $('#detailCouInfoDescription').val(Despinfo[0]);
                            }
                        }
                        $("#mainInfoSet").hide();
                        $("#detailInfoSet").show();
                        //bug修正-修正了（不能设置题数为1的情况）
                        if(questionWinCount == questionShowCount){
                            $("#detailAddBtn").hide();
                            $("#OKBtn").show();
                        }
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });

        });
        $('#detailAddBtn').click(function(){

            detailCouInfoIntegral = $("#detailCouInfoIntegral").val();
            detailCouInfoDescription = $.trim($("#detailCouInfoDescription").val());

            if((isNull(detailCouInfoIntegral)) || (!isNumber(detailCouInfoIntegral)) ){
                msg = "【对应获得<?php echo $weixinName;?>数】只能是不为空的数字";
                showMsg(msg);
                return false;
            }
            if(isNull(detailCouInfoDescription)){
                msg = "【该种情况时，显示的话语】不能为空";
                showMsg(msg);
                return false;
            }
            count.push(questionWinCount);
            integral.push(detailCouInfoIntegral);
            comment.push(detailCouInfoDescription);

            questionWinCount = parseInt(questionWinCount);
            questionShowCount = parseInt(questionShowCount);
            questionWinCount = questionWinCount + 1;

            $("#detailCountInfo").val("答对"+questionWinCount+"题时候设置");
            if(questionID){

                //修正bug 返回值不是数据的情况，不出错 20150925
                if(classinfo === ""){
                    $('#detailCouInfoIntegral').val("");
                }else{
                    $('#detailCouInfoIntegral').val(classinfo[j]);
                }
                if(Despinfo === ""){
                    $('#detailCouInfoDescription').val("");
                }else{
                    $('#detailCouInfoDescription').val(Despinfo[j]);
                }
             }else{
                $("#detailCouInfoIntegral").val("");
                $("#detailCouInfoDescription").val("");
             }
            if(questionWinCount == questionShowCount){

                $("#detailAddBtn").hide();
                $("#detailAdd").hide();
                $("#OKBtn").show();
            }
            j++;
        });

        $('#OKBtn').click(function(){

            //重新取得数据框数据
            detailCouInfoIntegral = $.trim($("#detailCouInfoIntegral").val());
            detailCouInfoDescription = $.trim($("#detailCouInfoDescription").val());

            var msg = "";

            if(isNull(detailCouInfoIntegral)){
                msg = "【对应获得<?php echo $weixinName;?>数】不能为空";
            }else if(!isNumber(detailCouInfoIntegral)){
                msg = "【对应获得<?php echo $weixinName;?>数】只能为数字";
            }else{
                if(isNull(detailCouInfoDescription)){
                    msg = "【该种情况时，显示的话语】不能为空";
                }
            }
            if(msg != ""){
                $('#myMsg').html(msg);
                $('#myMsg').show();
                setTimeout("$('#myMsg').hide()",2000);
                return false;
            }

            //最后的数据追加到数组中，然后写入数据库
            count.push(questionWinCount);
            integral.push(detailCouInfoIntegral);
            comment.push(detailCouInfoDescription);

            questionTitle = $.trim($("#questionTitle").val());
            questionSatus = $.trim($("#questionSatus").val());
            questionShowCount = $.trim($("#questionShowCount").val());
            question_beginDate = $.trim($("#question_beginDate").val());
            question_endDate = $.trim($("#question_endDate").val());
            questionClass = $.trim($("#questionClass").val());
            maxTimes = $.trim($("#maxTimes").val());

            $.ajax({
                url:'question_data.php?action=detailInfo&weixinID=<?php echo $weixinID?>'//改为你的动态页
                ,type:"POST"
                ,data:{ "questionShowCount":questionShowCount,
                        "questionWinCount":questionWinCount,
                        "questionTitle":questionTitle,
                        "questionSatus":questionSatus,
                        "question_beginDate":question_beginDate,
                        "question_endDate":question_endDate,
                        "questionClass":questionClass,
                        "maxTimes":maxTimes,
                        "questionID":questionID,
                        "count":count,
                        "integral":integral,
                        "comment":comment}
                ,dataType: "json"
                ,success:function(json){
                    if((json.success == "InsertNG") || (json.success == "UpdateNG") || (json.success == "exist")){
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide()",2000);
                        return false;
                    }
                    if(json.success == "OK"){

                        $('#OKBtn').hide();
                        $('#mainInfoSet').hide();
                        $('#detailInfoSet').hide();
                        $('#title').hide();
                        $('#myOKMsg').html(json.msg);
                        $('#myOKMsg').show();

                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });

        })
    });
	$(document).ready(function(){
		$("#question_true").get(0).selectedIndex=<?php echo $question_trueToNum?>;
	});
</script>
</html>
