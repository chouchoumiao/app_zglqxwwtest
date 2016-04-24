<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

$thisold_flowerCity_id = addslashes($_REQUEST['flowerCity_id']);
$thisflowerCity_name = addslashes($_REQUEST['flowerCity_name']);
$thisflowerCity_content = addslashes($_REQUEST['flowerCity_content']);
$thisflowerCity_stockCount = addslashes($_REQUEST['flowerCity_stockCount']);
$thisflowerCity_flowerNum = addslashes($_REQUEST['flowerCity_flowerNum']);
$thisflowerCity_fromDate = addslashes($_REQUEST['flowerCity_fromDate']);
$thisflowerCity_endDate = addslashes($_REQUEST['flowerCity_endDate']);
$thisflowerCity_expirationDate = addslashes($_REQUEST['flowerCity_expirationDate']);

//图片上传保存操作
$filename = 'filename';
$files = $_FILES[$filename];
$fileSize = $files['size'];
if ($fileSize > 0){
    $name= '/flowerCity/flower-'.time().'.jpg';
    $form_data =$files['tmp_name'];
    $s2 = new SaeStorage();
    $img = new SaeImage();
    $img_data = file_get_contents($form_data);//获取本地上传的图片数据
    $img->setData($img_data);
    $img->resize(600,400); //600*400
    $img->improve();       //提高图片质量的函数
    $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
    $s2->write('weixincourse',$name,$new_data);//将public修改为自己的storage 名称
    $url= $s2->getUrl('weixincourse',$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
}else{
    $url = "imgPath error";
}	
$thisflowerCity_imgPath = $url;
$nowtime=date("Y/m/d H:i:s",time());
//如果是修改
if($thisold_flowerCity_id){
    //修改
    if($thisflowerCity_imgPath == "imgPath error"){
        $sql = "update flowerCity_config
                set flowerCity_name='$thisflowerCity_name',
                flowerCity_content='$thisflowerCity_content',
                flowerCity_stockCount=$thisflowerCity_stockCount,
                flowerCity_flowerNum=$thisflowerCity_flowerNum,
                flowerCity_fromDate='$thisflowerCity_fromDate',
                flowerCity_endDate='$thisflowerCity_endDate',
                flowerCity_expirationDate = '$thisflowerCity_expirationDate',
                flowerCity_editTime='$nowtime'
                where id=$thisold_flowerCity_id
                AND WEIXIN_ID= $weixinID";
    }else{
        $sql = "update flowerCity_config
                set flowerCity_name='$thisflowerCity_name',
                flowerCity_content='$thisflowerCity_content',
                flowerCity_stockCount=$thisflowerCity_stockCount,
                flowerCity_flowerNum=$thisflowerCity_flowerNum,
                flowerCity_fromDate='$thisflowerCity_fromDate',
                flowerCity_endDate='$thisflowerCity_endDate',
                flowerCity_expirationDate = '$thisflowerCity_expirationDate',
                flowerCity_editTime='$nowtime',
                flowerCity_imgPath='$thisflowerCity_imgPath'
                where id=$thisold_flowerCity_id
                AND WEIXIN_ID= $weixinID";
    }
}else{
    $sql = "insert into flowerCity_config
                        (WEIXIN_ID,
                        flowerCity_name,
                        flowerCity_content,
                        flowerCity_stockCount,
                        flowerCity_flowerNum,
                        flowerCity_InsertTime,
                        flowerCity_fromDate,
                        flowerCity_endDate,
                        flowerCity_expirationDate,
                        flowerCity_imgPath
                        ) values (
                        $weixinID,
                        '$thisflowerCity_name',
                        '$thisflowerCity_content',
                        $thisflowerCity_stockCount,
                        $thisflowerCity_flowerNum,
                        '$nowtime',
                        '$thisflowerCity_fromDate',
                        '$thisflowerCity_endDate',
                        '$thisflowerCity_expirationDate',
                        '$thisflowerCity_imgPath'
                        )";
}
$errorno = SaeRunSql($sql);
if($errorno != 0){
    $msg = "数据库操作错误啦！".$sql;
}else{
    $msg = "操作成功！";    
}
echo "<script>alert('$msg');location='flowerCityManger.php'</Script>";
