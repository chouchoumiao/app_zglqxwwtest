<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");
isVipByOpenid($openid,$weixinID,"08_iphonEvent/iphoneEvent.php");
//根据iphone活动表中的印章数量进行排名，并关联Vip表取出会员信息
$sql = "SELECT A.Vip_id,
               A.Vip_openid,
               A.Vip_name,
               A.Vip_tel,
               B.flowerCount,
               @rownum := @rownum +1 rownum
        FROM Vip AS A
        INNER JOIN(
            SELECT @rownum :=0,ipE_referee_vipID,count(*) AS flowerCount
            FROM iphoneEvent
            GROUP BY ipE_referee_vipID
            ORDER BY flowerCount DESC
        )  AS B
        WHERE A.Vip_id = B.ipE_referee_vipID";

$OKInfo = getDataBySql($sql);

$sqlCount = count($OKInfo); //取得数据总条数
$vipCount = 0; //本会员的印章数量初始化为0
$rank = 0; //本会员的排名初始化为0

//取得会员的印章排名（根据openid逐个判断，存在的话将本会员的印章数和排名查询出来）
for($i=0;$i<$sqlCount;$i++){
    if($openid == $OKInfo[$i]['Vip_openid']){
        $vipCount = $OKInfo[$i]['flowerCount'];
        $rank = $OKInfo[$i]['rownum'];
    }
}
?>
<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html>
<head>
<title>拉好友赢大奖</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="format-detection" content="telephone=no">
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12" id = "main">
            <h5>拉好友 赢大奖 活动</h5>
            <div class="alert alert-info" id = "baseTitle">
                <p class="text-primary">亲，你以为拉好友 赢大奖 活动只有 印章兑换里的那些奖品吗？错了，那只是保底的奖励，当你拉够<span style="color:#E74C3C">30</span>人时，你将获得<span style="color:#E74C3C">现场抽大奖</span>的机会，iphone6s？iwatch？ipadmini？kindle？...，对大奖中就有他们的身份，详情见<span style="color:#E74C3C">12月31日</span>“路桥发布”微信内容。快加把劲，努力拉好友来关注“路桥发布”吧。</p>
            </div>
            <a class="btn btn-danger btn-block" role="button" data-toggle="collapse" data-target="#NG" aria-expanded="false" aria-controls="collapseExample">
                点击查看您的排名
            </a>
            <div class="collapse" id="NG">
                <?php
                if($rank == 0 || $vipCount == 0){  //如果排名和印章数是初始化的数据，则表示没有本会员的活动信息
                ?>
                    <div class="well well-sm">
                        <span class="text-danger"><small>没有数据</small></span><br>
                    </div>
                <?php
                }else{  //将本会员的排名和印章数显示出来
                ?>
                    <div class="well">
                        <span class="text-danger"><small>排名：<?php echo $rank?></small></span><br>
                        <span class="text-danger"><small>印章：<?php echo $vipCount?></small></span>
                    </div>
                <?php
                }
                ?>
            </div>
            </br>
            <a class="btn btn-warning btn-block" role="button" data-toggle="collapse" data-target="#OK" aria-expanded="false" aria-controls="collapseExample">
               点击查看前20排名
            </a>
            <div class="collapse" id="OK">
                <?php
                //如果活动表中取得的数据为空，则表示没有活动数据
                if($sqlCount <= 0){
                ?>
                    <div class="well well-sm">
                        <span class="text-warning"><small>没有数据</small></span>
                    </div>
                <?php
                }else{
                    if($sqlCount <= 20){ //如果数据总条数小于20，那么则取总条数
                        $thisCount = $sqlCount;
                    }else{  //如果数据总条数大于20，那么总条数设置为20
                        $thisCount = 20;
                    }
                    //将所有的信息打印出来
                    for($i = 0; $i<$thisCount; $i++){
                        $oldTel= $OKInfo[$i]['Vip_tel'];
                        $newTel = substr($oldTel,0,3)."*****".substr($oldTel,8,3);
                    ?>
                        <div class="well">
                            <span class="text-warning"><small>排名：<?php echo $OKInfo[$i]['rownum']?></small></span><br>
                            <span class="text-warning"><small>姓名：<?php echo $OKInfo[$i]['Vip_name']?></small></span><br>
                            <span class="text-warning"><small>手机：<?php echo $newTel?></small></span><br>
                            <span class="text-warning"><small>印章：<?php echo $OKInfo[$i]['flowerCount']?>个</small></span>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
            <br>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
</script>
</html>

