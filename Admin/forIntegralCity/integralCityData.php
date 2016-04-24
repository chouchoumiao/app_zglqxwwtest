<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];

$thisold_integralCity_id = addslashes($_REQUEST['integralCity_id']);
$thisintegralCity_name = addslashes($_REQUEST['integralCity_name']);
$thisintegralCity_content = addslashes($_REQUEST['integralCity_content']);
$thisintegralCity_stockCount = addslashes($_REQUEST['integralCity_stockCount']);
$thisintegralCity_integralNum = addslashes($_REQUEST['integralCity_integralNum']);
$thisintegralCity_fromDate = addslashes($_REQUEST['integralCity_fromDate']);
$thisintegralCity_endDate = addslashes($_REQUEST['integralCity_endDate']);
$thisintegralCity_expirationDate = addslashes($_REQUEST['integralCity_expirationDate']);

//图片上传保存操作
$filename = 'filename';
$files = $_FILES[$filename];
$fileSize = $files['size'];
if ($fileSize > 0){
    $name= '/integralCity/integral-'.time().'.jpg';
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
$thisintegralCity_imgPath = $url;
$nowtime=date("Y/m/d H:i:s",time());
//如果是修改
if($thisold_integralCity_id){
    //修改
    if($thisintegralCity_imgPath == "imgPath error"){
        $sql = "update integralCity_config
                set integralCity_name='$thisintegralCity_name',
                    integralCity_content='$thisintegralCity_content',
                    integralCity_stockCount=$thisintegralCity_stockCount,
                    integralCity_integralNum=$thisintegralCity_integralNum,
                    integralCity_fromDate='$thisintegralCity_fromDate',
                    integralCity_endDate='$thisintegralCity_endDate',
                    integralCity_expirationDate = '$thisintegralCity_expirationDate',
                    integralCity_editTime='$nowtime'
                where integralCity_id=$thisold_integralCity_id
                AND WEIXIN_ID= $weixinID";
    }else{
        $sql = "update integralCity_config
                set integralCity_name='$thisintegralCity_name',
                    integralCity_content='$thisintegralCity_content',
                    integralCity_stockCount=$thisintegralCity_stockCount,
                    integralCity_integralNum=$thisintegralCity_integralNum,
                    integralCity_fromDate='$thisintegralCity_fromDate',
                    integralCity_endDate='$thisintegralCity_endDate',
                    integralCity_expirationDate = '$thisintegralCity_expirationDate',
                    integralCity_editTime='$nowtime',
                    integralCity_imgPath='$thisintegralCity_imgPath'
                where integralCity_id=$thisold_integralCity_id
                AND WEIXIN_ID= $weixinID";
    }
}else{
    $sql = "insert into integralCity_config
                        (WEIXIN_ID,
                        integralCity_name,
                        integralCity_content,
                        integralCity_stockCount,
                        integralCity_integralNum,
                        integralCity_InsertTime,
                        integralCity_fromDate,
                        integralCity_endDate,
                        integralCity_expirationDate,
                        integralCity_imgPath
                        ) values
                        ($weixinID,
                        '$thisintegralCity_name',
                        '$thisintegralCity_content',
                        $thisintegralCity_stockCount,
                        $thisintegralCity_integralNum,
                        '$nowtime',
                        '$thisintegralCity_fromDate',
                        '$thisintegralCity_endDate',
                        '$thisintegralCity_expirationDate',
                        '$thisintegralCity_imgPath'
                        )";
}
$errorno = SaeRunSql($sql);
if($errorno != 0){
    $msg = "数据库操作错误啦！";
}else{
    $msg = "操作成功！";    
}
echo "<script>alert('$msg');location='integralCityManger.php'</Script>";
