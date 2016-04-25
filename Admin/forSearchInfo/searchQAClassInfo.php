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
$action = addslashes($_GET["action"]);

//是否删除
if($action=="del"){

    //获取问题ID号传入
    $id=addslashes($_GET["id"]);

    $sql = "delete from question_class where id = $id AND WEIXIN_ID = $weixinID";
   
    $errno = SaeRunSql($sql);
    if( $errno != 0 )	{
        echo "<script>alert('会员信息删除失败！');history.back();</Script>";
        exit;
    }else{
        echo "<script>alert('会员信息删除成功！');location='searchQAClassInfo.php?page=$page';</Script>";
        exit;    
    }
    
}

//列表数据获取、分页

//计算总数

//取得所有建言的总条数
$sql="select COUNT(*) from question_class Where WEIXIN_ID = $weixinID";
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
	$sql = "select * from question_class
            where WEIXIN_ID = $weixinID
            order by id DESC
            limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}

?>
<!--页面名称-->
<h3>答题分类 一览</h3>
<!--列表开始-->

<table class="table table-bordered" >
    <thead>
        <tr>
            <th>分类名称</th>
            <th>创建时间</th>
            <th>编辑(使用中的不能删除)</th>
        </tr>
    </thead>
<?php
if($class_list){
    foreach($class_list as $value){
        $sql = "select COUNT(*) from question_master
                where WEIXIN_ID = $weixinID
                AND QUESTION_CLASS = '$value[question_class_title]'";
        $isClassUseed = getVarBySql($sql);
        echo "<tbody><tr><td>$value[question_class_title]</td>
            <td>$value[insertTime]</td>
            <td>";
            if(!$isClassUseed){
                echo "<a href='javascript:isDelete($value[id]);'>删除</a>";
            }else{
                echo "<a onclick='return false;' href='javascript:void(0));'>不能删除</a>";
            }

        echo "</td><tr></tbody>";
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
        multi(pagecount,pagesize,currentpage,showCount,"searchQAClassInfo");
        $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
    });
    function isDelete(id){
        if(confirm("确认删除吗？")){
            location.href='searchQAClassInfo.php?action=del&id='+id;
        }else{
            return false;
        }
    }
</script>
</body>
</html>
