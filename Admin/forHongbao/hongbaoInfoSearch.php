<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}
//获取当前页码
$page=intval(addslashes($_GET["page"]));

//追加可以选择显示条数的功能，默认的情况是显示5条 20151030
$showCount = intval(addslashes($_GET['showCount']));
if($showCount == 0){
    $showCount = 5; //默认为5条
}

//获取操作标识传入
$action = addslashes($_GET["action"]);
//是否删除
if($action=="del"){
	
    //获取问题ID号传入
    $thishongbao_id=intval(addslashes($_GET["hongbao_id"]));
    
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
	$sql = "update hongbaoInfo
            set hongbao_Status=0,
                hongbao_editTime='$nowtime'
            where hongbao_id=$thishongbao_id
            AND WEIXIN_ID=$weixinID";
	$errno = SaeRunSql($sql);
	if( $errno != 0 )	{
		echo "<script>alert('删除数据失败！');history.back();</Script>";
		exit;
	}
	echo "<script>alert('删除成功！');location='hongbaoInfoSearch.php?page=$page&weixinID=$weixinID';</Script>";
    exit;    
}  
//列表数据获取、分页

//计算总数


//取得所有建言的总条数
$sql="select COUNT(*) from hongbaoInfo
      where WEIXIN_ID = $weixinID
      AND hongbao_Status = 1";

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
	$sql = "select * from hongbaoInfo
            where WEIXIN_ID = $weixinID
            AND hongbao_Status = 1
            order by hongbao_id DESC
            limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}
?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

</HEAD>
<body>
<!--页面名称-->
<h3>红包活动 管理<a href="hongbaoSet.php">新增信息>></a></h3>
<!--列表开始-->

<table class="table table-bordered" >
    <thead>
        <tr>
            <th>序号</th>
            <th>主题</th>
            <th>红包密码</th>
            <th>创建时间</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>操作</th>
        </tr>
    </thead>
<?php
    if($class_list){
        foreach($class_list as $value){
            echo "<tbody><tr><td>$value[hongbao_id]</td>
                <td>$value[hongbao_title]</td>
                <td>$value[hongbao_password]</td>
                <td>$value[hongbao_insertTime]</td>
                <td>$value[hongbao_beginTime]</td>
                <td>$value[hongbao_endTime]</td>
                <td>
                <a href='javascript:isDelete($value[hongbao_id]);'>删除</a>&nbsp
                <a href='hongbaoSet.php?action=edit&hongbaoID=$value[hongbao_id]&page=$page'>修改</a>
            </td><tr></tbody>";
        }
    }else{
        echo "<tbody><tr><td colspan=7>无记录</td></tr></tbody>";
    }
?>    
</table>
<ul class="pagination" id="pagination"></ul>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/multi.js"></script>
<script>
    $(document).ready(function() {
        var pagecount = <?php echo $count;?>;
        var pagesize = <?php echo $page_num;?>;
        var currentpage = <?php echo $page;?>;
        var showCount = <?php echo $showCount?>;
        multi(pagecount,pagesize,currentpage,showCount,"hongbaoInfoSearch");
        $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
    });
    function isDelete(id){
        if(confirm("确认删除吗？")){
            location.href='hongbaoInfoSearch.php?action=del&hongbao_id='+id;
        }else{
            return false;
        }
    }
</script>
</body>
</html>
