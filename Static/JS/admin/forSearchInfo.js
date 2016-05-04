$(function(){

    //查询前200名全答对信息查询
    $('#BtnByClassAndID').click(function(){
        //alert("AAA");
        var i;
        var vipIDArr = [],
            vipNameArr = [],
            vipTelArr = [],
            dataArr = [];

        masterID = $.trim($("#questionClassTitle").val());
        $.ajax({
            url:'./admin.php?controller=forSearchInfo&method=getQuestion200'//改为你的动态页
            ,type:"POST"
            ,data:{ "masterID":masterID}
            ,dataType: "json"
            ,beforeSend:function(XMLHttpRequest){
                $("#createtable").hide();
                $("#loading").html("<div style = 'text-align:center'>正在查询,请稍后...</div>");
            }
            ,success:function(json){
                alert(json.msg)
                $("#loading").empty();
                $("#createtable").show();
                if(json.success == "OK"){

                    //先清除原来的表格
                    $("#createtable").empty();
                    vipIDArr =  json.vipIDStr.split(',');
                    vipNameArr =  json.vipNameStr.split(',');
                    vipTelArr =  json.vipTelStr.split(',');
                    dataArr =  json.dataStr.split(',');

                    var table=$("<table class='table table-bordered'>");
                    table.appendTo($("#createtable"));
                    var thead = $("<thead><tr><th>名次</th><th>会员昵称</th><th>联系电话</th><th>会员卡号</th><th>答对时间(只含最早一次)</th></tr></thead>");
                    thead.appendTo(table);
                    if(vipIDArr.length > 1){
                        for(var i=0;i<vipIDArr.length;i++)
                        {
                            var tr=$("<tr></tr>");
                            tr.appendTo(table);

                            var td=$("<td>"+(i+1)+"</td><td>"+vipNameArr[i]+"</td><td>"+vipTelArr[i]+"</td><td>"+vipIDArr[i]+"</td><td>"+dataArr[i]+"</td>");
                            td.appendTo(tr);
                        }
                    }
                    tr.appendTo(table);
                    $("#createtable").append("</table>");
                }
            }
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
        });

    });
});