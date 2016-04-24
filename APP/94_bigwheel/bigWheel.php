<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--设置显示的高宽，缩放倍数等信息-->
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<!--网站开启对web app程序的支持-->  
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- 在web app应用下状态条（屏幕顶部条）的颜色,默认值为default（白色），可以定为black（黑色）和black-translucent（灰色半透明）-->
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--使设备浏览网页时对数字不启用电话功能-->  
<meta name="format-detection" content="telephone=no">

<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_GET["openid"]);
$weixinID = addslashes($_GET["weixinID"]);  //weixinID

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

isVipByOpenid($openid,$weixinID,"94_bigwheel/bigWheel.php");

$config = getConfigWithMMC($weixinID);
//判断基础信息是否取得成功
if($config == '' || empty($config)){
    echo "取得配置信息失败，请确认！";
    exit;
}
$weixinName = $config['CONFIG_VIP_NAME'];

?>
<head>
<title>幸运大转盘抽奖</title>
<link href="css/activity-style.css" rel="stylesheet" type="text/css">
</head>
<body class="activity-lottery-winning" >
<?php
$nowDate = date("Y-m-d",time());
$sql = "select * from  bigWheel_main
        where bigWheel_beginDate <= '$nowDate'
        AND bigWheel_endDate >= '$nowDate'
        AND bigWheel_isDeleted = 0
        and WEIXIN_ID = $weixinID
        order by bigWheel_id desc";
$bigWheelMainInfo = getlineBySql($sql);
if(!$bigWheelMainInfo){
?>		    
    <div class="main" >
        <script type="text/javascript">
            var loadingObj = new loading(document.getElementById('loading'),{radius:20,circleLineWidth:8});
            loadingObj.show();
        </script>
    <div class="content"  >
        <div class="boxcontent boxyellow">
            <div class="box">
                <div class="title-green"><span>奖项设置：</span></div>
                    <div class="Detail">
                        <p>亲，感谢您参与大转盘活动！但是</p>
                        <p>当前还没有活动，敬请期待哟。。。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 	
    exit;
    }else{
        $bigWheel_detailInfo_title = json_decode($bigWheelMainInfo["bigWheel_detailInfo_title"]);
        $bigWheel_detailInfo_description = json_decode($bigWheelMainInfo["bigWheel_detailInfo_description"]);
        $bigWheel_detailInfo_count = json_decode($bigWheelMainInfo["bigWheel_detailInfo_count"]);
    }
