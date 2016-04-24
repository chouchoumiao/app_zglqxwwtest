<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = addslashes($_GET['weixinID']);

//数据hongbaoInfo
$hongbao_title = addslashes($_REQUEST['hongbao_title']);
$hongbao_password = addslashes($_REQUEST['hongbao_password']);
$hongbao_beginTime = addslashes($_REQUEST['hongbao_beginTime']);
$hongbao_endTime = addslashes($_REQUEST['hongbao_endTime']);

//数据replyInfo
$reply_intext = addslashes($_REQUEST['reply_intext']);
$reply_title = addslashes($_REQUEST['reply_title']);
$reply_description = addslashes($_REQUEST['reply_description']);
$reply_content = addslashes($_REQUEST['reply_content']);

//图片上传保存操作
$filename = 'up_img';
$files = $_FILES[$filename];
$fileSize = $files['size'];
if ($fileSize > 0){
    $name= '/hongbao/hongbao-'.time().'.jpg';
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
$hongbao_id = addslashes($_REQUEST['hongbao_id']);

//修改的情况下
if($hongbao_id){
    $sql = "update hongbaoInfo
            set hongbao_title='$hongbao_title',
                hongbao_password=$hongbao_password,
                hongbao_beginTime='$hongbao_beginTime',
                hongbao_endTime='$hongbao_endTime',
                hongbao_editTime='$nowtime'
            where hongbao_id=$hongbao_id
            and WEIXIN_ID = $weixinID";
    $resultErrorNo = SaeRunSql($sql);
   
    if($resultErrorNo == 0)
    {
        if($reply_ImgUrl == "imgPath error"){
            $sql = "update replyInfo
                    set reply_intext='$reply_intext',
                        reply_title='$reply_title',
                        reply_description='$reply_description',
                        reply_content='$reply_content',
                        record_editTime = '$nowtime'
                    where hongbao_id=$hongbao_id";
        }else{
            $sql = "update replyInfo
                    set reply_intext='$reply_intext',
                        reply_title='$reply_title',
                        reply_ImgUrl='$reply_ImgUrl',
                        reply_description='$reply_description',
                        reply_content='$reply_content',
                        record_editTime = '$nowtime'
                    where hongbao_id=$hongbao_id";
        }
        

        $resultErrorNo = SaeRunSql($sql);
        if($resultErrorNo != 0)
        {
            $msg = "更新错误！";
        }else{
            $msg =  "更新成功！";
        }
    }
}else{
    $insertToMainSql = "insert into hongbaoInfo (
                        WEIXIN_ID,hongbao_title,hongbao_password,hongbao_insertTime,
                        hongbao_beginTime,hongbao_endTime,hongbao_Status) values (
                        $weixinID,'$hongbao_title',$hongbao_password,'$nowtime',
                        '$hongbao_beginTime','$hongbao_endTime',1)";
    $resultErrorNo = SaeRunSql($insertToMainSql);
    if($resultErrorNo == 0)
    {
        sleep(1);
        $hongbaoIdGetSql = "select MAX(hongbao_id) from hongbaoInfo
                            where WEIXIN_ID = $weixinID
                            AND hongbao_password = $hongbao_password
                            AND  hongbao_title = '$hongbao_title'";
        $insertHongbaoID = getVarBySql($hongbaoIdGetSql);

        //$sql = "insert into replyInfo (WEIXIN_ID,hongbao_id,reply_intext,reply_title,reply_ImgUrl,reply_description,reply_content,
        //record_editTime) values ($weixinID,$insertHongbaoID,'$reply_intext','$reply_title','$reply_ImgUrl','$reply_description','$reply_content','$nowtime')";

        $sql = "insert into replyInfo
                (WEIXIN_ID,
                hongbao_id,
                event_Text,
                reply_intext,
                reply_title,
                reply_ImgUrl,
                reply_url,
                reply_description,
                reply_content,
                record_insertTime,
                record_editTime,
                status
                ) values (
                $weixinID,
                $insertHongbaoID,
                '',
                '$reply_intext',
                '$reply_title',
                '$reply_ImgUrl',
                '',
                '$reply_description',
                '$reply_content',
                '$nowtime',
                '$nowtime',
                1
                )";

        $resultErrorNo = SaeRunSql($sql);
        if($resultErrorNo != 0)
        {
            $msg = "新数据插入失败！";
        }else{
            $msg = "新数据插入成功！";
        }
    }
}
echoInfo($msg);
exit;
