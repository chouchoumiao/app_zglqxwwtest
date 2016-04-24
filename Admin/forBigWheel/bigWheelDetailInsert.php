<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$action = addslashes($_GET["action"]);
$weixinID = addslashes($_GET["weixinID"]);

if($action == "mainInfo"){
    $bigWheel_id = addslashes($_POST["bigWheel_id"]);
    $arr['detailInfoTitle'] = "";
    $arr['detailInfoDescription'] = "";
    $arr['detailInfoProbability'] = "";
    $arr['detailInfoCount'] = "";     
            
    if($bigWheel_id){
        $sql = "select * from bigWheel_main
                where bigWheel_isDeleted = 0
                AND WEIXIN_ID = $weixinID
                AND bigWheel_id = $bigWheel_id";
        $bigWheelMainInfoArr = getlineBySql($sql);
        if($bigWheelMainInfoArr){
            $bigWheel_detailInfo_description = $bigWheelMainInfoArr['bigWheel_detailInfo_description'];
            $bigWheel_detailInfo_probability = $bigWheelMainInfoArr['bigWheel_detailInfo_probability'];
            $bigWheel_detailInfo_count = $bigWheelMainInfoArr['bigWheel_detailInfo_count'];
            
            //修正bug 取出的数据不是数据或者为空的情况下,返回值为空 20150925
            if($bigWheel_detailInfo_description){
                $detailInfoDescription = json_decode($bigWheel_detailInfo_description);
            }else{
                $detailInfoDescription = "";
            }
            if($bigWheel_detailInfo_probability){
                $detailInfoProbability = json_decode($bigWheel_detailInfo_probability);
            }else{
                $detailInfoProbability = "";
            }
            if($bigWheel_detailInfo_count){
                $detailInfoCount = json_decode($bigWheel_detailInfo_count);
            }else{
                $detailInfoCount = "";
            }
            $arr['detailInfoDescription'] = $detailInfoDescription;
            $arr['detailInfoProbability'] = $detailInfoProbability;
            $arr['detailInfoCount'] = $detailInfoCount;            
        }
    }
    $arr['success'] = "OK";
    echo json_encode($arr);	
    exit;
}

if($action == "detailInsert"){
    //取得set页面传递过来的数据
    $mainInfoStr = addslashes($_POST["mainInfoStr"]);
    $bigWheel_id = addslashes($_POST["bigWheel_id"]);
    
    //addslashes函数不能再数组中使用 20150925
    $titleArr = $_POST["titleArr"];
    $descriptionArr = $_POST["descriptionArr"];
    $probabilityArr = $_POST["probabilityArr"];
    $countArr = $_POST["countArr"];
    
    $newTitle = getPreg_replace($titleArr);
    $newDescription = getPreg_replace($descriptionArr);
    $newProbability = json_encode($probabilityArr);
    $newCount = json_encode($countArr);

    //先将Main表插入数据
    $mainInfoArr = explode(".",$mainInfoStr);
    
    $thisbigWheel_title = $mainInfoArr[0];
    $thisbigWheel_description = $mainInfoArr[1];
    $thisbigWheel_times = $mainInfoArr[2];
    $thisbigWheel_Integral = $mainInfoArr[3];
    $thisbigWheel_beginDate = $mainInfoArr[4];
    $thisbigWheel_endDate = $mainInfoArr[5];
    $thisbigWheel_expirationDate = $mainInfoArr[6];
    $thisbigWheel_address = $mainInfoArr[7];
    $thisbigWheel_count = $mainInfoArr[8];
    $thisbigWheel_imgPath = "img/activity-lottery-7.png";
    $thisbigWheel_migPathInner = "img/activity-lottery-2.png";

    //取得当前插入的时间
    $nowtime=date("Y/m/d H:i:s",time());
    
    if(!$bigWheel_id){
        //确保无记录时候才追加新的记录
        $sql =  "insert into bigWheel_main
                (WEIXIN_ID,bigWheel_title,bigWheel_description,bigWheel_times,
                 bigWheel_Integral,bigWheel_beginDate,bigWheel_endDate,
                 bigWheel_expirationDate,bigWheel_address,bigWheel_migPath,
                 bigWheel_migPathInner,bigWheel_count,bigWheel_detailInfo_title,
                 bigWheel_detailInfo_description,bigWheel_detailInfo_probability,
                 bigWheel_detailInfo_count,bigWheel_insertTime) values ($weixinID,
                 '$thisbigWheel_title','$thisbigWheel_description',$thisbigWheel_times,
                 '$thisbigWheel_Integral','$thisbigWheel_beginDate','$thisbigWheel_endDate',
                 '$thisbigWheel_expirationDate','$thisbigWheel_address',
                 '$thisbigWheel_imgPath','$thisbigWheel_migPathInner',
                 $thisbigWheel_count,'$newTitle','$newDescription',
                '$newProbability','$newCount','$nowtime')";
    }else{
        $sql =  "update bigWheel_main
                 set bigWheel_title='$thisbigWheel_title',
                     bigWheel_description='$thisbigWheel_description',
                     bigWheel_times=$thisbigWheel_times,
                     bigWheel_Integral=$thisbigWheel_Integral,
                     bigWheel_beginDate='$thisbigWheel_beginDate',
                     bigWheel_endDate='$thisbigWheel_endDate',
                     bigWheel_expirationDate='$thisbigWheel_expirationDate',
                     bigWheel_address='$thisbigWheel_address',
                     bigWheel_count=$thisbigWheel_count,
                     bigWheel_detailInfo_title = '$newTitle',
                     bigWheel_detailInfo_description='$newDescription',
                     bigWheel_detailInfo_probability='$newProbability',
                     bigWheel_detailInfo_count='$newCount',
                     bigWheel_editTime = '$nowtime'
                where bigWheel_id=$bigWheel_id
                AND WEIXIN_ID = $weixinID";
    }
    
    $resultErrorNo = SaeRunSql($sql);
    
    if($resultErrorNo != 0)
    {
        $arr['success'] = "NG";
        $arr['msg'] = "设置失败！";
    }else{
        $arr['success'] = "OK";
        $arr['msg'] = "设置成功！2秒后跳转到主页面";
    }
    echo json_encode($arr);	
    exit;
}