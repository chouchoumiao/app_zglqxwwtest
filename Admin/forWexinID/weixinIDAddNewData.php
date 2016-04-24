<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
$action = addslashes($_GET["action"]);
$msg = "";
$user = $_SESSION['user'];

if($action == "getToken"){
    $newToken =  getToken();
    if($newToken){
        $arr['success'] = "OK";
        $arr['msg'] = $newToken;
    }else{
        $arr['success'] = "NG";
        $arr['msg'] = "未能生成新的Token";
    }
    echo json_encode($arr);
    exit;
}

//数据微信公众号信息取得
$weixinName = addslashes($_REQUEST['weixinName']);
$weixinType = addslashes($_REQUEST['weixinType']);
$weixinUrl = addslashes($_REQUEST['weixinUrl']);
$weixinToken = addslashes($_REQUEST['weixinToken']);
$weixinAppId = addslashes($_REQUEST['weixinAppId']);
$weixinAppSecret = addslashes($_REQUEST['weixinAppSecret']);
$weixinCode = addslashes($_REQUEST['weixinCode']);
$weixinOldID = addslashes($_REQUEST['weixinOldID']);
$weixin_id = addslashes($_REQUEST['weixin_id']);

$nowTime  = date("Y-m-d H:i:s",time());

//新增
if(!$weixin_id){
    $sql = "insert into AdminToWeiID
                        (username,
                        weixinName,
                        weixinType,
                        weixinUrl,
                        weixinToken,
                        weixinAppId,
                        weixinAppSecret,
                        weixinCode,
                        weixinOldID,
                        weixinInsertTime,
                        weixinStatus
                        ) values (
                        '$user',
                        '$weixinName',
                        '$weixinType',
                        '$weixinUrl',
                        '$weixinToken',
                        '$weixinAppId',
                        '$weixinAppSecret',
                        '$weixinCode',
                        '$weixinOldID',
                        '$nowTime',
                        1
                        )";
    
    $resultErrorNo = SaeRunSql($sql);
    
    if($resultErrorNo == 0)
    {
        
        $selectIDSql = "select MAX(id) from AdminToWeiID where weixinStatus = 1";
        $thisID = getVarBySql($selectIDSql);
        //$newWeixinUrl = "http://1.zglqxwwtest.sinaapp.com/?weixinID=".$thisID;
        $newWeixinUrl = 'http://'.$_SERVER['HTTP_HOST'].'/?weixinID='.$thisID;

        $domain = "weixincourse";
        $filename = 'up_img';
        $files = $_FILES[$filename];
        $fileSize = $files['size'];
        if ($fileSize > 0){
            $name= '/weixin/QRImg-'.$thisID.'.jpg';
            $form_data =$files['tmp_name'];
            $s2 = new SaeStorage();
            $img = new SaeImage();
            $img_data = file_get_contents($form_data);//获取本地上传的图片数据
            $img->setData($img_data);
            //$img->resize(180,180); //图片缩放为180*180
            $img->improve();       //提高图片质量的函数
            $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
            $s2->write($domain,$name,$new_data);//将public修改为自己的storage 名称
            $QRUrl= $s2->getUrl($domain,$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
        }else{
            $QRUrl = "url error";
        }
        if($QRUrl == "url error"){
            $QRUrl = "img/default_QR.png";
        }
        
        $filename = 'up_imgMin';
        $files = $_FILES[$filename];
        $fileSize = $files['size'];
        if ($fileSize > 0){
            $name= '/weixin/HeadImg-'.$thisID.'.jpg';
            $form_data =$files['tmp_name'];
            $s2 = new SaeStorage();
            $img = new SaeImage();
            $img_data = file_get_contents($form_data);//获取本地上传的图片数据
            $img->setData($img_data);
            //$img->resize(180,180); //图片缩放为180*180
            $img->improve();       //提高图片质量的函数
            $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
            $s2->write($domain,$name,$new_data);//将public修改为自己的storage 名称
            $headUrl= $s2->getUrl($domain,$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
        }else{
            $headUrl = "url error";
        }
        if($headUrl == "url error"){
            $headUrl = "img/default_head.png";
        }
    
        $updateSql = "update AdminToWeiID
                      set weixinUrl = '$newWeixinUrl',
                          weixinQRCodeUrl = '$QRUrl',
                          weixinHeadUrl = '$headUrl',
                          weixinEditTime = '$nowTime'
                      where id = $thisID";
        $resultErrorNo = SaeRunSql($updateSql);

        if($resultErrorNo == 0){
            $msg = "提交成功！1秒后跳转到主页面";
            echo "<script>setTimeout(function(){window.parent.location='../login/main.php';},1000);  </script>";

            //echo $msg.$updateSql;
            //exit;
        }else{
            //处理失败的情况下，将原先插入的数据删除
            $deleteSql = "delete from AdminToWeiID where id = $thisID";
            SaeRunSql($deleteSql);
            $msg = "提交失败！";
            //echo $msg.$updateSql;
            //exit;
        }
        
    }else{
        $msg = "追加新公众号失败！";
    }
}else{
    
    $domain = 'weixincourse';
    $filename = 'up_img';
    $files = $_FILES[$filename];
    $fileSize = $files['size'];
    if ($fileSize > 0){
        $name= '/weixin/QRImg-'.$weixin_id.'-'.time().'.jpg';
        $form_data =$files['tmp_name'];
        $s2 = new SaeStorage();
        $img = new SaeImage();
        $img_data = file_get_contents($form_data);//获取本地上传的图片数据
        $img->setData($img_data);
        //$img->resize(180,180); //图片缩放为180*180
        $img->improve();       //提高图片质量的函数
        $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
        $s2->write($domain,$name,$new_data);//将public修改为自己的storage 名称
        $QRUrl= $s2->getUrl($domain,$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
    }else{
        $QRUrl = "url error";
    }
    
    $filename = 'up_imgMin';
    $files = $_FILES[$filename];
    $fileSize = $files['size'];
    if ($fileSize > 0){
        $name= '/weixin/HeadImg-'.$weixin_id.'-'.time().'.jpg';
        $form_data =$files['tmp_name'];
        $s2 = new SaeStorage();
        //if(fileExists($domain, $name)){
        $r = $s2->delete($domain,$name);
        //}
        $img = new SaeImage();
        $img_data = file_get_contents($form_data);//获取本地上传的图片数据
        $img->setData($img_data);
        //$img->resize(180,180); //图片缩放为180*180
        $img->improve();       //提高图片质量的函数
        $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
        $s2->write($domain,$name,$new_data);//将public修改为自己的storage 名称
        $headUrl= $s2->getUrl($domain,$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
    }else{
        $headUrl = "url error";
    }
    
    if( ($QRUrl == "url error") &&  ($headUrl == "url error") ){
        $sql = "update AdminToWeiID
                set username = '$user',
                    weixinName = '$weixinName',
                    weixinType = '$weixinType',
                    weixinToken = '$weixinToken',
                    weixinAppId = '$weixinAppId',
                    weixinAppSecret = '$weixinAppSecret',
                    weixinCode = '$weixinCode',
                    weixinOldID = '$weixinOldID',
                    weixinEditTime = '$nowTime'
                where id = $weixin_id";
    }else if(($QRUrl != "url error") &&  ($headUrl == "url error")){
        $sql = "update AdminToWeiID
                set username = '$user',
                    weixinName = '$weixinName',
                    weixinType = '$weixinType',
                    weixinToken = '$weixinToken',
                    weixinAppId = '$weixinAppId',
                    weixinAppSecret = '$weixinAppSecret',
                    weixinCode = '$weixinCode',
                    weixinOldID = '$weixinOldID',
                    weixinQRCodeUrl = '$QRUrl',
                    weixinEditTime = '$nowTime'
                where id = $weixin_id";
    }else if(($QRUrl == "url error") &&  ($headUrl != "url error")){
        $sql = "update AdminToWeiID
                set username = '$user',
                    weixinName = '$weixinName',
                    weixinType = '$weixinType',
                    weixinToken = '$weixinToken',
                    weixinAppId = '$weixinAppId',
                    weixinAppSecret = '$weixinAppSecret',
                    weixinCode = '$weixinCode',
                    weixinOldID = '$weixinOldID',
                    weixinHeadUrl = '$headUrl',
                    weixinEditTime = '$nowTime'
                where id = $weixin_id";
    }else{
        $sql = "update AdminToWeiID
                set username = '$user',
                    weixinName = '$weixinName',
                    weixinType = '$weixinType',
                    weixinToken = '$weixinToken',
                    weixinAppId = '$weixinAppId',
                    weixinAppSecret = '$weixinAppSecret',
                    weixinCode = '$weixinCode',
                    weixinOldID = '$weixinOldID',
                    weixinQRCodeUrl = '$QRUrl',
                    weixinHeadUrl = '$headUrl',
                    weixinEditTime = '$nowTime'
                where id = $weixin_id";
    }
    $resultErrorNo = SaeRunSql($sql);
    if($resultErrorNo == 0){
        $msg = "更新成功！";
    }else{
        $msg = "更新失败！";
    }
}
echoInfo($msg);
exit;