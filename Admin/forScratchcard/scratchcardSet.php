<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet">

</HEAD>
<body>
     
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

//获取问题ID号传入
$scratchcardID=intval(addslashes($_GET["scratchcardID"]));

//判断是否修改，如果传入了问题ID，进行数据库查询获取全部内容
if($scratchcardID)
{    
    $sql = "select * from scratchcard_main
            where scratchcard_isDeleted = 0
            AND WEIXIN_ID = $weixinID
            AND scratchcard_id = $scratchcardID";
    $scratchcardMainInfoArr = getlineBySql($sql);
    if(!$scratchcardMainInfoArr){
		echo "<script>alert('数据取得失败！');history.back();</Script>";
		exit;
	}
?>	
	<script type="text/javascript">
        //用于显示select的选中事件
		$(document).ready(function(){
			$("#scratchcard_count ").get(0).selectedIndex=<?php echo $scratchcardMainInfoArr["scratchcard_count"] - 1;?>;
		});
	</script>
<?php    
}
$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];
?>
<!--表单开始-->
<div id = "main_set">
<form action="?" method="post" name="class_add" id="class_add" enctype="multipart/form-data" class="form-horizontal" role="form">
    <fieldset>
    <div class="form-group">
        <label class="col-sm-4 control-label"></label>
        <div class="col-sm-3">
            <p><h3><span class="label label-info">刮 刮 卡 设 置 画 面</span></h3></p></br>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_title">活动标题：</label>
        <div class="col-sm-5">
            <input class="form-control" placeholder = "请输入该活动的标题" type="text" 
                value="<?php echo $scratchcardMainInfoArr["scratchcard_title"];?>" id = "scratchcard_title">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_description">活动描述：</label>
        <div class="col-sm-5">
            <textarea class="form-control" placeholder = "请输入该活动的描述" type="text" id = "scratchcard_description"><?php echo $scratchcardMainInfoArr["scratchcard_description"];?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_times">每天可免费刮奖次数：</label>
        <div class="col-sm-5">
            <input class="form-control" placeholder = "请输入0-100之间的整数" type="text"
                value="<?php echo $scratchcardMainInfoArr["scratchcard_times"];?>" id = "scratchcard_times">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_Integral">刮一次需要的<?php echo $weixinName;?>数：</label>
        <div class="col-sm-5">
            <input class="form-control" placeholder = "请输入1-999之间的整数" type="text" 
                value="<?php echo $scratchcardMainInfoArr["scratchcard_Integral"];?>" id = "scratchcard_Integral">
        </div>
    </div>	
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_beginDate">活动开始日期：</label>
        <div class="col-sm-9">
            <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $scratchcardMainInfoArr["scratchcard_beginDate"];?>" name="scratchcard_beginDate" id = "scratchcard_beginDate" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>	
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_endDate">活动结束日期：</label>
        <div class="col-sm-9">
            <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $scratchcardMainInfoArr["scratchcard_endDate"];?>" name="scratchcard_endDate" id = "scratchcard_endDate" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_expirationDate">领奖过期日期：</label>
        <div class="col-sm-9">
            <div class="input-group date form_date col-md-6" data-link-format="yyyy-mm-dd">
                <input class="form-control" type="text" placeholder = "格式：yyyy-mm-dd 点击最右边按钮进行日期选择" value="<?php echo $scratchcardMainInfoArr["scratchcard_expirationDate"];?>" name="scratchcard_expirationDate" id = "scratchcard_expirationDate" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="scratchcard_address">领奖地址：</label>
        <div class="col-sm-5">
            <textarea class="form-control" type="text" id = "scratchcard_address"><?php echo $scratchcardMainInfoArr["scratchcard_address"];?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">奖项个数：</label>
        <div class="col-sm-5">
        <select class="input-xlarge" name="scratchcard_count" id = "scratchcard_count">
            <option value="1">一个奖项</option>
            <option value="2">二个奖项</option>
            <option value="3" selected>三个奖项</option>
            <option value="4">四个奖项</option>
            <option value="5">五个奖项</option>
            <option value="6">六个奖项</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-5">
            <input type="hidden" name="scratchcard_id" id = "scratchcard_id" value="<?=$scratchcardMainInfoArr["scratchcard_id"]?>">
            <button type="button" class="btn btn-primary btn-block"  id = "detailSet">设置各个奖项细节</button>
        </div>
    </div>
    </fieldset>
