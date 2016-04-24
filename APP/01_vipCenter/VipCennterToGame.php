<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<meta name="Generator" content="Fortune v1.0.0">
<meta name="format-detection" content="telephone=no">
<link href="css/footer.css" rel="stylesheet" type="text/css">
<link href="./css/style.css?v=20150826" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="./css/sweet-alert.css">
<head> 
<title>会员中心</title>
</head>	
<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
  echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

//$openid = myURLDecode(addslashes($_GET["openid"])); // 追加加密解密
//$weixinID = myURLDecode(addslashes($_GET["weixinID"]));  // 追加加密解密

$openid = addslashes($_GET["openid"]); // 追加加密解密
$weixinID = addslashes($_GET["weixinID"]);  // 追加加密解密

$config =  getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
  echo "取得配置信息失败，请确认！";
  exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

isVipByOpenid($openid,$weixinID,"01_vipCenter/VipCennter.php");

$vipInfoArr = vipInfo($openid,$weixinID);

$thisVipIntegral = $vipInfoArr[0]['Vip_integral'];


//取得该会员的积分排名
$sql = "select rowno from
          (select Vip_openid,
                  Vip_id,
                  Vip_integral,
                  (@rowno:=@rowno+1) as rowno
          from Vip,
          (select (@rowno:=0)) b
          where WEIXIN_ID = $weixinID
          AND Vip_isDeleted = 0
          order by Vip_integral desc,
                  Vip_createtime asc) c
          where Vip_openid ='$openid'";
$getIntegralRank = getVarBySql($sql);

?>
<body>
<div id="mappContainer">
  <section id="card_ctn">
    <div class="bk1"></div>
    <div class="cont">
      <div class="card">
        <div class="front">
          <figure class="fg" style="background-image:url(./img/vipCard.png);">
            <figcaption class="fc">
              <span class="cname" style="color:#957426;"></span>
              <span class="t" style="color:#aaa;text-shadow:#000 0 -1px;"></span>
              <span class="n" style="color: rgb(210, 210, 210); text-shadow: rgb(0, 0, 0) 0px -1px; ">NO.<?php echo $vipInfoArr[0]['Vip_id'];?></span>
            </figcaption>
          </figure>
        </div>
        <div class="back">
          <figure class="fg" style="background-image:url(./img/4b.jpg);">
            <div class="info">
              <p class="addr">地址：路桥区委宣传部</p>
              <p class="tel"><a class="autotel" onclick="return false;" href="javascript:void(0);">0576-89207054</a></p>
            </div>
            <p class="keywords">路 桥 发 布 中 心</p>
          </figure>
        </div>
      </div>
    </div>
  </section>

  <div id="vip" style="">
    <small><b><em>亲爱的会员,您的目前<?php echo $weixinName?>数为:<?php echo $thisVipIntegral;?></em></b></small>
    <small><em><?php echo $weixinName?>排名为: 第 <?php echo $getIntegralRank?> 名</em></small>
    <div class = "link">
      <div class="leftIcon">
          <a href="../02_integralCity/integralCity.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/duihuan.png"></a>
          <p><?php echo $weixinName;?>兑换</p>
      </div>
      <div class="rightIcon">
          <a href="../95_scratchcard/scratchcard.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/choujiang.png"></a>
          <p><?php echo $weixinName;?>抽奖</p>
      </div>
    </div>
    </br>
    <div class = "link">
      <div class="leftIcon">
        <a href="../08_iphonEvent/iphoneEvent.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/duihuan.png"></a>
        <!--<p>--><?php //echo $weixinName;?><!--印章排行</p>-->
        <p>印章排行</p>
      </div>
      <div class="rightIcon">
        <a href="../08_iphonEvent/flowerCity.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/choujiang.png"></a>
        <!--<p>--><?php //echo $weixinName;?><!--印章兑奖</p>-->
        <p>印章兑奖</p>
      </div>
    </div>
    </br>
    <div class = "link">
      <div class="leftIcon">
          <a onclick="return false;" href="javascript:void(0)"><img id = "moreInfo" class = "leftImg" src="./img/more.png"></a>
          <p>更多</p>
      </div>
      <div class="rightIcon">
      </div>
    </div>
  </div>
  <!-- Footer start -->
    <footer class="am-footer am-footer-default">
      <div class="am-footer-miscs">
        <p>由
          <a onclick="return false;" href="javascript:void(0)" title="路桥区网络新闻中心" target="_blank" class="">路桥区网络新闻中心</a>提供技术支持</p>
        <p>CopyRight@2015 路桥发布</p>
      </div>
    </footer>
	<!-- Footer end -->
</div>

<script src="./js/sweet-alert.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="./js/_meishi_wei_html5_v3.2.9.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script>
$(function(){
    //屏蔽右上角分享按钮
    NoShowRightBtn();
    
    $("#moreInfo").click(function(){
        alert("更多玩法，推出中...")
    })
})
</script>
</body>
</html>