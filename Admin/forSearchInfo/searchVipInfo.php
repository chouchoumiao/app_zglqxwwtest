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

//获取操作标识传入
$action = addslashes($_GET["action"]);

//是否删除
if($action=="del"){

    //获取问题ID号传入
    $id=addslashes($_GET["id"]);
    
    $sql = "select Vip_openid from Vip
            where Vip_id = $id
            AND WEIXIN_ID = $weixinID
            AND Vip_isDeleted = 0";
    $openid = getVarBySql($sql);
    
    //获取当前时间
    $nowtime=date("Y/m/d H:i:s",time());
    $sql = "update Vip
            set Vip_isDeleted = 1,
                Vip_edittime = '$nowtime'
            where Vip_id = $id
            AND WEIXIN_ID = $weixinID";
    $errno = SaeRunSql($sql);
    if( $errno != 0 )	{
        echo "<script>alert('会员信息删除失败！');history.back();</Script>";
        exit;
    }else{
        $sql = "update answer_recorded
                set status = 1
                where answer_recorded_openid = '$openid'
                AND WEIXIN_ID = $weixinID";
        $errno = SaeRunSql($sql);
        echo "<script>alert('会员信息删除成功！');location='searchVipInfo.php?page=$page';</Script>";
        exit;    
    }
    
}

//列表数据获取、分页

//计算总数

//取得所有建言的总条数
$sql="select COUNT(*) from Vip Where WEIXIN_ID = $weixinID AND Vip_isDeleted = 0";
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
	$sql = "select * from Vip
            where WEIXIN_ID = $weixinID
            AND Vip_isDeleted = 0
            order by Vip_id DESC
            limit $from_record,$page_num";
    $class_list = getDataBySql($sql);
}

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

?>
<!--页面名称-->
<h3>会员信息 一览</h3>
<!--列表开始-->

<table class="table table-bordered" >
    <thead>
        <tr>
            <th>会员号</th>
            <th>姓名/昵称</th>
            <th>性别</th>
            <th>联系方式</th>
            <th><?php echo $weixinName;?>数</th>
            <th>创建时间</th>
            <th>编辑</th>
        </tr>
    </thead>
<?php
if($class_list){
    foreach($class_list as $value){
        if($value['Vip_sex'] == 1){
            $sexName = "男";
        }else{
            $sexName = "女";
        }
        echo "<tbody><tr><td>$value[Vip_id]</td>
            <td>$value[Vip_name]</td>
            <td>$sexName</td>
            <td>$value[Vip_tel]</td>
            <td>$value[Vip_integral]</td>
            <td>$value[Vip_createtime]</td>
            <td>
            <a href='javascript:isDelete($value[Vip_id]);'>删除</a>
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
        multi(pagecount,pagesize,currentpage,showCount,"searchVipInfo");
        $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
    });
    function isDelete(id){
        if(confirm("确认删除吗？")){
            location.href='searchVipInfo.php?action=del&id='+id;
        }else{
            return false;
        }
    }
</script>
</body>
</html>
