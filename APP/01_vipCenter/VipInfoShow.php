<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="Generator" content="Fortune v1.0.0">
<meta name="format-detection" content="telephone=no">
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<link href="./css/style.css" rel="stylesheet" type="text/css">
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">
<link href="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" rel="stylesheet">
<head>
<title>会员中心</title>
</head>	
<?php
//判断是否取得openid 和 是否为会员判定
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);  //weixinID

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$vipInfoArr = vipInfo($openid,$weixinID);

$thisVipID = $vipInfoArr[0]['Vip_id '];  //追加推荐人分享链接
$thisVipNmae = $vipInfoArr[0]['Vip_name'];
$thisVipTel = $vipInfoArr[0]['Vip_tel'];
$thisVipIntegral = $vipInfoArr[0]['Vip_integral'];
$thisVip_comment = $vipInfoArr[0]['Vip_comment'];

//追加显示印章
$thisVipID = $vipInfoArr[0]['Vip_id'];
$sql = "select count(*) from iphoneEvent where ipE_referee_vipID = $thisVipID";
$flowerCount = getVarBySql($sql);

//从bill表中获取已经中奖的印章数
$sqlFromBill = "select SUM(Bill_integral) from bill
                where Bill_openid = '$openid'
                AND WEIXIN_ID = $weixinID
                AND bill_type = '004'";
$afterBill = getVarBySql($sqlFromBill);

$nowflowerCount = $flowerCount - $afterBill;

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

//取得建言献策的抽奖次数
$adviceSql = "select count(*) from adviceInfo
              where WEIXIN_ID = $weixinID
              AND ADVICE_OPENID = '$openid'
              AND ADVICE_EVENT = 1";
$adviceCount = intval(getVarBySql($adviceSql));

//取得刮刮卡使用次数
$sql = "select scratchcard_userCount from scratchcard_user
        where scratchcard_userIsAllow = 1
        AND scratchcard_userOpenid = '$openid'
        AND scratchcard_id = 159
        AND WEIXIN_ID = $weixinID";
$scratchcardedTimes = intval(getVarBySql($sql));

$count = $adviceCount - $scratchcardedTimes;
?>
<body>
<div id="loading" style="display: none; ">
  <div class="bk"></div>
  <div class="cont">
  <img src="img/loading.gif" alt="loading...">正在加载...</div>
</div>

</br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="demo-type-example">
                <p>如信息输入错误，修改请联系微信号xiangyu426。关注“路桥发布”微信获取更多<?php echo $weixinName;?>
                    <a href = "http://mp.weixin.qq.com/s?__biz=MzA5MjAwNTg5MA==&mid=202313729&idx=1&sn=771fb385b364df578067e9b3307eba45#rd" id="blue">点我关注</a>
                </p>
      		</div>
            </br>
            <div id = "baseInfo">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">昵称</span>
                        <input type="text" class="form-control input-lg" placeholder=<?php echo $thisVipNmae;?> disabled="disabled">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input type="text" class="form-control input-lg" placeholder=<?php echo $thisVipTel;?> disabled="disabled">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo $weixinName?></span>
                        <input type="text" class="form-control input-lg" placeholder=<?php echo $thisVipIntegral;?> disabled="disabled">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">印章总数</span>
                        <input type="text" class="form-control input-lg" placeholder=<?php echo $flowerCount;?> disabled="disabled">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">剩余印章</span>
                        <input type="text" class="form-control input-lg" placeholder=<?php echo $nowflowerCount;?> disabled="disabled">
                    </div>
                </div>
                <?php
                    if($count > 0){
                ?>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-block" onclick = "location.href='../95_scratchcard/scratchcard.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>'">建言献策抽奖<?php echo $count;?>次</button>
                        </div>
                <?php
                    }
                    if($thisVip_comment != "referrer"){
                ?>
                    <div class="form-group">
                            <button type="button" class="btn btn-warning btn-block" id = "referrerBtn">点我补登推荐人</button>
                    </div>
                <?php
                    }
                ?>
                <div class="form-group">
                        <button type="button" class="btn btn-success btn-block" onclick = "location.href='vipWinningInfo.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>'">查看中奖信息</button>
                </div>
                <!--<div class="form-group">-->
                <!--    <button type="button" class="btn btn-danger btn-block" onclick="return WeiXinShareBtn();">推荐其他人</button>-->
                <!--</div>-->
            </div>
            <div id = "referrerInfo" style = "display:none">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">推荐人卡号</span>
                        <input type="text" class="form-control input-lg" id = "referrerID">
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-block" id = "referrerSubmit">提交</button>
                    <button type="button" class="btn btn-block disabled" id = "referrerSubmitDoing" style="display:none">正在提交。。。</button>
                </div>
            </div>
        </div>

        </br>
    </div>
</div>

<script src="../js/wx.js?v=20150101"></script>
<script src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js"></script>
<script type="text/javascript">
    NoShowRightBtn();

    $(function(){
        $("#referrerBtn").click(function(){
            $("#baseInfo").hide();
            $("#referrerInfo").show();

        });
        $("#referrerSubmit").click(function(){

            var referrerID = $("#referrerID").val();
            var VipID = <?php echo $vipInfoArr[0]['Vip_id'];?>;

            if(!isNull(referrerID)){
                if (referrerID.length != 8){
                    alert("会员卡号格式错误，请确认！");

                    return false;
                }
            }else{
                alert("会员卡号不能为空");
                return false;
            }

            if(referrerID == VipID){
                alert("不能输入自己的卡号");
                return false;
            }
            $.ajax({
                url:'VipInfoShowData.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID ?>'//改为你的动态页
                ,type:"POST"
                ,data:{
                    "thisVipIntegral":<?php echo $thisVipIntegral ?>,
                    "referrerID":referrerID
                }
                ,dataType:"json"
                ,beforeSend: function(){
                    $("#referrerSubmit").hide();
                    $("#referrerSubmitDoing").show();
                }
                ,success:function(json){
                    $("#referrerSubmitDoing").hide();
                    alert(json.msg);
                    if(json.success == 0){
                        location='VipCennter.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID ?>';
                    }else{
                        $("#referrerSubmit").show();
                    }
                }
                ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        });
    })
</script>
</body>
</html>