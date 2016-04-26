<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

</HEAD>
<body>
    
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

//获取当前页码
$page=intval(addslashes($_GET["page"]));

//追加可以选择显示条数的功能，默认的情况是显示5条 20151030
$showCount = intval(addslashes($_GET['showCount']));
if($showCount == 0){
	$showCount = 5; //默认为5条
}
//计算总数

/*   奖品兑换情况查询用     */
$sql="select COUNT(*) from bill where WEIXIN_ID = $weixinID";
$count = getVarBySql($sql);
//如果数据表里有数据
if($count){
    //每页显示记录数
    $page_num = $showCount;
    //如果无页码参数则为第一页
    if ($page == 0) $page = 1;
    //计算开始的记录序号
    $from_record = ($page - 1) * $page_num;
    //获取符合条件的数据
	//0151022修改 按照奖品名称排列
	$sql = "select * from bill
			where WEIXIN_ID = $weixinID
			order by Bill_insertDate desc,
					 Bill_Status desc
			limit $from_record,$page_num";
	//$sql = "select * from bill where WEIXIN_ID = $weixinID order by Bill_GoodsDescription,Bill_Status desc limit $from_record,$page_num";
	$class_list = getDataBySql($sql);

}
?>
<table class="table table-bordered">
	<thead><tr>
		<th>序号</th>
		<th>会员名</th>
		<th>手机</th>
		<th>奖品类型</th>
		<th>奖品内容</th>
		<th>奖品描述</th>
		<th>中奖日期</th>
		<th>兑换日期</th>
		<th>兑换码</th>
		<th>状态</th>
	</tr></thead>
<?php
	if($class_list){
		foreach($class_list as $value){
			$thisOpenid = $value['Bill_openid'];

			$sql = "select Vip_name,Vip_tel from Vip where Vip_openid = '$thisOpenid'";
			$nameAndTel = getLineBySql($sql);
			if($value['Bill_Status'] == 0){
				$status = "未兑换";
			}else if(($value['Bill_Status'] == 1)){
				$status = "已兑换";
			}else{
				$status = "未知";
			}
			if($value['Bill_type'] == "001"){
				$billType = "积分商城";
			}else if($value['Bill_type'] == "002"){
				$billType = "大转盘";
			}else if($value['Bill_type'] == "003"){
				$billType = "刮刮卡";
			}else if($value['Bill_type'] == "004"){
				$billType = "印章";
			}else{
				$billType = "未知";
			}
			echo "<tbody><tr>
				<td>$value[Bill_id]</td>
				<td>$nameAndTel[Vip_name]</td>
				<td>$nameAndTel[Vip_tel]</td>
				<td>$billType</td>
				<td>$value[Bill_GoodsName]</td>
				<td>$value[Bill_GoodsDescription]</td>
				<td>$value[Bill_insertDate]</td>
				<td>$value[Bill_editDate]</td>
				<td>$value[Bill_SN]</td>
				<td>$status</td>
			<tr><tbody>";
		}
	}else{
		echo "<tr><td colspan=10>无记录</td></tr>";
	}
?>    
</table>
<ul class="pagination" id="pagination"></ul>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../../Static/JS/multi.js?v=201501030"></script>
<script>
$(document).ready(function() {  
    var pagecount = <?php echo $count;?>;  
    var pagesize = <?php echo $page_num;?>;
    var currentpage = <?php echo $page;?>;
	var showCount = <?php echo $showCount?>;
	multi(pagecount,pagesize,currentpage,showCount,"exchangeInfoSearch");
	$("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
});  
</script>
</body>
</html>
