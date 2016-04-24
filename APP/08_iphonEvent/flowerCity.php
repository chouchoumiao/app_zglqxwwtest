<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link href="css/flower.css?v=0840" rel="stylesheet" type="text/css">
<title>印章兑换</title>
</head>
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

isVipByOpenid($openid,$weixinID,"08_iphonEvent/flowerCity.php"); //weixin

//取得当前时间内活动的商品信息
$nowDate = date("Y-m-d",time());
$sql = "select * from  flowerCity_config 
        where flowerCity_fromDate <= '$nowDate' 
        AND flowerCity_endDate >= '$nowDate' 
        AND WEIXIN_ID = $weixinID 
        AND flowerCity_isDeleted = 0 
        order by flowerCity_flowerNum ASC";
$flowerGoodsInfoArr = getDataBySql($sql);

?>
<body>
<div class="price_banner"><img src="img/flowerPro.jpg"></div>
<div class="productbox" id="integralPro">
<?php  
//追加不存在判断 wujiayu
if(!$flowerGoodsInfoArr){
    echo '<div><p>相关奖品将于1月4日放出，敬请期待！</p></div>';
    exit;
}
$count = count($flowerGoodsInfoArr);
for ($i = 0;$i<$count;$i++) {
?>
    <ul>
         <li>
         <div class="pic01box" >
            <div class="pic01" onclick="showMask(<?php echo $i;?>)">
                <img src="<?php echo $flowerGoodsInfoArr[$i]['flowerCity_imgPath'];?>">
            </div>
            <div class="pic01text" align = "center">
                <h2><?php echo $flowerGoodsInfoArr[$i]['flowerCity_name'];?></h2>
                <p>
                    <span style = "float:left; margin-left:6px">印章:<?php echo $flowerGoodsInfoArr[$i]['flowerCity_flowerNum'];?></span>
                    <span style = "float:right; margin-right:6px" >库存:<?php echo $flowerGoodsInfoArr[$i]['flowerCity_stockCount'];?><span>
                </p>
                <p>
                    <?php
                        if($flowerGoodsInfoArr[$i]['flowerCity_stockCount'] <= 0){
                    ?>            
                        <input class="btnsEnd"  id="btnsEnd" type="button" value = "已结束">
                    <?php 
                        }else{
                    ?>	
                        <input class="btns" id="btns" type="button" value = "兑  换" onclick = "return integralGoodsBill(<?php echo $flowerGoodsInfoArr[$i]['id'];?>);">
                    <?php 
                        }
                    ?>
                </p>
            </div>
        </div>
        </li>
    </ul>
    <div id="mask<?php echo $i?>" class="mask" style = "display:none">
        <span style="color: #3d74ef"><h3><?php echo $flowerGoodsInfoArr[$i]['flowerCity_name']?></h3></span>
        <span><?php echo $flowerGoodsInfoArr[$i]['flowerCity_content']?></span>
    </div>
    <?    
    }
    ?>
</div>
<div id="overMask" class= "overMask" style="display:none"></div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="../../Static/JS/CommJS_1.1.js"></script>
<script type="text/javascript">
    NoShowRightBtn();
    function showMask(id){
        //显示商品说明图层
        var o=$("#mask"+id);
        var itop=(document.documentElement.clientHeight-o.height())/2+document.documentElement.scrollTop;
        var ileft=(document.documentElement.clientWidth-o.width())/2+document.documentElement.scrollLeft;
        o.css({
            position:"fixed",
            top:itop+"px",
            left:ileft+"px"
        }).fadeIn();

        //显示图层(全屏)，用于点击该图层使商品说明图层消失
        $("#overMask").css("height",$(document).outerHeight(true));     
        $("#overMask").css("width",$(document).width());
        $("#overMask").show();
    };

    //点击最外层图层后，商品说明图层消失
    $("#overMask").click(function(){ 
        $("#overMask").hide();
        $(".mask").fadeOut(); 
    });

    //提交数据
    function integralGoodsBill(thisIntegralID){
        var a = window.confirm("您要进行兑换么？");
        if(a==true)
        {
            $.ajax({
            url:'flowerJudge.php?openid=<?php echo $openid;?>&weixinID=<?php echo $weixinID?>'//改为你的动态页
            ,type:"POST"
            ,data:{"fromIntegralID":thisIntegralID}//调用json.js类库将json对象转换为对应的JSON结构字符串
            ,dataType: "json"
            ,success:function(json){
                if(json.success == 1){
                    alert(json.msg);
                    location.reload();
                }else{
                    alert(json.msg);
                }

            } 
            ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
            });
        }
    };
</script>
</body>
</html>