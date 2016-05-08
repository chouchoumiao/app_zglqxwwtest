$(function(){
    $('#exchangSearchBtn').click(function(){
        var SNLast6 = $.trim($("#exchangeID").val());
        if((SNLast6 == '') || (SNLast6.length != 6)){
            $('#myAlert').show();
            setTimeout("$('#myAlert').hide()",2000);
        }else{
            $.ajax({
                url:'../../../admin.php?controller=exchange&method=exchangeDoActionCon&action=exchange'//改为你的动态页
                ,type:"POST"
                ,data:{"SNLast6":SNLast6}
                ,dataType: "json"
                ,success:function(json){

                    alert(json.success)
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
                        alert(json.msg);

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
                url:'../../admin.php?controller=exchange&method=exchangeDoActionCon&action=Awarded'//改为你的动态页
                ,type:"POST"
                ,data:{"SNCode":SNCode}
                ,dataType: "json"
                ,success:function(json){
                    if(json.success == 2){
                        alert(json.msg);
                        self.location='exchange.php';
                    }else{
                        alert(json.msg);
                        self.location='exchange.php';
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        }
    })
});