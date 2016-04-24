<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" /> 
<link rel="stylesheet" href="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
<!--<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://apps.bdimg.com/libs/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>-->
</head>
<body>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$openid = addslashes($_REQUEST['thisVip_openidField']);
$weixinID = addslashes($_GET['weixinID']);

//判断传入的参数openid和weixinID的长度正确性
isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

$thisPhotoWall_name = addslashes($_REQUEST['textinputName']);
$thisPhotoWall_tel = addslashes($_REQUEST['textinputTel']);

//图片上传保存操作
$filename = 'up_img';
//echo $filename;
$files = $_FILES[$filename];
$fileSize = $files['size'];
if ($fileSize > 0){
    $name= '/photoWall/photoWall-'.time().'.jpg';
    $form_data =$files['tmp_name'];
    $s2 = new SaeStorage();
    $img = new SaeImage();
    $img_data = file_get_contents($form_data);//获取本地上传的图片数据
    $img->setData($img_data);
    
    $attr=$img->getImageAttr();
    if($attr[0]>1000)
    {
      $img->resize(1000);  
    }
    $img->improve();       //提高图片质量的函数
    $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
    $s2->write('weixincourse',$name,$new_data);//将public修改为自己的storage 名称
    $url= $s2->getUrl('weixincourse',$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
}else{
    $url = "imgPath error";
}

$thisImgUrl = $url;
$thisPhotoWall_createtime = date("Y-m-d H:i:s",time());
$thisPhotoWall_edittime = date("Y-m-d H:i:s",time());

$insertSql = "insert into photoWall
                          (WEIXIN_ID,
                          PHOTOWALL_OPENID,
                          PHOTOWALL_NAME,
                          PHOTOWALL_TEL,
                          PHOTOWALL_IMGURL,
                          PHOTOWALL_CREATETIME,
                          PHOTOWALL_EDITETIME,
                          PHOTOWALL_ISOK
                          ) values (
                          $weixinID,
                          '$openid',
                          '$thisPhotoWall_name',
                          '$thisPhotoWall_tel',
                          '$thisImgUrl',
                          '$thisPhotoWall_createtime',
                          '$thisPhotoWall_edittime',
                          0
                          )";
$errorNo = SaeRunSql($insertSql);
//echo $insertSql;

if($errorNo == 0){
?>

<div data-role="page">  
    <div data-role="content"> 
        <div>
            <label>照片提交成功，请耐心等待审核！</label>
        </div>
    </div>
</div>
 
<?php        
}else{
?>
    
<div data-role="page">  
    <div data-role="content"> 
        <div>
            <label>提交时出现错误，请重新提交！</label>
        </div>
    </div>
</div>
    
<?php    
}
?>
</body>