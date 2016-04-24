<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
</HEAD>
<body>
    
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
$weixinID = $_SESSION['weixinID'];
//获取当前页码
$page=intval(addslashes($_GET["page"]));

//获取操作标识传入
$action = addslashes($_GET["action"]);

//追加可以选择显示条数的功能，默认的情况是显示5条 20151030
$showCount = intval(addslashes($_GET['showCount']));
if($showCount == 0){
	$showCount = 5; //默认为5条
}

//是否删除
if($action=="del"){
	
    //获取问题ID号传入
    $thisflowerCity_Id=intval(addslashes($_GET["flowerCityId"]));
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
	$sql = "update flowerCity_config
			set flowerCity_isDeleted = 1,
				flowerCity_editTime = '$nowtime'
			where flowerCity_id=$thisflowerCity_Id
			AND WEIXIN_ID= $weixinID" ;
	$errno = SaeRunSql($sql);
	if( $errno != 0 ){
		echo "<script>alert('删除印章商品数据失败！');history.back();</Script>";
		exit;
	}
	echo "<script>alert('操作成功！');location='flowerCityManger.php?page=$page';</Script>";
    exit;    
}    
//列表数据获取、分页

//计算总数
$sql="select COUNT(*) from flowerCity_config
	  where flowerCity_isDeleted = 0
	  AND WEIXIN_ID= $weixinID";
$count = getVarBySql($sql);
//如果数据表里有数据
if($count){
    //每页显示记录数
    $page_num = $showCount; //默认为5
    //如果无页码参数则为第一页
    if ($page == 0) $page = 1;
    //计算开始的记录序号
    $from_record = ($page - 1) * $page_num;
    //获取符合条件的数据
	$sql = "select * from flowerCity_config
			where flowerCity_isDeleted = 0
			AND WEIXIN_ID= $weixinID
			order by id asc
			limit $from_record,$page_num ";
    $class_list = getDataBySql($sql);
}
?>
<!--页面名称-->
<h3>兑换印章商品管理<a href="flowerCitySet.php">新增商品>></a></h3>
<!--列表开始-->

<table class="table table-bordered">
	<thead><tr>
		<th>序号</th>
		<th>商品名称</th>
		<th>详细信息描述</th>
		<th>商品库存</th>
		<th>该商品所需印章</th>
		<th>兑换开始日期</th>
		<th>兑换结束日期</th>
		<th>兑换截止日期</th>
		<th>操作</th>
	</tr></thead>
<?php
	if($class_list){
		foreach($class_list as $value){

			echo "<tbody><tr>
				<td>$value[id]</td>
				<td>$value[flowerCity_name]</td>
				<td><textarea>$value[flowerCity_content]</textarea></td>
				<td>$value[flowerCity_stockCount]</td>
				<td>$value[flowerCity_flowerNum]</td>
				<td>$value[flowerCity_fromDate]</td>
				<td>$value[flowerCity_endDate]</td>
				<td>$value[flowerCity_expirationDate]</td>
				<td>
				<a href='javascript:isDelete($value[id]);'>删除</a>&nbsp
				<a href='flowerCitySet.php?action=edit&flowerCityId=$value[id]&page=$page'>修改</a>
				</td>
			<tr><tbody>";
		}
	}else{
		echo "<tr><td colspan=8>无记录</td></tr>";
	}
?>    
</table>
<ul class="pagination" id="pagination"></ul>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/multi.js?v=20151045"></script>
<script>
	$(document).ready(function() {
		var pagecount = <?php echo $count;?>;
		var pagesize = <?php echo $page_num;?>;
		var currentpage = <?php echo $page;?>;
		var showCount = <?php echo $showCount?>;
		multi(pagecount,pagesize,currentpage,showCount,"flowerCityManger");
		$("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
	});
	function isDelete(id){
		if(confirm("确认删除吗？")){
			location.href='flowerCityManger.php?action=del&flowerCityId='+id;
		}else{
			return false;
		}
	}
</script>
</body>
</html>
