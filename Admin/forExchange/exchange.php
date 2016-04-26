<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<title>兑奖画面</title>
<?php
    $weixinID = $_SESSION['weixinID'];
?>
</head>
<body>

<div id = "main_search">
<form action="?" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<fieldset>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-3">
		<p><h3><span class="label label-info">本画面为中奖信息查询画面，可根据兑换号（后六位）进行查询</span></h3></p></br>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="exchangeID">兑换码(后六位)：</label>
		<div class="col-sm-3">
			<input class="form-control" placeholder = "请输入兑换码后六位进行查询" type="text" name="exchangeID" id = "exchangeID"></br>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-8">
			<button type="button" class="btn btn-success"  id = "exchangSearchBtn">点击查询</button>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-8">
			<div id="myAlert" class="alert alert-warning" style = "display:none;width: 550px;">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>请注意：</br></strong>兑换码不能为空，且为六位的字符！
			</div>
		</div>
	</div>
	</fieldset>
</form>
</div>
<div id = "main_result" style = "display:none">
<form action="?" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<fieldset disabled>
	<div class="form-group">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-3">
		<p><h3><span class="label label-info">兑换码对应的中奖信息：</span></h3></p></br>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billName">用户名：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "billName">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billTel">手机号：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "billTel">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billType">中奖类型：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "BillType">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billGoodsName">奖品内容：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "billGoodsName">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billGoodsDescription">奖品描述：</label>
		<div class="col-sm-3">
			<textarea class="form-control" type="text" id = "billGoodsDescription"></textarea>
		</div>
	</div>	
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billSNCode">中奖SN码：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text"id = "billSNCode">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="billTime">中奖时间：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "billTime">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label" for="EventsBeginDate">该活动开始日期：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "EventsBeginDate">
		</div>
	</div>	<div class="form-group">
		<label class="col-sm-4 control-label" for="EventsEndDate">该活动结束日期：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "EventsEndDate">
		</div>
	</div>	<div class="form-group">
		<label class="col-sm-4 control-label" for="EventsExpirationDate">领奖截止日期：</label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id = "EventsExpirationDate">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-8">
			<div id="NoResultAlert" class="alert alert-warning" style = "display:none;width: 550px;">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>请注意：</br></strong>输入的兑换码无对应的信息，请确认！
			</div>
		</div>
	</div>
	<div class="form-group" id = "AwardNGDiv" style = "display:none">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-8">
			<button type="button" class="btn btn-success"  id = "AwardNGDiv">该商品已被兑换！</button>
		</div>
	</div>
	</fieldset>
	<div class="form-group" id = "AwardDiv">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-8">
			<button type="button" class="btn btn-success"  id = "AwardBtn">确认领奖</button>
		</div>
	</div>
</form>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(function(){
		$('#exchangSearchBtn').click(function(){
			var SNLast6 = $.trim($("#exchangeID").val());
			if((SNLast6 == '') || (SNLast6.length != 6)){
				$('#myAlert').show();
				setTimeout("$('#myAlert').hide()",2000);
			}else{
				$.ajax({
					url:'exchangeData.php?action=searchBill&weixinID=<?php echo $weixinID?>'//改为你的动态页
					,type:"POST"
					,data:{"SNLast6":SNLast6}
					,dataType: "json"
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
					url:'exchangeData.php?action=Awarded&weixinID=<?php echo $weixinID?>'//改为你的动态页
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
</script>
</body>

</html>