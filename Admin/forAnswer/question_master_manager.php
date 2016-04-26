<?php session_start();?>
<!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</HEAD>
<body>
    
<?php
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
$action= addslashes($_GET["action"]);

//是否删除
if($action=="del"){
    //获取问题ID号传入
    $masterID=intval(addslashes($_GET["masterID"]));
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
	$sql = "update question_master
            set QUESTION_SATUS = -1,
                QUESTION_EDIT_TIME = '$nowtime'
            where MASTER_ID = $masterID
            and WEIXIN_ID=$weixinID";
    
    $errno = SaeRunSql($sql);
    
    if( $errno != 0 ){
        echo "<script>alert('删除数据失败！');history.back();</Script>";
        exit;
    }  
    echo "<script>alert('操作成功！');location='question_master_manager.php?page=$page&weixinID=$weixinID';</Script>";
    exit;    
 
       
}    
//列表数据获取、分页

//计算总数
$sql= "select COUNT(*) from question_master where WEIXIN_ID = $weixinID";
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
    $sql = "select * from question_master
            where WEIXIN_ID = $weixinID
            AND QUESTION_SATUS != -1
            order by MASTER_ID asc
            limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}
?>
<!--页面名称-->
<h3>会员答题 管理<a href="question_textSet.php">新增活动>></a></h3>
<!--列表开始-->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>序号</th>
            <th>主题</th>
            <th>答题数目</th>
            <th>有奖题目数</th>
            <th>开始日期</th>
            <th>结束日期</th>
            <th>答题类型</th>
            <th>创建时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
<?php
$NowDate = date("Y-m-d",time());
if($class_list){
    foreach($class_list as $value){

        if($value['QUESTION_SATUS'] == 0){
            $nowSatus = "无效";
        }else if($value['QUESTION_SATUS'] == 1){
            $nowSatus = "有效";
        }
        if($value['QUESTION_BEGIN_DATE']>$NowDate){
            $nowSatus = "尚未开始活动";
        }
        if($value['QUESTION_END_DATE']<$NowDate){
            $nowSatus = "活动过期";
        }
        $winCount = json_decode($value['QUESTION_WIN_COUNT']);
        echo "<tbody><tr>
            <td>$value[MASTER_ID]</td>
            <td>$value[QUESTION_TITLE]</td>
            <td>$value[QUESTION_SHOW_COUNT]</td>
            <td>$winCount[0]</td>
            <td>$value[QUESTION_BEGIN_DATE]</td>
            <td>$value[QUESTION_END_DATE]</td>
            <td>$value[QUESTION_CLASS]</td>
            <td>$value[QUESTION_INSERT_DATETIME]</td>
            <td>$nowSatus</td>
            <td>
            <a href='question_textSet.php?action=edit&masterID=$value[MASTER_ID]'>修改</a>
            <a href='javascript:isDelete($value[MASTER_ID]);'>删除</a>&nbsp
            </td>
        <tr></tbody>";
    }
}

else{
    echo "<tbody><tr><td colspan=10>无记录</td></tr></tbody>";
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
    multi(pagecount,pagesize,currentpage,showCount,"question_master_manager");
    $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
});

function isDelete(id){
    if(confirm("确认删除吗？")){
        location.href='question_master_manager.php?action=del&masterID='+id;
    }else{
        return false;
    }
}
</script>
</body>
</html>
