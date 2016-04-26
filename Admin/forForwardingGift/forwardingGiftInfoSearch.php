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
   
//列表数据获取、分页

//计算总数

//取得所有建言的总条数
$sql="select COUNT(*) from forwardingGift where WEIXIN_ID = $weixinID";

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
	$sql = "select * from forwardingGift
            where WEIXIN_ID = $weixinID
            order by id DESC
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
<h3>转发有礼 管理</h3>
<!--列表开始-->
<div style = "min-height:450px">
<table class="table table-bordered" >
    <thead>
        <tr>
            <th>序号</th>
            <th>姓名/昵称</th>
            <th>联系方式</th>
            <th>照片路径</th>
            <th>追加<?php echo $weixinName;?></th>
            <th>回复内容</th>
            <th>上传时间</th>
            <th>当前状态</th>
            <th>编辑</th>
        </tr>
    </thead>
<?php
    if($class_list){
        foreach($class_list as $value){
            $flag =  $value['FORWARDINGGIFT_ISOK'];
            if($flag == 0){
                $isOKFlag = "未审核";
            }else if($flag == 1){
                $isOKFlag = "通过";
            }else if($flag == 2){
                $isOKFlag = "未通过";
            }

            $thisOpenid = $value['FORWARDINGGIFT_OPENID'];

            $sql = "select * from Vip
                    where Vip_openid = '$thisOpenid'
                    and WEIXIN_ID = '$weixinID'
                    and Vip_isDeleted = 0";
            $vipInfo = getLineBySql($sql);

            $id = $value['id'];
            $name = $vipInfo['Vip_name'];
            $tel = $vipInfo['Vip_tel'];
            $imgUrl = $value['FORWARDINGGIFT_IMGURL'];
            $bigImgUrl = $value['FORWARDINGGIFT_BIGIMGURL'];
            $integral = $value['FORWARDINGGIFT_INTEGRAL'];
            $reply = $value['FORWARDINGGIFT_REPLY'];
            $creatTime = $value['FORWARDINGGIFT_CREATETIME'];

            $getStr = $id.','.$page.','.$name.','.$tel.','.$imgUrl.','.$bigImgUrl.','.$integral.','.$reply.','.$creatTime.','.$thisOpenid;

            echo "<tbody><tr><td>$id</td>
                <td>$name</td>
                <td>$tel</td>
                <td><textarea cols='35'>$imgUrl</textarea></td>
                <td>$integral</td>
                <td><textarea cols='35'>$reply</textarea></td>
                <td>$creatTime</td>
                <td>$isOKFlag</td>
                <td>";
            if($flag != 1){
                echo "<a href='forwardingGiftSet.php?str=$getStr'>修改/审核</a>";
            }else{
                echo "<a onclick='return false;' href='javasript:void(0)'>不能编辑</a>";
            }

            echo "</td><tr></tbody>";
        }
    }else{
        echo "<tbody><tr><td colspan=12>无记录</td></tr></tbody>";
    }
?>    
</table>
</div>
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
    multi(pagecount,pagesize,currentpage,showCount,"forwardingGiftInfoSearch");
    $("#showPage ").val(<?php echo $showCount;?>); //用于显示select的选中事件
});  
</script>
</body>
</html>
