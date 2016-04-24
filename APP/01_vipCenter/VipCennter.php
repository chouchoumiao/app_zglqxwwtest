<?php
header("Content-type:text/html;charset=utf-8");
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}
$openid = addslashes($_GET["openid"]); // 追加加密解密
$weixinID = addslashes($_GET["weixinID"]);  // 追加加密解密

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

isVipByOpenid($openid,$weixinID,"01_vipCenter/VipCennter.php");

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

$vipInfoArr = vipInfo($openid,$weixinID);

$thisVipNmae = $vipInfoArr[0]['Vip_name'];
$thisVipIntegral = $vipInfoArr[0]['Vip_integral'];
$thisVipSignedDayTime = $vipInfoArr[0]['Vip_isSignedDayTime'];
$thisSignedDate = date("Y-m-d",time());

if ((strtotime($thisSignedDate) - strtotime($thisVipSignedDayTime))/86400 >= 1){
    $isSigned = 0;
}else{
    $isSigned = 1;
}

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
<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="format-detection" content="telephone=no">
<link href="./css/style.css?v=20150906" rel="stylesheet" type="text/css">
<link href="css/footer.css" rel="stylesheet" type="text/css">
<head>
    <title>会员中心</title>
</head>
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
    <br>
    <div id="vip">
        <small><b><em>亲爱的会员,您的目前<?php echo $weixinName?>数为:<?php echo $thisVipIntegral;?></em></b></small>
        <small><em><?php echo $weixinName?>排名为: 第 <?php echo $getIntegralRank?> 名</em></small>
        <div class="linkBox">
            <div class = "link">
                <div class="leftIcon6">
                    <a href="VipInfoShow.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/icon_vipInfo.png"></a>
                    <p>我的信息</p>
                </div>
                <?php
                if(0 == $isSigned){
                    ?>
                    <div class="leftIcon6">
                        <a href="vipdaliy.php?openid=<?php echo $openid;?>&integral=<?php echo $thisVipIntegral;?>&weixinID=<?php echo $weixinID?>"><img src="./img/icon_qiandao.png"></a>
                        <p>每日签到</p>
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="leftIcon6" id = "modelDiv">
                        <a onclick="return false;" href="javascript:void(0);"><img src="./img/icon_qiandaoDisable.png"></a>
                        <p>您已签到</p>
                    </div>
                    <?php
                }
                ?>
                <div class="rightIcon6">
                    <a href="../07_forwardingGift/forwardingGift.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/icon_fenxiang.png"></a>
                    <p>分享有礼</p>
                </div>
            </div>
            </br>
            <div class = "link">
                <div class="leftIcon6">
                    <a href="javascript:;" onclick="showMask()" ><img src="./img/icon_guize.png"></a>
                    <p><?php echo $weixinName?>规则</p>
                </div>
                <div class="leftIcon6">
                    <a href="./VipCennterToGame.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/icon_shuoming.png"></a>
                    <p>有奖活动</p>
                </div>
                <div class="rightIcon6">
                    <a href="vipInfoListSearch.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>"><img class = "leftImg" src="./img/icon_paiming.png"></a>
                    <p>排行榜</p>
                </div>

            </div>
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
<div id="mask" class="mask" style = "display:none">
    <span style="color: #3d74ef"><h3>一、<?php echo $weixinName?>获得</h3></span>
    <span>1．初次注册会员。完成个人信息填写即可获得5个<?php echo $weixinName?>（注：个人信息涉及到奖品领取等线下操作）</span>
    <span>2．每日签到。在每日签到页面中，可以通过签到获取1个<?php echo $weixinName?>，输入签到码，可获得5个<?php echo $weixinName?>。每日可签到一次。签到码的获得：可在“路桥发布”最近发布的某篇文章底部找到。注：签到码有时间限制，过期失效。</span>
    <span>3．邀请好友。邀请好友加入“路桥发布”会员系统，并填写你的会员卡号作为邀请码即可。邀请人获得3个<?php echo $weixinName?>，被邀请人可额外获得2个<?php echo $weixinName?>。</span>

    <span style="color: #3d74ef"><h3>二、奖品领取</h3></span>
    <span>实物奖品，请到路桥区网络新闻中心领取，领取前先预约。地址：台州市路桥区西路桥大道201号（新华书店六楼），上班时间：周一至周五，上午8：30-12：00，下午14：30-17：30。预约电话：0576-89207054</span>
</div>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="./js/_meishi_wei_html5_v3.2.9.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js?v=20160102"></script>
<script type="text/javascript">

    //去除分享按钮
    NoShowRightBtn();

    //显示遮罩层    
    function showMask(){
        $("#mask").css("height",$(document).outerHeight(true));
        $("#mask").css("width",$(document).width());
        $("#mask").show();
    }
    //隐藏遮罩层 
    $("#mask").click(function(){
        $("#mask").hide();
    });

</script>
</body>
</html>