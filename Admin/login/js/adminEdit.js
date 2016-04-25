$(function(){
    $('#adminEditBtn').click(function(){
        var newPass = $.trim($("#newPass").val());
        var newPass2 = $.trim($("#newPass2").val());
        if((newPass == '')||(newPass != newPass2)){
            $('#myMsg').html('用户名，密码不能为空，并且两次输入的密码要一致，请确认！');
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",3000);
        }else{
            $.ajax({
                url:'../../admin.php?controller=admin&method=adminEdit'
                ,type:"POST"
                ,data:{"action":"newPassEdit","newPass":newPass}
                ,dataType: "json"
                ,beforeSend:function(XMLHttpRequest){
                    $("#newPassBtn").html('正在提交，请稍等！');
                    $("#newPassBtn").attr({"disabled":"disabled"});
                }
                ,success:function(json){
                    $("#newPassBtn").removeAttr("disabled");//将按钮可用
                    if(json.success == 1){
                        $("#myform").hide();
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
    });

    $('#adminSetMuneClassBtn').click(function(){

        var eventNameList = $.trim($("#eventNameList").val());
        var eventBackUrlList = $.trim($("#eventBackUrlList").val());
        var eventForwardUrlList = $.trim($("#eventForwardUrlList").val());
        if(!eventNameList || !eventBackUrlList || !eventForwardUrlList){
            $('#myMsg').html('不能为空');
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
            return false;
        }
        var isArr1 = eventNameList.match(/[,，]/g);
        var isArr2 = eventBackUrlList.match(/[,，]/g);
        var isArr3 = eventForwardUrlList.match(/[,，]/g);
        if(isArr1 && isArr2 && isArr3){
            var arr1 = isArr1.length;
            var arr2 = isArr2.length;
            var arr3 = isArr3.length;
        }
        if((arr1 != arr2) || (arr1 != arr3)){
            $('#myMsg').html('不能都为空,并且数组的个数必须一致！');
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
        }else{
            $.ajax({
                url:'../../admin.php?controller=admin&method=adminEdit'
                ,type:"POST"
                ,data:{"action":"eventListSet",
                    "eventNameList":eventNameList,
                    "eventBackUrlList":eventBackUrlList,
                    "eventForwardUrlList":eventForwardUrlList
                }
                ,dataType: "json"
                ,beforeSend:function(XMLHttpRequest){
                    $("#OKBtn").html('正在提交，请稍等！');
                    $("#OKBtn").attr({"disabled":"disabled"});
                }
                ,success:function(json){
                    if(json.success == 1){
                        $("#myform").hide();
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

    });

    $('#addUserByAdminBtn').click(function(){
        var addUser = $.trim($("#addUser").val());
        var newPass = $.trim($("#newPass").val());
        var newPass2 = $.trim($("#newPass2").val());

        if((addUser == '')||(newPass == '')||(newPass2 == '')||(newPass != newPass2)){
            $('#myMsg').html('用户名，密码不能为空，并且两次输入的密码要一致，请确认！');
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",3000);
        }else{
            $.ajax({
                url:'../../admin.php?controller=admin&method=adminEdit'
                ,type:"POST"
                ,data:{"action":"addUserByAdmin","addUser":addUser,"newPass":newPass}
                ,dataType: "json"
                ,beforeSend:function(XMLHttpRequest){
                    $("#newPassBtn").html('正在提交，请稍等！');
                    $("#newPassBtn").attr({"disabled":"disabled"});
                }
                ,success:function(json){
                    $("#newPassBtn").removeAttr("disabled");//将按钮可用
                    if(json.success == 1){
                        $("#myform").hide();
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
    });

    function isDelete(id){
        if(confirm("确认删除吗？")){
            $.ajax({
                url:'admin.php?controller=admin&method=delUserInfoByID'
                ,type:"POST"
                ,data:{"id":id}
                ,dataType: "json"
                ,success:function(json){
                    if(json.success){
                        alert('删除成功!');
                    }else{
                         alert('删除失败!');
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        }else{
            return false;
        }
    };

});