?>
    <div class="main" >
      <script type="text/javascript">
        var loadingObj = new loading(document.getElementById('loading'),{radius:20,circleLineWidth:8});
        loadingObj.show();
      </script>
      <div id="outercont"  >
        <div id="outer-cont">
          <div id="outer"><img src=<?php echo $bigWheelMainInfo['bigWheel_migPath']?>></div>
        </div>
        <div id="inner-cont">
          <div id="inner"><img src=<?php echo $bigWheelMainInfo['bigWheel_migPathInner']?>></div>
        </div>
      </div>
      <div class="content">
        <div class="boxcontent boxyellow" id="result" style="display:none" >
          <div class="box">
            <div class="title-orange"><span>恭喜你中奖了</span></div>
            <div class="Detail">
              <p>亲爱的会员：<span class="red" id="name" ></span></p>
              <p>手机号码：<span class="red" id="tel"></span></p>
              <p>你中了：<span class="red" id="prizelevel" ></span></p>
              <p>奖品为：<span class="red" id="prizeDescription" ></span></p>
              <p class="red" id="red"></p>
              <p>SN码：<span class="red" id="sncode"></span></p>
            </div>
          </div>
        </div>
        <div class="boxcontent boxyellow" id="winnedResult" style="display:none" >
          <div class="box">
            <div class="title-orange"><span>已中过奖咯</span></div>
            <div class="Detail">
              <p>亲爱的会员：<span class="red" id="winnedname" ></span></p>
              <p>手机号码：<span class="red" id="winnedtel"></span></p>
              <p>你已经中过了：<span class="red" id="winnedprizelevel" ></span></p>
              <p>奖品为：<span class="red" id="winnedprizeDescription" ></span></p>
              <p>SN码：<span class="red" id="winnedsncode"></span></p>
              <p>中奖时间：<span class="red" id="winnedDateTime"></span></p>
            </div>
          </div>
        </div>
        <div class="boxcontent boxyellow" id="maxtimesResult" style="display:none" >
          <div class="box">
            <div class="title-orange"><span>注意啦</span></div>
            <div class="Detail">
              <p>免费和<?php echo $weixinName;?>次数都用完啦：<span class="red" id="LastmaxtimesCount" ></span></p>
              <p><span class="red">亲，你的<?php echo $weixinName;?>已经不够了哦，赶紧赚取<?php echo $weixinName;?>吧！</span></p>
            </div>
          </div>
        </div>
        <div class="boxcontent boxyellow" id="noDataResult" style="display:none" >
          <div class="box">
            <div class="title-orange"><span>注意啦</span></div>
            <div class="Detail">
              <p><span class="red">取得数据失败，请重新进入！</span></p>
            </div>
          </div>
        </div>
        <div class="boxcontent boxyellow" id="NowIntegralCount" style="display:none" >
          <div class="box">
            <div class="title-orange"><span>亲爱的会员</span></div>
            <div class="Detail">
              <p><span class="red">您正在使用<?php echo $weixinName;?>进行大转盘活动：</span></p>
              <p><span class="red">目前的<?php echo $weixinName;?>为：</span><span class="red" id = "thisVipIntegral">分</span></p>
            </div>
          </div>
        </div>
        <div class="boxcontent boxyellow" id = "detailInfo">
          <div class="box">
            <div class="title-green"><span>奖项设置：</span></div>
            <div class="Detail">
        <?php 
            $detailCount = count($bigWheel_detailInfo_title);
            for($i = 0; $i < $detailCount; $i++){
                $titleArr[$i] = $bigWheel_detailInfo_title[$i];
        ?>	
              <p><?php echo $bigWheel_detailInfo_title[$i];?>：
                    <?php echo $bigWheel_detailInfo_description[$i];?> &nbsp &nbsp 数量：
                    <?php echo $bigWheel_detailInfo_count[$i]?> 
              </p>
        <?php 
            }
        ?>	
            </div>
          </div>
        </div>
        <div class="boxcontent boxyellow" id = "defaultInfo">
          <div class="box">
            <div class="title-green"  id = "beforBigWheelInfo">活动说明：</div>
            <div class="Detail">
              <div id = "OKInfo1">
                  <p>每人每天免费可以转： <?php echo $bigWheelMainInfo['bigWheel_times']?> 次 </p>
                  <p>免费次数完后还可以使用<?php echo $weixinName;?>进行，</p>
                  <p>单次需要： <?php echo $bigWheelMainInfo['bigWheel_Integral']?> 分 </p>
                  <p>亲，大奖转出来，祝您好运哦！!  </p>
                  <p><span class="red" id="info"></span></p>
              </div>    
              <div id = "OKInfo" style = "display:none">
              <p>领奖地址：<span class="red" id="adress" ></span></p>
              <p>领奖过期日期：<span class="red" id="expirationDate"></span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
		
    <script src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
    <script src="../js/alert.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
    <script type="text/javascript">
    NoShowRightBtn();
    //设置动画刷新
    $(function() {    
    window.requestAnimFrame = (function() {
      return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || 
            window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
      function(callback) {
        window.setTimeout(callback, 1000 / 60)
      }
    })();
    var totalAngle  = 0;
    var steps = [];
    
    var thisAngle =  <?php echo $detailCount?>;
    switch(thisAngle){
        case 1:
            var loseAngle = [36];
            var winAngle = [6];
            break;
        case 2:
            var loseAngle = [36, 96];
            var winAngle = [6, 66];
            break;
        case 3:
            var loseAngle = [36, 96, 156];
            var winAngle = [6, 66, 126];
            break;
        case 4:
            var loseAngle = [36, 96, 156, 216];
            var winAngle = [6, 66, 126,186];
            break;
        case 5:
            var loseAngle = [36, 96, 156, 216, 276];
            var winAngle = [6, 66, 126,186,246];
            break;
        case 6:
            var loseAngle = [36, 96, 156, 216, 276,336];
            var winAngle = [6, 66, 126,186,246,306];
            break;				
    }
    var prizeLevel;
    var prizeDescription;
    var sncode;
    var tel;
    var name;
    var info;
    var adress;
    var expirationDate;
    var msg;
    var dataCounts = 0;
    var integralNum =  <?php echo $bigWheelMainInfo['bigWheel_Integral']?>;
    var isFreeEnd = 0;
    var count = 0;
    var isWinning = 0;
    var now = 0;
    var a = 0.01;
    var outter, inner, timer, running = false;
    function countSteps() {
      var t = Math.sqrt(2 * totalAngle / a);
      var v = a * t;
      for (var i = 0; i < t; i++) {
        steps.push((2 * v * i - a * i * i) / 2)
      }
      steps.push(totalAngle)
    }
      //实现转盘旋转效果
    function step() {
        //Safari and Chrome用
      outter.style.webkitTransform = 'rotate(' + steps[now++] + 'deg)';
        //Firefox用
      outter.style.MozTransform = 'rotate(' + steps[now++] + 'deg)';
        //Opera用
      outter.style.oTransform = 'rotate(' + steps[now++] + 'deg)';
        //IE9用
      outter.style.msTransform = 'rotate(' + steps[now++] + 'deg)';
      if (now < steps.length) {
        requestAnimFrame(step)
      } else {
        running = false;
        setTimeout(function() {
          if (prizeLevel != null) {
            var levelName = <?php echo json_encode($titleArr)?>;
            
            //隐藏本来的【奖项设置】框：
            $("#detailInfo").hide();
            
            //隐藏本来的【活动说明】框
            $("#beforBigWheelInfo").hide();
            
            //设置中奖结果，并显示【恭喜你中奖了】框
            $("#name").text(name);
            $("#tel").text(tel);
            $("#prizelevel").text(prizeLevel);
            $("#prizeDescription").text(prizeDescription);
            $("#sncode").text(sncode);
            $("#info").text(msg);
            
            $("#adress").text(adress);
            $("#OKInfo").show();
            $("#OKInfo1").hide();
            $("#expirationDate").text(expirationDate);
            
            $("#result").slideToggle(500);  //显示中奖结果
            $("#outercont").slideUp(500)    //隐藏转盘
          } else {
            alert("亲，继续努力哦！")
          }
        },
        200)
      }
    }
    function start(deg) {
      deg = deg || loseAngle[parseInt(loseAngle.length * Math.random())];
      running = true;
      clearInterval(timer);
      totalAngle  = 360 * 1 + deg;
      steps = [];
      now = 0;
      countSteps();
      requestAnimFrame(step)
    }
    window.start = start;
    outter = document.getElementById('outer');
    inner = document.getElementById('inner');
    i = 10;
    $("#inner").click(function() {
      if (running) return;
      //没有获得中奖json返回，让用户退出
      if (prizeLevel != null) {
        alert("亲，你不能再参加本次活动了喔！下次再来吧~");
        $("#outercont").slideUp(500);
        return
      }
    $.ajax({
        url: "data.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>",
        type: "POST",
        dataType: "json",
        data: {
          "bigWheelID": <?php echo $bigWheelMainInfo['bigWheel_id']?>
        },
        beforeSend: function() {
          running = true;
          
          timer = setInterval(function() {
            i += 5;
            outter.style.webkitTransform = 'rotate(' + i + 'deg)';
            outter.style.MozTransform = 'rotate(' + i + 'deg)'
          },
          1)
        },
        success: function(data){
          if (data.status == "noData"){
            clearInterval(timer);
            $("#outercont").slideUp(500);
            $("#detailInfo").hide();
            $("#defaultInfo").hide();
            $("#noDataResult").slideToggle(500);
            running = false;
            return;
            
          }
          //该会员已经中过奖
          if (data.status == "isWinning") {
            clearInterval(timer);
            $("#outercont").slideUp(500);
            
            $("#detailInfo").hide();
            
            //隐藏本来的活动说明框
            $("#beforBigWheelInfo").hide();
            
            $("#winnedname").text(data.name);
            $("#winnedtel").text(data.tel);				
            $("#winnedprizelevel").text(data.prizelevel);
            $("#winnedprizeDescription").text(data.prizeDescription);
            $("#winnedsncode").text(data.SN);
            $("#winnedDateTime").text(data.winnedDatetime);
            $("#info").text(data.message);
            $("#adress").text(data.adress);
            $("#expirationDate").text(data.expirationDate);
            $("#OKInfo1").hide();
            $("#OKInfo").show();
            $("#winnedResult").slideToggle(500);  //显示中奖结果
            $("#outercont").slideUp(500);    //隐藏转盘
            
            return;
          }
          
          //达到免费最大次数的时候，进行flag设置，以便使兑换框出现
          if(data.status == "max_times"){
            if(confirm("亲，您的免费次数已经结束了噢，接下来您还可以使用<?php echo $weixinName;?>继续哟！\n每次需要"+integralNum+"个<?php echo $weixinName;?>")){
                
            }else{
                
            }
            //隐藏本来的活动说明框
            $("#defaultInfo").hide();
            
            //显示正在使用积分大转盘框
            $("#thisVipIntegral").text(data.nowIntegralData);
            $("#NowIntegralCount").show();
            
            clearInterval(timer);
            running = false;
            return;
          }
          if(data.status == "max_times2"){
            //隐藏本来的活动说明框
            $("#defaultInfo").hide();
           
            //显示正在使用积分大转盘框
            $("#thisVipIntegral").text(data.nowIntegralData);
            $("#NowIntegralCount").show();
            
            
          }
          //积分不够一次大转盘时
          if(data.status == "NoEnoughIntegral"){
            clearInterval(timer);
            
            $("#outercont").slideUp(500);
            $("#detailInfo").hide();
            $("#defaultInfo").hide();
            $("#maxtimesResult").slideToggle(500);
            $("#thisVipIntegral").text(data.nowIntegralData);
            $("#NowIntegralCount").show();
            running = false;
            return;
          }
          
          //本次中奖
          if (data.status == "ok"){
            clearInterval(timer);
            prizeLevel = data.prizelevel;
            prizeDescription = data.prizeDescription;
            sncode = data.SN;
            tel = data.tel;
            name = data.name;
            adress = data.adress;
            expirationDate = data.expirationDate;
            msg = data.message;

            start(winAngle[data.fotDeg]);
            
            return
          }
          //未中奖则累加次数
          running = false;
          count++
          prizeLevel = null;
          start()
        },
        //未获取json返回时
        error: function() {
          prizeLevel = null;
          start();
          running = false;
          count++
        },
        timeout: 4000
      })
    })
    });
    </script>
</body>
</html>