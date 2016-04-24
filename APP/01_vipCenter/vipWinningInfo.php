<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<link href="./css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>

<!--使设备浏览网页时对数字不启用电话功能-->  
<meta name="format-detection" content="telephone=no">

<style>
.bg{ background-color:#BFCFFE;}
.bgMain{ background-color:#D0CACA;}
</style>
</head>
<body>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET['openid']);
$weixinID = addslashes($_GET['weixinID']);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$vipInfo = vipInfo($openid,$weixinID);
?>
<div data-role="page">
<div data-role="header" data-position="fixed">
    <tr class = "bgMain"><td>
        亲爱的会员:<?php echo $vipInfo[0]['Vip_name']?></br>
        手机号码:<?php echo $vipInfo[0]['Vip_tel']?>
    </td></tr>
</div><!-- /header -->
<?php
$sql = "select * from bill
        where Bill_Status = 0
        and Bill_openid = '$openid'
        AND WEIXIN_ID = $weixinID
        order by Bill_id";
$billInfo = getDataBySql($sql);

$billInfoCount = count($billInfo);

if(!$billInfo){
?>        
    <div data-role="content" alin> 
        <p>尚未有中奖信息！</p>
    </div>  
<?php         
}else{
?>
    <div data-role="content"> 
        <table border=1 width = "100%" align="center">
        <form name="customersUpdateForm" method="POST" action="?" data-ajax="false" data-transition="flip">
            <?php 
                for($i = 0;$i<$billInfoCount;$i++){
            ?>	
                <tr  class="bg"><td><p>
                第<?php echo ($i+1)?>条中奖信息:
                </tr></td></p>
                <tr  class="bg"><td><p>
                <?php 
                    if($billInfo[$i]['Bill_type'] == "001"){
                        $Bill_type = "积分商城";
                    }else if($billInfo[$i]['Bill_type'] == "002"){
                        $Bill_type = "大转盘";
                    }else if($billInfo[$i]['Bill_type'] == "003"){
                        $Bill_type = "刮刮卡";
                    }else if($billInfo[$i]['Bill_type'] == "004"){
                        $Bill_type = "印章";
                    }
                ?>
                中奖类型 : <?php echo $Bill_type; ?>
                </tr></td></p>
                <tr class="bg"><td><p>
                中奖内容 : <?php echo $billInfo[$i]['Bill_GoodsName']; ?>	
                </tr></td></p>
                <tr class="bg"><td><p>
                中奖SN码 : <?php echo $billInfo[$i]['Bill_SN']; ?>
                </tr></td></p>
                <tr class="bg"><td><p>
                中奖时间 : <?php echo $billInfo[$i]['Bill_insertDate']; ?>
                </tr></td></p>
                <tr class="bg"><td><p>
                活动开始日期 : <?php echo $billInfo[$i]['Bill_goods_beginDate']; ?>
                </tr></td></p>
                <tr class="bg"><td><p>
                活动结束日期 : <?php echo $billInfo[$i]['Bill_goods_endDate']; ?>
                </tr></td></p>
                <tr class="bg"><td><p>
                奖品兑换截止日期 : <?php echo $billInfo[$i]['Bill_goods_expirationDate']; ?>
                </tr></td></p>
                <tr><td>
                    </br>
                </td></tr>
            <?php 
            }
            ?>
        </form>
        </table>

    </div>  
<?php 
}
?>    
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
</script>
</body>
</html>