</form>
</div>
<div id="detail_form" style="display:none">
    <form action="?" method="post" id="scratchcardDetail_add" enctype="multipart/form-data" class="form-horizontal" role="form">
        <fieldset>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detail_title">奖项标题：</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" value="" id = "detail_title" readonly="true">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detail_description">奖品描述：</label>
            <div class="col-sm-5">
                <textarea class="form-control" placeholder = "请输入该奖品的描述，建议控制在9个中文字之内" type="text" value="" id = "detail_description"></textarea>
            </div>
        </div>	
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detail_probability">中奖概率：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "请输入1-100之间的整数" type="text" value="" id = "detail_probability">
            </div>
        </div>	
        <div class="form-group">
            <label class="col-sm-3 control-label" for="detail_count">奖品数量：</label>
            <div class="col-sm-3">
                <input class="form-control" placeholder = "请输入大于零的整数" type="text" value="" id = "detail_count">
            </div>
        </div>	
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-5">
                <button type="button" class="btn btn-info btn-block"  id = "submitDoing" style="display:none">正在提交，请稍等。。。</button>
                <button type="button" class="btn btn-primary btn-block"  id = "detailAllSubmit" style="display:none">提交</button>
                <button type="button" class="btn btn-primary btn-block"  id = "detailSetMore">继续追加奖项</button>
            </div>
        </div>
        </fieldset>
    </form>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-4">
        <div id="myMsg" class="alert alert-warning" style = "display:none"></div>
        <div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
    </div>
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datetimepicker.js"></script>
<script src="../js/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    $(function(){
        var i = 0
        var titleArr = [],descriptionArr = [],probabilityArr = [],countArr = [];
        var detailInfoTitle,detailInfoDescription,detailInfoProbability,detailInfoCount;
        var scratchcard_id = $('#scratchcard_id').val();

        $('#detailSet').click(function(){
            //判断主表填入数据的完整性
            if (!MainInfoCheck()){
                return;
            }
            $('#detail_form').show();
            $('#main_set').hide();
            i++;

            $('#detail_setNum').val("一");
            $('#detail_title').val("一等奖");
            count= $("#scratchcard_count").val();
            if( i == count){
                $("#detailSetMore").hide();
                $('#detailAllSubmit').show();
            }
            $.ajax({
                url:"scratchcardDetailInsert.php?action=mainInfo&weixinID=<?php echo $weixinID?>"//改为你的动态页
                ,type:"POST"
                ,data:{"scratchcard_id":scratchcard_id}//调用json.js类库将json对象转换为对应的JSON结构字符串
                ,dataType: "json"
                ,success:function(json){
                    if(json.success == "OK"){
                        if(scratchcard_id){
                            detailInfoDescription = json.detailInfoDescription;
                            detailInfoProbability = json.detailInfoProbability;
                            detailInfoCount = json.detailInfoCount;
                            //修正bug 返回值不是数据的情况，不出错 20150925
                            if(detailInfoDescription === ""){
                                $('#detail_description').val("");
                            }else{
                                $('#detail_description').val(detailInfoDescription[0]);
                            }
                            if(detailInfoProbability === ""){
                                $('#detail_probability').val("");
                            }else{
                                $('#detail_probability').val(detailInfoProbability[0]);
                            }
                            if(detailInfoCount === ""){
                                $('#detail_count').val("");
                            }else{
                                $('#detail_count').val(detailInfoCount[0]);
                            }
                        }
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
        $('#detailSetMore').click(function(){

            if(!DetailInfoCheck()){
                return;
            }
            $('#detail_form').show();
            $('#main_set').hide();
            i++;

            titleArr.push($('#detail_title').val());
            descriptionArr.push($('#detail_description').val());
            probabilityArr.push($('#detail_probability').val());
            countArr.push($('#detail_count').val());

            var count= $("#scratchcard_count").val();
            if(i > count){
                return;
            }else if(i ==count){
                $("#detailSetMore").hide();
                $('#detailAllSubmit').show();
            }
             if(scratchcard_id){
                //修正bug 返回值不是数据的情况，不出错 20150925
                if(detailInfoDescription === ""){
                    $('#detail_description').val("");
                }else{
                    $('#detail_description').val(detailInfoDescription[i-1]);
                }
                if(detailInfoProbability === ""){
                    $('#detail_probability').val("");
                }else{
                    $('#detail_probability').val(detailInfoProbability[i-1]);
                }
                if(detailInfoCount === ""){
                    $('#detail_count').val("");
                }else{
                    $('#detail_count').val(detailInfoCount[i-1]);
                }
            }else{
                $('#detail_description').val("");
                $('#detail_probability').val("");
                $('#detail_count').val("");
            }
            switch(i){
                case 2:
                    $('#detail_setNum').val("二");
                    $('#detail_title').val("二等奖");
                    break;
                case 3:
                    $('#detail_setNum').val("三");
                    $('#detail_title').val("三等奖");
                    break;
                case 4:
                    $('#detail_setNum').val("四");
                    $('#detail_title').val("四等奖");
                    break;
                case 5:
                    $('#detail_setNum').val("五");
                    $('#detail_title').val("五等奖");
                    break;
                case 6:
                    $('#detail_setNum').val("六");
                    $('#detail_title').val("六等奖");
                    break;
                default:
            }
        });
        $('#detailAllSubmit').click(function(){

            if(!DetailInfoCheck()){
                return;
            }

            $('#detailAllSubmit').hide();
            $('#submitDoing').show();
            i++;

            titleArr.push($('#detail_title').val());
            descriptionArr.push($('#detail_description').val());
            probabilityArr.push($('#detail_probability').val());
            countArr.push($('#detail_count').val());

            var mainInfoStr = $("#scratchcard_title").val()+"."+$("#scratchcard_description").val()+"."+$("#scratchcard_times").val()+"."+
                    $("#scratchcard_Integral").val()+"."+$("#scratchcard_beginDate").val()+"."+$("#scratchcard_endDate").val()+"."+
                    $("#scratchcard_expirationDate").val()+"."+$("#scratchcard_address").val()+"."+$("#scratchcard_count").val();
            $.ajax({
                url:"scratchcardDetailInsert.php?action=detailInsert&weixinID=<?php echo $weixinID?>"//改为你的动态页
                ,type:"POST"
                ,data:{"scratchcard_id":scratchcard_id,
                       "titleArr":titleArr,
                       "descriptionArr":descriptionArr,
                       "probabilityArr":probabilityArr,
                       "countArr":countArr,
                       "mainInfoStr":mainInfoStr}//调用json.js类库将json对象转换为对应的JSON结构字符串
                ,dataType: "json"
                ,success:function(json){
                    $('#detail_form').hide();
                    $('#main_set').hide();

                    $('#myOKMsg').html(json.msg);
                    $('#myOKMsg').show();
                    setTimeout("$('#myOKMsg').hide()",2000);
                    setTimeout(function(){window.location="scratchcardManger.php";},2000);
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        })
    });
    //判断Main表中的数值正确性
    function MainInfoCheck()
    {
        var scratchcard_times = $('#scratchcard_times').val();
        var scratchcard_Integral = $('#scratchcard_Integral').val();
        var scratchcard_beginDate = $('#scratchcard_beginDate').val();
        var scratchcard_endDate = $('#scratchcard_endDate').val();
        var scratchcard_expirationDate = $('#scratchcard_expirationDate').val();

        var msg = '';
        if(isNull($('#scratchcard_title').val())){
            msg = "【活动标题】不能为空";
            showMsg(msg);
            return false;
        }
        if(isNull($('#scratchcard_description').val())){
            msg = "【活动描述】不能为空";
            showMsg(msg);
            return false;
        }
        if(!isInteger(scratchcard_times) || scratchcard_times<0 || scratchcard_times>100){
            msg = "【每天可免费刮奖次数】只能为0到100之间的整数";
            showMsg(msg);
            return false;
        }
        if(!isInteger(scratchcard_Integral) || scratchcard_Integral<1 || scratchcard_Integral>999){
            msg = "【刮奖一次需要的<?php echo $weixinName;?>数】只能为1到999之间的整数";
            showMsg(msg);
            return false;
        }
        if(isNull(scratchcard_beginDate)){
            msg = "【活动开始日期】不能为空";
            showMsg(msg);
            return false;
        }
        if(isNull(scratchcard_endDate)){
            msg = "【活动结束日期】不能为空";
            showMsg(msg);
            return false;
        }
        var dateMsg = checkTwoDate2(scratchcard_beginDate,scratchcard_endDate,"活动开始日期","活动结束日期");
            
        if(dateMsg != ""){
            showMsg(dateMsg);
            return false;
        }
        if(isNull(scratchcard_expirationDate)){
            msg = "【领奖过期日期】不能为空";
            showMsg(msg);
            return false;
        }
        dateMsg = checkTwoDate2(scratchcard_endDate,scratchcard_expirationDate,"活动结束日期","领奖过期日期");
        if(dateMsg != ""){
            showMsg(dateMsg);
            return false;
        }
        if(isNull($('#scratchcard_address').val())){
            msg = "【领奖地址】不能为空";
            showMsg(msg);
            return false;
        }
        return true;
    }
    function DetailInfoCheck()
    {
        var detail_probability = $('#detail_probability').val();
        var detail_count = $('#detail_count').val();
        var msg = '';
        if(isNull($('#detail_title').val())){
            msg = "【奖项标题】不能为空";
            showMsg(msg);
            return false;
        }
        if(isNull($('#detail_description').val())){
            msg = "【奖项描述】不能为空";
            showMsg(msg);
            return false;
        }
        if(!isInteger(detail_probability) || detail_probability<1 || detail_probability>100 ){
            msg = "【中奖概率】只能为1到100之间的整数";
            showMsg(msg);
            return false;
        }
        if(!isInteger(detail_count) || detail_count<1){
            msg = "【奖品数量】只能是大于零的整数";
            showMsg(msg);
            return false;
        }
        return true;
    };
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
</body>
</html>
