<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/pageFormart.css" type="text/css" media="screen" />
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="div_from_aoto" style="width: 500px;">
    <form>
		<input class="form-control" type="text" name="content" id= "content" value="&nbsp&nbsp&nbsp&nbsp只有Admin用户有添加用户的权限，其他用户只能管理，不能追加用户" readonly="true">
		<br><br>
		<div class="control-group" id = "newUser">
            <label class="laber_from">用户名</label>
            <div  class="controls" ><INPUT class="input_from" id = "addUser" type=text placeholder=" 请输入用户名"><p class=help-block></p></div>
        </div>
        <div class="control-group" id = "pass1">
            <label class="laber_from">密码</label>
            <div  class="controls" ><INPUT class="input_from" id = "newPass" type=password placeholder=" 请输入密码"><p class=help-block></p></div>
        </div>
        <div class="control-group" id = "pass2">
            <label class="laber_from" >再次输入密码</label>
            <div  class="controls" ><INPUT class="input_from" id = "newPass2" type=password placeholder=" 请输入确认密码"><p class=help-block></p></div>
        </div>
    </form>
	<div class="control-group" id = "passBtn">
            <label class="laber_from" ></label>
            <div class="controls" ><button id = "newPassBtn" class="btn btn-success" style="width:120px;" id = "newPassBtn">确认</button></div>
    </div>
	<div id = "successMeg" class ="successMeg" style="display:none">
		<label class="laber_from" id = "msg"></label>
	</div>
	<div id="myAlert" class="alert alert-warning" style = "display:none">
	   <a href="#" class="close" data-dismiss="alert">&times;</a>
	   <strong>请注意：</br></strong>用户名，密码不能为空，并且两次输入的密码要一致，请确认！
	</div>
	<div id="myMsg" class="alert alert-warning" style = "display:none"></div>
	<div id="myOKMsg" class="alert alert-success" style = "display:none"></div>
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(function(){
		var isAddNew = false;
		$('#newPassBtn').click(function(){
			var addUser = $.trim($("#addUser").val());
			var newPass = $.trim($("#newPass").val());
			var newPass2 = $.trim($("#newPass2").val());

			if((addUser == '')||(newPass == '')||(newPass2 == '')||(newPass != newPass2)){
				$('#myAlert').show();
				setTimeout("$('#myAlert').hide()",2000);
			}else{
				$.ajax({
					url:'adminDBOpr.php'//改为你的动态页
					,type:"POST"
					,data:{"action":"addUserByAdmin","addUser":addUser,"newPass":newPass}
					,dataType: "json"
					,success:function(json){
						$("#passBtn").slideUp('fast');
						$("#successMeg").show();
						if(json.success == 1){
							$("#newUser").hide();
							$("#pass1").hide();
							$("#pass2").hide();
							$("#content").hide();
							$('#myOKMsg').html(json.msg);
							$('#myOKMsg').show();
							isAddNew = true;
						}else{
							if(isAddNew == false){
								setTimeout("$('#successMeg').hide('fast')",3000);
								setTimeout("$('#passBtn').show('fast')",3000);
								$('#myMsg').html(json.msg);
								$('#myMsg').show();
								setTimeout("$('#myMsg').hide()",3000);
							}
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