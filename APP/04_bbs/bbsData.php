<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
<!--<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>-->

</head>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_REQUEST['thisVip_openidField']);
$weixinID = addslashes($_REQUEST['thisVip_weixinID']);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$thisVip_name = addslashes($_REQUEST['textinputName']);
$thisVip_tel = addslashes($_REQUEST['textinputTel']);
$thisVip_Advice = addslashes($_REQUEST['textinputAdvice']);
$thisVip_createtime = date("Y-m-d H:i:s",time());
$thisVip_edittime = date("Y-m-d H:i:s",time());


//判断已经有同样的献策，有的话提示“不能重复建言”
$sql="select COUNT(*) from  bbsInfo
      where BBS_NAME = '$thisVip_name'
      AND BBS_TEL = '$thisVip_tel'
      AND BBS_ADVICE = '$thisVip_Advice'
      AND WEIXIN_ID = $weixinID";
$count = getVarBySql($sql);
if($count){
    ?>
    <body>
        <div data-role="page">  
            <div data-role="content"> 
                <div>
                    <label>已经有相同内容了，请不要重复提交</label>
                </div>
            </div>
        </div>
    </body>
<?php
    exit;
}
//小图
$imgUrl = array();
//大图
$imgUrl2 = array();
//图片上传保存操作
for($i = 1;$i<=4;$i++){
    $filename = 'up_img'.$i;
    //echo $filename;
    $files = $_FILES[$filename];
    $fileSize = $files['size'];
    if ($fileSize > 0){
        $name= '/BBS/bbs-'.time().'up_img'.$i.'.jpg';
        $name2= '/BBS/bbs-'.time().'up_img'.$i.'-big.jpg';
        $form_data =$files['tmp_name'];
        $s2 = new SaeStorage();
        $img = new SaeImage();
        $img2 = new SaeImage();
        $img_data = file_get_contents($form_data);//获取本地上传的图片数据
        $img->setData($img_data);
        $img2->setData($img_data);
        //$img->resize(280); //等比例缩放到280宽
        $attr=$img->getImageAttr();
        if($attr[0]>200)
        {
          $img->resize(200);  
        }
        $attr2=$img2->getImageAttr();
        if($attr2[0]>750)
        {
          $img2->resize(750);  
        }
        $img->improve();
        $img2->improve();
        $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
        $new_data2 = $img2->exec(); // 执行处理并返回处理后的二进制数据
        $s2->write('weixincourse',$name,$new_data);//将public修改为自己的storage 名称
        $s2->write('weixincourse',$name2,$new_data2);//将public修改为自己的storage 名称
        $url= $s2->getUrl('weixincourse',$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
        $url2= $s2->getUrl('weixincourse',$name2);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
    }else{
        $url = "imgPath error";
        $url2 = "imgPath error";
    }
    //$thisVip_imgUlr = $url;
    if($url != "imgPath error"){
        $imgUrl[] =  $url;
        $imgUrl2[] =  $url2;
    }
}

$thisVip_imgUlr = json_encode($imgUrl);
$thisVip_imgUlr2 = json_encode($imgUrl2);

$insertSql = "insert into bbsInfo
                        (WEIXIN_ID,
                        BBS_OPENID,
                        BBS_NAME,
                        BBS_TEL,
                        BBS_ADVICE,
                        BBS_IMGURL,
                        BBS_BIGIMGURL,
                        BBS_CREATETIME,
                        BBS_EDITETIME,
                        BBS_ISOK
                        ) values (
                        $weixinID,
                        '$openid',
                        '$thisVip_name',
                        '$thisVip_tel',
                        '$thisVip_Advice',
                        '$thisVip_imgUlr',
                        '$thisVip_imgUlr2',
                        '$thisVip_createtime',
                        '$thisVip_edittime',
                        0
                        )";
$errorNo = SaeRunSql($insertSql);
if($errorNo == 0){
    //echo $insertSql."OK";
    //exit;
?>
    <body>
        <div data-role="page">  
            <div data-role="content"> 
                <div>
                    <label>感谢您的线索，我们会认真详读，然后审核</label>
                </div>
            </div>
        </div>
    </body>
<?php        
}else{
    //echo $insertSql."NG";
    //exit;
?>
    <body>
        <div data-role="page">  
            <div data-role="content"> 
                <div>
                    <label>提交时出现错误，请重新提交！</label>
                </div>
            </div>
        </div>
    </body>
<?php    
}
?>