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
    $question_id=intval(addslashes($_GET["question_id"])); 
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
	
	$sql = "update question_tb
            set status=0,editTime = '$nowtime'
            where question_id=$question_id
            and WEIXIN_ID=$weixinID";
    
    $errno = SaeRunSql($sql);
    
    if( $errno != 0 ){
        echo "<script>alert('删除数据失败！');history.back();</Script>";
        exit;
    }  
    echo "<script>alert('操作成功！');location='question_manager.php?page=$page&weixinID=$weixinID';</Script>";
    exit;    
 
       
}    
//列表数据获取、分页

//计算总数
$sql= "select COUNT(*) from question_tb
       where status = 1
       AND WEIXIN_ID = $weixinID";
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
    $sql = "select * from question_tb
            where WEIXIN_ID = $weixinID
            AND status = 1
            order by question_id asc
            limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}
?>
<!--页面名称-->
<h3>会员答题 管理<a href="question_add.php?page=<?php echo $page;?>">新增题目>></a></h3>
<!--列表开始-->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>序号</th>
            <th>内容</th>
            <th>分类</th>
            <th>图片地址</th>
            <th>选项A</th>
            <th>选项B</th>
            <th>选项C</th>
            <th>选项D</th>
            <th>答案正确</th>
            <th>操作</th>
        </tr>
    </thead>
<?php
if($class_list){
    foreach($class_list as $value){
        if(($value['question_img'] == "") || ($value['question_img'] == "imgPath error") ){
            $imgUrl = "未设置图片";
        }else{
            $imgUrl = $value['question_img'];
        }
        echo "<tbody><tr>
            <td>$value[question_id]</td>
            <td><textarea rows='2' cols='20'>$value[question_subject]</textarea></td>
            <td>$value[question_class_title]</td>
            <td><textarea rows='2' cols='20'>$imgUrl</textarea></td>
            <td><textarea rows='2' cols='15'>$value[question_optionsA]</textarea></td>
            <td><textarea rows='2' cols='15'>$value[question_optionsB]</textarea></td>
            <td><textarea rows='2' cols='15'>$value[question_optionsC]</textarea></td>
            <td><textarea rows='2' cols='15'>$value[question_optionsD]</textarea></td>
            <td>$value[question_true]</td>
            <td>
            <a href='javascript:isDelete($value[question_id]);'>删除</a>&nbsp
            <a href='question_add.php?action=edit&question_id=$value[question_id]&page=$page'>修改</a>
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
    multi(pagecount,pagesize,currentpage,showCount,"question_manager");
    $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
});
function isDelete(id){
    if(confirm("确认删除吗？")){
        location.href='question_manager.php?action=del&question_id='+id;
    }else{
        return false;
    }
}
</script>
</body>
</html>
