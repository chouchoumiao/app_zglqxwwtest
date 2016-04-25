// JavaScript Document
$(function(){
	$("#user").focus();
	$("input:text,textarea,input:password").focus(function() {
		$(this).addClass("cur_select");
    });
    $("input:text,textarea,input:password").blur(function() {
		$(this).removeClass("cur_select");
    });

	
	$(".btn").live('click',function(){
		var user = $("#user").val();
		var pass = $("#pass").val();
		if(user==""){
			$('<div id="msg" />').html("用户名不能为空！").appendTo('.sub').fadeOut(2000);
			$("#user").focus();
			return false;
		}
		if(pass==""){
			$('<div id="msg" />').html("密码不能为空！").appendTo('.sub').fadeOut(2000);
			$("#pass").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "admin.php?controller=admin&method=login",
			dataType: "json",
			data: {"user":user,"pass":pass},
			beforeSend: function(){
				$('<div id="msg" />').addClass("loading").html("正在登录...").css("color","#999").appendTo('.sub');
			},
			success: function(json){

                if(json.success==1){
					window.location.href="admin.php?controller=admin&method=index";
				}else{
					$("#msg").remove();
					$('<div id="errmsg" />').html(json.msg).css("color","#999").appendTo('.sub').fadeOut(2000);
					return false;
				}
			},
            error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
		});
	});

});