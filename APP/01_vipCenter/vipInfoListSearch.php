<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

$config =  getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$limitNo = 20;

$sql = "select * from Vip
        where WEIXIN_ID = $weixinID
        AND Vip_isDeleted = 0
        order by Vip_integral DESC,
                 Vip_createtime ASC
        limit ".$limitNo;
$vipInfoList = getDataBySql($sql);

?>
<!DOCTYPE html>
<html>
<head>
<title>排行榜</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <br>
            <?php
            $count = count($vipInfoList);
            if($count <= 0){
            ?>
                <div class="well">
                    <span class="text-danger"><small>没有数据</small></span>
                </div>
            <?php
            }else{
                for($i = 0; $i<$count; $i++){
                ?>
                    <div class="well well-sm">
                        <span class="text-warning"><small>排名：<?php echo $i + 1;?></small></span><br>
                        <span class="text-warning"><small>姓名：<?php echo $vipInfoList[$i]['Vip_name']?></small></span><br>
                        <span class="text-warning"><small><?php echo $weixinName;?>：<?php echo $vipInfoList[$i]['Vip_integral']?></small></span>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
</script>
</body>
</html>
