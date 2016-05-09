$(function(){
    $('#exchangSearchBtn').click(function(){

        var SNLast6 = $.trim($("#exchangeID").val());
        if((SNLast6 == '') || (SNLast6.length != 6)){
            $('#myMsg').html('兑换码不能为空，且为六位的字符！');
            $('#myMsg').show();
            setTimeout("$('#myMsg').hide()",3000);
        }else{
            $.ajax({
                url:'../../../admin.php?controller=exchange&method=exchangeDoActionCon&action=searchBill'//改为你的动态页
                ,type:"POST"
                ,data:{"SNLast6":SNLast6}
                ,dataType: "json"
                ,beforeSend:function(XMLHttpRequest){
                        $("#exchangSearchBtn").html('正在提交，请稍等！');
                        $("#exchangSearchBtn").attr({"disabled":"disabled"});
                    }
                ,success:function(json){

                    if(json.success == 1){
                        if(json.Bill_Status  == 1){
                            $("#AwardDiv").hide();
                            $("#AwardNGDiv").attr({"disabled":"disabled"});
                            $("#AwardNGDiv").show();
                        }
                        $("#main_search").hide();
                        $("#billName").val(json.bill_Name);
                        $("#billTel").val(json.bill_Tel);
                        $("#BillType").val(json.Bill_type);
                        $("#billGoodsName").val(json.Bill_GoodsName);
                        $("#billGoodsDescription").val(json.Bill_GoodsDescription);
                        $("#billSNCode").val(json.Bill_SN);
                        $("#billTime").val(json.Bill_insertDate);
                        $("#EventsBeginDate").val(json.Bill_goods_beginDate);
                        $("#EventsEndDate").val(json.Bill_goods_endDate);
                        $("#EventsExpirationDate").val(json.Bill_goods_expirationDate);
                        $("#main_result").show();

                    }else{
                        $("#exchangSearchBtn").html('点击查询');
                        $("#exchangSearchBtn").removeAttr("disabled");
                        $('#myMsg').html(json.msg);
                        $('#myMsg').show();
                        setTimeout("$('#myMsg').hide()",3000);
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        }
    });
    $('#AwardBtn').click(function(){
        var SNCode = $.trim($("#billSNCode").val());
        if(SNCode == ''){
            alert("请确认您的兑换码！");
        }else{
            $.ajax({
                url:'../../../admin.php?controller=exchange&method=exchangeDoActionCon&action=Awarded'//改为你的动态页
                ,type:"POST"
                ,data:{"SNCode":SNCode}
                ,dataType: "json"
                ,beforeSend:function(XMLHttpRequest){
                    $("#AwardBtn").html('正在提交，请稍等！');
                    $("#AwardBtn").attr({"disabled":"disabled"});
                }
                ,success:function(json){

                    $('#main_result').hide();
                    $('#myOKMsg').html(json.msg);
                    $('#myOKMsg').show();
                    setTimeout(function(){
                        window.location = "exchange.html";
                    },2000);
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        }
    });
});