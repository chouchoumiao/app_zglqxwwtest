<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>密码修改</title>

<link rel="stylesheet" href="css/pageFormart.css" type="text/css" media="screen" />
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="div_from_aoto" style="width: 500px;" id = "infoDiv">
    <form role="form">
        <div class="form-group">
            <label for="eventNameList">该公公众号可使用的活动(逗号分开)</label>
            <textarea id = "eventNameList"  class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="eventBackUrlList">各个活动对应的后台URL(逗号分开)</label>
            <textarea id = "eventBackUrlList"  class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="eventForwardUrlList">各个活动对应的前端URL(逗号分开)</label>
            <textarea id = "eventForwardUrlList"  class="form-control" rows="3"></textarea>
        </div>
		<div id = "successMeg" class ="successMeg" style="display:none">
			<label class="laber_from" id = "msg"></label>
		</div>
    </form>
	<div class="control-group">
		<label class="laber_from" ></label>
		<div class="controls" ><button id = "OKBtn" class="btn btn-success btn-block">设置</button></div>
    </div>
    <div id="myAlert" class="alert alert-warning" style = "display:none">
	   <a href="#" class="close" data-dismiss="alert">&times;</a>
	   <strong>请注意：</br></strong>不能都为空,并且数组的个数必须一致！
	</div>
    <div id="myAlert2" class="alert alert-warning" style = "display:none">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>请注意：</br></strong>两个文本框内内容形式必须一致！
    </div>
</div>
<div class="div_from_aoto" style="width: 500px;">
    <div id="myMsg" class="alert alert-warning" style = "display:none"></div>
    <div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(function(){
        $('#OKBtn').click(function(){

            var eventNameList = $.trim($("#eventNameList").val());
            var eventBackUrlList = $.trim($("#eventBackUrlList").val());
            var eventForwardUrlList = $.trim($("#eventForwardUrlList").val());

            var isArr1 = eventNameList.match(/[,，]/g);
            var isArr2 = eventBackUrlList.match(/[,，]/g);
            var isArr3 = eventForwardUrlList.match(/[,，]/g);
            if(isArr1 && isArr2 && isArr3){
                var arr1 = isArr1.length;
                var arr2 = isArr2.length;
                var arr3 = isArr3.length;
            }
            if((arr1 != arr2) || (arr1 != arr3) ){
                $('#myAlert').show();
                setTimeout("$('#myAlert').hide()",2000);
            }else{
                $.ajax({
                    url:'adminDBOpr.php'//改为你的动态页
                    ,type:"POST"
                    ,data:{"action":"eventListSet",
                        "eventNameList":eventNameList,
                        "eventBackUrlList":eventBackUrlList,
                        "eventForwardUrlList":eventForwardUrlList
                    }
                    ,dataType: "json"
                    ,success:function(json){
                        if(json.success == 1){
                            $("#infoDiv").hide();
                            $('#myOKMsg').html(json.msg);
                            $('#myOKMsg').show();
                        }else{
                            $('#myMsg').html(json.msg);
                            $('#myMsg').show();
                            setTimeout("$('#myMsg').hide()",3000);
                        }
                    }
                    ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
                });
            }

        })
    });
</script>
</body>

</html>