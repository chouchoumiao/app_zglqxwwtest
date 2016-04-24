<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = addslashes($_GET['weixinID']);
$action = addslashes($_GET['action']);

//取得当前插入的时间
$nowtime=date("Y/m/d H:i:s",time());

if($action == "getNowData"){
     
    $eventText = addslashes($_POST["eventText"]);
    
    $sql = "select * from replyInfo
            where WEIXIN_ID = $weixinID
            AND event_Text = '$eventText'";
    $dataInfo = getLineBySql($sql); 

    if($dataInfo){
        $arr['reply_intext'] = $dataInfo['reply_intext'];
        $arr['reply_title'] = $dataInfo['reply_title'];
        $arr['reply_ImgUrl'] = $dataInfo['reply_ImgUrl'];
        $arr['reply_description'] = $dataInfo['reply_description'];
        $arr['reply_content'] = $dataInfo['reply_content'];
        $arr['replyID'] = $dataInfo['id'];
    }else{
        $arr['reply_intext'] = "";
        $arr['reply_title'] = "";
        $arr['reply_ImgUrl'] = "../../Static/img/upload.jpg";
        $arr['reply_description'] = "";
        $arr['reply_content'] = "";
        $arr['replyID'] = "";
    }
    echo json_encode($arr);	
    exit;
    
}
if($action == "setReply"){
    $weixinID = addslashes($_GET['weixinID']);
    
    //数据replyInfo
    $eventListText = addslashes($_REQUEST['eventTypeText']);

    $linkUrl = 'http://'.$_SERVER['HTTP_HOST'].'/APP/';
    switch ($eventListText)
    {
        case "会员中心":
            $eventUrl = $linkUrl."01_vipCenter/VipCennter.php";
            break;
        case "积分商城":
            $eventUrl = $linkUrl."02_integralCity/integralCity.php";
            break;
        case "会员答题":
            $eventUrl = $linkUrl."03_integralAnswer/answerMain.php";
            break;    
        case "红黑榜":
            $eventUrl = $linkUrl."04_bbs/bbsMain.php";
            break;
        case "照片墙":
            $eventUrl = $linkUrl."05_photoWall/photoWallMain.php";
            break;
        case "建言献策":
            $eventUrl = $linkUrl."06_advice/adviceMain.php";
            break;
        case "大转盘":
            $eventUrl = $linkUrl."94_bigwheel/bigWheel.php";
            break;
        case "刮刮卡":
            $eventUrl = $linkUrl."95_scratchcard/scratchcard.php";
            break;
        //答题刮刮卡追加 20151104
        case "答题刮刮卡":
            $eventUrl = "http://1.datiguaguaka.sinaapp.com/03_integralAnswer/answerMain.php";
            break;

        default:
            break;
    }
        
    $reply_intext = addslashes($_REQUEST['reply_intext']);
    $reply_title = addslashes($_REQUEST['reply_title']);
    $reply_description = addslashes($_REQUEST['reply_description']);
    $reply_content = addslashes($_REQUEST['reply_content']);

    //图片上传保存操作
    $filename = 'up_img';
    $files = $_FILES[$filename];
    $fileSize = $files['size'];
    if ($fileSize > 0){
        $name= '/reply/main-'.time().'.jpg';
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
    $reply_ImgUrl = $url;
    //取得当前插入的时间
    $nowtime=date("Y-m-d H:i:s",time());
    $replyID = addslashes($_REQUEST['replyID']);

    //修改的情况下
    if($replyID){
        
        if($reply_ImgUrl == "imgPath error"){
            $sql = "update replyInfo
                    set reply_intext='$reply_intext',
                        reply_title='$reply_title',
                        reply_description='$reply_description',
                        reply_content='$reply_content',
                        record_editTime = '$nowtime'
                    where id=$replyID";
        }else{
            $sql = "update replyInfo
                    set reply_intext='$reply_intext',
                        reply_title='$reply_title',
                        reply_ImgUrl='$reply_ImgUrl',
                        reply_description='$reply_description',
                        reply_content='$reply_content',
                        record_editTime = '$nowtime'
                    where id=$replyID";
        }
    }else{
        $sql = "insert into replyInfo
                ( WEIXIN_ID,
                  event_Text,
                  reply_intext,
                  reply_title,
                  reply_ImgUrl,
                  reply_url,
                  reply_description,
                  reply_content,
                  record_insertTime,
                  status )
                  values
                ( $weixinID,
                  '$eventListText',
                  '$reply_intext',
                  '$reply_title',
                  '$reply_ImgUrl',
                  '$eventUrl',
                  '$reply_description',
                  '$reply_content',
                  '$nowtime',1)";
    }
    $errorNo = SaeRunSql($sql);
        
    if($errorNo != 0){
        $msg = "设置失败！";
    }else{
        $msg = "设置成功！";
    }
    echoInfo($msg);
    exit;
}