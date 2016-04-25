<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

</HEAD>
<body>
    
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

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
    $id=intval(addslashes($_GET["id"]));
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
	$sql = "update AdminUser set isdeleted = 1,editTime = '$nowtime' where id=$id";
	$errno = SaeRunSql($sql);
	if( $errno != 0 ){
		echo "<script>alert('删除该用户失败！');history.back();</Script>";
		exit;
	}
	echo "<script>alert('删除用户成功！');location='adminUserSearch.php?page=$page';</Script>";
    exit;    
}    
//列表数据获取、分页

//计算总数
$sql="select COUNT(*) from AdminUser where isdeleted = 0 ";
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
    $sql = "select * from AdminUser
            where isdeleted = 0
            order by id asc
            limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}
?>
<!--页面名称-->
<h3>管理员用户管理<a href="../../tpl/admin/addUserByAdmin.php">新增用户>></a></h3>
<!--列表开始-->

<table class="table table-bordered">
    <thead><tr>
        <th>序号</th>
        <th>用户名</th>
        <th>注册时间</th>
        <th>操作</th>
    </tr></thead>
<?php
    if($class_list){
        foreach($class_list as $value){

            if($value['username'] != "admin"){
                $insertDateTime = date("Y-m-d",$value['login_time']);
                echo "<tbody><tr>
                        <td>$value[id]</td>
                        <td>$value[username]</td>
                        <td>$insertDateTime</td>
                        <td><a href='javascript:isDelete($value[id]);'>删除</a></td>
                    <tr><tbody>";
            }
        }
    }else{
        echo "<tr><td colspan=3>无记录</td></tr>";
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
        multi(pagecount,pagesize,currentpage,showCount,"adminUserSearch");
        $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
    });
    function isDelete(id){
        if(confirm("确认删除吗？")){
            location.href='adminUserSearch.php?action=del&id='+id;
        }else{
            return false;
        }
    }
</script>
</body>
</html>
