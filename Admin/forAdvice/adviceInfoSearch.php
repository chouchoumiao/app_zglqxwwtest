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

$nowDate = date("Y-m-d",time());
$sql = "select MAX(scratchcard_id) from scratchcard_main
		where scratchcard_beginDate <= '$nowDate'
		AND scratchcard_endDate >= '$nowDate'
		AND scratchcard_isDeleted = 0
		AND WEIXIN_ID = $weixinID";
$scratchcardID = getVarBySql($sql);

//追加可以选择显示条数的功能，默认的情况是显示5条 20151030
$showCount = intval(addslashes($_GET['showCount']));
if($showCount == 0){
	$showCount = 5; //默认为5条
}

//列表数据获取、分页

//计算总数
//取得所有建言的总条数
$sql="select COUNT(*) from adviceInfo
	  where WEIXIN_ID = $weixinID";
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
	$sql = "select * from adviceInfo
			where WEIXIN_ID = $weixinID
			order by id DESC
			limit $from_record,$page_num";
	$class_list = getDataBySql($sql);
}
?>
<!--页面名称-->
<h3>建言献策 管理</h3>
<!--列表开始-->

<table class="table table-bordered" id="alltable">
	<thead>
	<tr>
		<th>序号</th>
		<th>姓名/昵称</th>
		<th>联系方式</th>
		<th>建言内容</th>
		<th>回复内容</th>
		<th>建言时间</th>
		<th>参加活动资格</th>
		<th>当前状态</th>
		<th>共有机会</th>
		<th>已经使用</th>
		<th>编辑</th>
	</tr>
	</thead>
	<?php
	if($class_list){
		foreach($class_list as $value){
			if($value['ADVICE_ISOK'] == 0){
				$isOKFlag = "未审核";
			}else if($value['ADVICE_ISOK'] == 1){
				$isOKFlag = "通过";
			}else if($value['ADVICE_ISOK'] == 2){
				$isOKFlag = "未通过";
			}else if($value['ADVICE_ISOK'] == 3){
				$isOKFlag = "通过有抽奖资格";
			}


			if($value['ADVICE_EVENT'] == 1){
				$isEvent = "有";
			}else{
				$isEvent = "无";
			}

			//获取Vip的总可刮奖次数和已经刮奖次数
			$openid = $value['ADVICE_OPENID'];
			$sql = "select count(*) from adviceInfo
					where WEIXIN_ID = $weixinID
					AND ADVICE_OPENID = '$openid'
					AND ADVICE_EVENT = 1";
			$chanceCount = intval(getVarBySql($sql));

			$sql = "select scratchcard_userCount from scratchcard_user
					where scratchcard_id = $scratchcardID
					AND WEIXIN_ID = $weixinID
					AND scratchcard_userOpenid = '$openid'";
			$userCount = intval(getVarBySql($sql));

			echo "<tbody>
				  <tr>
					<td>$value[id]</td>
					<td>$value[ADVICE_NAME]</td>
					<td>$value[ADVICE_TEL]</td>
					<td><textarea rows='2' cols='30'>$value[ADVICE_ADVICE]</textarea></td>
					<td><textarea>$value[ADVICE_REPLY]</textarea></td>
					<td>$value[ADVICE_CREATETIME]</td>
					<td>$isEvent</td>
					<td>$isOKFlag</td>
					<td>$chanceCount</td>
					<td>$userCount</td>
					<td>
						<a href='adviceSet.php?adviceID=$value[id]&page=$page'>修改/审核</a>
						<a href='adviceReply.php?adviceID=$value[id]&page=$page'>回复内容</a>
					</td>
				  <tr>
			    </tbody>";
		}
	}else{
		echo "<tbody><tr><td colspan=12>无记录</td></tr></tbody>";
	}
	?>
</table>
<ul class="pagination" id="pagination"></ul>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery.tablesorter/2.24.6/js/jquery.tablesorter.js"></script>
<script src="../../Static/JS/multi.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#alltable").tablesorter();

		var pagecount = <?php echo $count;?>;
		var pagesize = <?php echo $page_num;?>;
		var currentpage = <?php echo $page;?>;
		var showCount = <?php echo $showCount?>;
		multi(pagecount,pagesize,currentpage,showCount,"adviceInfoSearch");
		$("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
	});
</script>
</body>
</html>
