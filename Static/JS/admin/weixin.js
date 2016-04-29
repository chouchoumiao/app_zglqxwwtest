$(function(){
    $('#dailyCodeBtn').click(function(){
        $.ajax({
            url:'../../../admin.php?controller=forSearchInfo&method=getDailyCode'//改为你的动态页
            ,type:"POST"
            ,data:{}
            ,dataType: "json"
            ,success:function(json){
                if(json.success == "OK"){
                    //先清除原来的表格
                    $("#createtable").empty();

                    var table=$("<table class='table table-bordered table-hover'>");
                    table.appendTo($("#createtable"));
                    var thead = $("<thead><tr><th>有效签到码</th><th>日期</th></tr></thead>");
                    thead.appendTo(table);
                    var tr=$("<tr></tr>");
                    tr.appendTo(table);
                    var td=$("<td>"+json.msg+"</td><td>"+json.date+"</td>");
                    td.appendTo(tr);
                    tr.appendTo(table);
                    $("#createtable").append("</table>");
                    $("#dailyCodeBtn").remove();

                    $("#createtable").fadeIn(1000);
                }else{
                    $("#createtable").empty();

                    var table=$("<table class='table table-bordered'>");
                    table.appendTo($("#createtable"));
                    var thead = $("<thead><tr><th>有效签到码</th><th>日期</th></tr></thead>");
                    thead.appendTo(table);
                    var tr=$("<tr></tr>");
                    tr.appendTo(table);
                    var td=$("<td>未能取得数据</td><td>未能取得数据</td>");
                    td.appendTo(tr);
                    tr.appendTo(table);
                    $("#createtable").append("</table>");
                    $("#dailyCodeBtn").remove();
                    $("#createtable").fadeIn(1000);
                }
            }
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });
    });

    $('#OKBtn').click(function(){

        var thisIntegral = $('#integralSet').val();
        var dailyCodeIntegral = $('#integralDailyCode').val();
        var msg = "";

        if(isNull(thisIntegral)){
            msg = "【每日签到】不能为空";
        }else if(!isNumber(thisIntegral)){
            msg = "【每日签到】只能为数字";
        }else if(thisIntegral<0 || thisIntegral>999){
            msg = "【每日签到】只能为1到999之间的整数";
        }

        if(msg != ""){
            $('#myMsg').html(msg);
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
            return false;
        }

        if(isNull(dailyCodeIntegral)){
            msg = "【签到码签到】不能为空";
        }else if(!isNumber(dailyCodeIntegral)){
            msg = "【签到码签到】只能为数字";
        }else if(dailyCodeIntegral<0 || dailyCodeIntegral>999){
            msg = "【签到码签到】只能为1到999之间的整数";
        }

        if(msg != ""){
            $('#myMsg').html(msg);
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
            return false;
        }

        $.ajax({
            url:'./admin.php?controller=weixin&method=editBaseInfo'//改为你的动态页
            ,type:"POST"
            ,data:{
                "thisIntegral":thisIntegral,
                "dailyCodeIntegral":dailyCodeIntegral
            }
            ,dataType: "json"
            ,success:function(json){
                if(json.success == "OK"){

                    $('#myform').hide();
                    $('#myOKMsg').html(json.msg);
                    $('#myOKMsg').show();

                }else{
                    $('#myMsg').html(json.msg);
                    $('#myMsg').show();
                }
                setTimeout(function(){
                    window.location="./admin.php?controller=weixin&method=showBaseInfo";
                },2000);
            }
            ,error:function(xhr){
                    alert('PHP页面有错误！'+xhr.responseText);
                }
        });
    });

    $('#OKNewVipBtn').click(function(){

        var integralNewInsert = $('#integralNewInsert').val();
        var integralReferrerForNewVip = $('#integralReferrerForNewVip').val();
        var integralReferrer = $('#integralReferrer').val();
        var msg = "";

        if((isNull(integralNewInsert))&&(isNull(integralReferrerForNewVip))&&(isNull(integralReferrer))){
            msg = "三项不能都为空！";
        }else if(!isNull(integralNewInsert)){
            if(!isNumber(integralNewInsert)){
                msg = "【新绑定会员设置】只能为数字";
            }else  if(integralNewInsert<0 || integralNewInsert>999){
                msg = "【新绑定会员设置】只能为1到999之间的整数";
            }
        }else if(!isNull(integralReferrerForNewVip)){
            if(!isNumber(integralReferrerForNewVip)){
                msg = "【存在推荐人时，新会员可获得额外的设置】只能为数字";
            }else  if(integralReferrerForNewVip<0 || integralReferrerForNewVip>999){
                msg = "【存在推荐人时，新会员可获得额外的设置】只能为1到999之间的整数";
            }
        }else if(!isNull(integralReferrer)){
            if(!isNumber(integralReferrer)){
                msg = "【存在推荐人时，推荐人可获得额外的设置】只能为数字";
            }else  if(integralReferrer<0 || integralReferrer>999){
                msg = "【存在推荐人时，推荐人可获得额外的设置】只能为1到999之间的整数";
            }
        }
        if(msg != ""){
            $('#myMsg').html(msg);
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",2000);
            return false;
        }

        $.ajax({
            url:'./admin.php?controller=weixin&method=editVipBaseInfo'
            ,type:"POST"
            ,data:{
                    "integralNewInsert":integralNewInsert,
                    "integralReferrerForNewVip":integralReferrerForNewVip,
                    "integralReferrer":integralReferrer
                }
            ,dataType: "json"
            ,success:function(json){
                if(json.success == "OK"){
                    $('#myform').hide();
                    $('#myOKMsg').html(json.msg);
                    $('#myOKMsg').show();
                }else{
                    $('#myMsg').html(json.msg);
                    $('#myMsg').show();
                }
                setTimeout(function(){
                        window.location="./admin.php?controller=weixin&method=showVipBaseInfo";
                    },2000);
            }
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });

    });
});