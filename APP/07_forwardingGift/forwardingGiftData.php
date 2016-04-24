<?php
header("Content-type:text/html;charset=utf-8");

include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

//只能使用微信内置浏览器进行访问 20160123
if(!is_weixin()){
    echoWarning('功能只能在微信内置浏览器进行访问噢');exit;
}

$action = addslashes($_GET['action']);

if($action == "submit"){
    //sae_xhprof_start(); 测试性能用 开始
    $openid = addslashes($_REQUEST['openid']);
    $weixinID = addslashes($_REQUEST['weixinID']);
    
    //判断传入的参数openid和weixinID的长度正确性
    isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");

    $nowDate = date("Y-m-d",time());
    $sql = "select count(*) from forwardingGift
            where FORWARDINGGIFT_OPENID = '$openid'
            AND WEIXIN_ID = $weixinID
            and FORWARDINGGIFT_COMMITDATE = '$nowDate'";
    $count = getVarBySql($sql);
    if($count >= 1){
        echo "<script>alert('不要多次提交!');location='forwardingGift.php?openid=$openid&weixinID=$weixinID';</script>";
    }else{
        //图片上传保存操作
        $filename = 'up_img';
        $files = $_FILES[$filename];
        $fileSize = $files['size'];

        if ($fileSize > 0){
            //对应小图和大图的名称
            $name= '/forwardingGift/forwardingGift-'.time().'.jpg';
            $nameBig= '/forwardingGift/forwardingGift-'.time().'.-big.jpg';

            $form_data =$files['tmp_name']; //取得上传图片的数据流
            $storage = new SaeStorage();//实例化Storage对象
            $img = new SaeImage(); //实例化小图的SaeImage对象
            $imgBig = new SaeImage(); //实例化大图的SaeImage对象
            $img_data = file_get_contents($form_data);//获取本地上传的图片数据

            //将图片的写入到img对象中
            $img->setData($img_data);
            $imgBig->setData($img_data);

            $imgSize = getimagesize($files['tmp_name']);//获取上传图片的size [0]:长 [1]:高
            $imgSizeW = $imgSize[0]; //长
            $imgSizeH = $imgSize[1]; //高

            //如果上传图片超过 长度超过750 则大图压缩到750等比例，如果不超过但是高度超过1000，则按照高度压缩为1000
            if($imgSizeW>750)
            {
                $imgBig -> resize(750);
            }else{
                if($imgSizeH>1000){
                    $imgBig -> resize(0,1000);
                }
            }
            //如果上传图片超过 200 则 小图压缩到200等比例，不超过则是原图，不做压缩
            if($imgSizeW > 200)
            {
              $img->resize(200);
            }

            $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
            $new_dataBig = $imgBig->exec(); // 执行处理并返回处理后的二进制数据
            $storage->write('weixincourse',$name,$new_data);//将public修改为自己的storage 名称
            $storage->write('weixincourse',$nameBig,$new_dataBig);//将public修改为自己的storage 名称
            $url= $storage->getUrl('weixincourse',$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
            $url2= $storage->getUrl('weixincourse',$nameBig);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
        }else{
            $url = "imgPath error";
            $url2 = "imgPath error";
        }

        $thisImgUrl = $url;
        $thisImgUrl2 = $url2;

        $nowTime = date("Y-m-d H:i:s",time());

        $insertSql = "insert into forwardingGift
                                  (WEIXIN_ID,
                                  FORWARDINGGIFT_OPENID,
                                  FORWARDINGGIFT_IMGURL,
                                  FORWARDINGGIFT_BIGIMGURL,
                                  FORWARDINGGIFT_CREATETIME,
                                  FORWARDINGGIFT_COMMITDATE,
                                  FORWARDINGGIFT_EDITETIME,
                                  FORWARDINGGIFT_ISOK
                                  ) values (
                                  $weixinID,
                                  '$openid',
                                  '$thisImgUrl',
                                  '$thisImgUrl2',
                                  '$nowTime',
                                  '$nowDate',
                                  '$nowTime',
                                  0
                                  )";
        $errorNo = SaeRunSql($insertSql);
        //sae_xhprof_end(); 测试性能用 结束
        if($errorNo == 0){
           echo "<script>alert('提交成功，请耐心等待审核!');location='forwardingGift.php?openid=$openid&weixinID=$weixinID';</script>";
        }else{
            echo "<script>alert('提交时出现错误，请重新提交!');location='forwardingGift.php?openid=$openid&weixinID=$weixinID';</script>";
        } 
    }
}else if ($action == "resultSearch"){
    $openid = addslashes($_GET['openid']);
    $weixinID = addslashes($_GET['weixinID']);

    //判断传入的参数openid和weixinID的长度正确性
    isOpenIDWeixinIDOK($openid,$weixinID,"参数错误");
    
    $sql = "select * from forwardingGift
            where FORWARDINGGIFT_OPENID = '$openid'
            AND WEIXIN_ID = $weixinID";
    $info = getDataBySql($sql);

    if($info){
        $arr['success'] = 1;
    }else{
        $arr['success'] = -1;
        $arr['msg'] = "未取得信息";
    }
    echo json_encode($arr);
    exit;
}
