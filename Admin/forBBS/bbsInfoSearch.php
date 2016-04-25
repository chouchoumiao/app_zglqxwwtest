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

//追加可以选择显示条数的功能，默认的情况是显示5条 20151030
$showCount = intval(addslashes($_GET['showCount']));
if($showCount == 0){
	$showCount = 5; //默认为5条
}

//获取操作标识传入
$action= addslashes($_GET["action"]);
   
//列表数据获取、分页

//计算总数
//取得所有建言的总条数
$sql="select COUNT(*) from bbsInfo where WEIXIN_ID = $weixinID";
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
	$sql = "select * from bbsInfo
			where WEIXIN_ID = $weixinID
			order by id DESC
			limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}
?>
<!--页面名称-->
<h3>红黑榜 管理</h3>
<!--列表开始-->

<table class="table table-bordered" >
	<thead>
		<tr>
			<th>序号</th>
			<th>姓名/昵称</th>
			<th>联系方式</th>
			<th>线索内容</th>
			<th>回复内容</th>
			<th>创建时间</th>
			<th>当前状态</th>
			<th>编辑</th>
		</tr>
	</thead>
<?php
	if($class_list){
		foreach($class_list as $value){
			if($value['BBS_ISOK'] == 0){
				$isOKFlag = "未审核";
			}else if($value['BBS_ISOK'] == 1){
				$isOKFlag = "红榜";
			}else if($value['BBS_ISOK'] == 2){
				$isOKFlag = "未通过";
			}else if($value['BBS_ISOK'] == -1){
				$isOKFlag = "黑榜";
			}
			echo "<tbody><tr><td>$value[id]</td>
				<td>$value[BBS_NAME]</td>
				<td>$value[BBS_TEL]</td>
				<td><textarea>$value[BBS_ADVICE]</textarea></td>
				<td><textarea>$value[BBS_REPLY]</textarea></td>
				<td>$value[BBS_CREATETIME]</td>
				<td>$isOKFlag</td>
				<td>
				<a href='bbsSet.php?bbsID=$value[id]&page=$page'>修改/审核</a>
				<a href='bbsReply.php?bbsID=$value[id]&page=$page'>回复内容</a>
			</td><tr></tbody>";
		}
	}else{
		echo "<tbody><tr><td colspan=12>无记录</td></tr></tbody>";
	}
?>    
</table>
<ul class="pagination" id="pagination"></ul>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../../Static/JS/multi.js"></script>
<script>
$(document).ready(function() {  
    var pagecount = <?php echo $count;?>;  
    var pagesize = <?php echo $page_num;?>;
    var currentpage = <?php echo $page;?>;
	var showCount = <?php echo $showCount?>;
	multi(pagecount,pagesize,currentpage,showCount,"bbsInfoSearch");
	$("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
});  
</script>
</body>
</html>
