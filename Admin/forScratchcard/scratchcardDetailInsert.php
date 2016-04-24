<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$action = addslashes($_GET["action"]);
$weixinID = addslashes($_GET["weixinID"]);

if($action == "mainInfo"){
    $scratchcard_id = addslashes($_POST["scratchcard_id"]);
    $arr['detailInfoTitle'] = "";
    $arr['detailInfoDescription'] = "";
    $arr['detailInfoProbability'] = "";
    $arr['detailInfoCount'] = "";     
            
    if($scratchcard_id){
        $sql = "select * from scratchcard_main
                where scratchcard_isDeleted = 0
                AND WEIXIN_ID = $weixinID
                AND scratchcard_id = $scratchcard_id";
        $scratchcardMainInfoArr = getlineBySql($sql);
        if($scratchcardMainInfoArr){
            $scratchcard_detail_description = $scratchcardMainInfoArr['scratchcard_detail_description'];
            $scratchcard_detail_probability = $scratchcardMainInfoArr['scratchcard_detail_probability'];
            $scratchcard_detail_count = $scratchcardMainInfoArr['scratchcard_detail_count'];
            
            
            //修正bug 取出的数据不是数据或者为空的情况下,返回值为空 20150925
            if($scratchcard_detail_description){
                $detailInfoDescription = json_decode($scratchcard_detail_description);
            }else{
                $detailInfoDescription = "";
            }
            if($scratchcard_detail_probability){
                $detailInfoProbability = json_decode($scratchcard_detail_probability);
            }else{
                $detailInfoProbability = "";
            }
            if($scratchcard_detail_count){
                $detailInfoCount = json_decode($scratchcard_detail_count);
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
    $mainInfoStr = addslashes($_POST["mainInfoStr"]);
    $scratchcard_id = addslashes($_POST["scratchcard_id"]);
    
    
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

    $thisscratchcard_title = $mainInfoArr[0];
    $thisscratchcard_description = $mainInfoArr[1];
    $thisscratchcard_times = $mainInfoArr[2];
    $thisscratchcard_Integral = $mainInfoArr[3];
    $thisscratchcard_beginDate = $mainInfoArr[4];
    $thisscratchcard_endDate = $mainInfoArr[5];
    $thisscratchcard_expirationDate = $mainInfoArr[6];
    $thisscratchcard_address = $mainInfoArr[7];
    $thisscratchcard_count = $mainInfoArr[8];

    //取得当前插入的时间
    $nowtime=date("Y/m/d H:i:s",time());
    
    if(!$scratchcard_id){
        $sql = "insert into scratchcard_main
                            (WEIXIN_ID,
                            scratchcard_title,
                            scratchcard_description,
                            scratchcard_times,
                            scratchcard_Integral,
                            scratchcard_beginDate,
                            scratchcard_endDate,
                            scratchcard_expirationDate,
                            scratchcard_address,
                            scratchcard_count,
                            scratchcard_detail_name,
                            scratchcard_detail_description,
                            scratchcard_detail_probability,
                            scratchcard_detail_count,
                            scratchcard_insertTime
                            ) values (
                            $weixinID,
                            '$thisscratchcard_title',
                            '$thisscratchcard_description',
                            $thisscratchcard_times,
                            $thisscratchcard_Integral,
                            '$thisscratchcard_beginDate',
                            '$thisscratchcard_endDate',
                            '$thisscratchcard_expirationDate',
                            '$thisscratchcard_address',
                            $thisscratchcard_count,
                            '$newTitle',
                            '$newDescription',
                            '$newProbability',
                            '$newCount',
                            '$nowtime'
                            )";
    }else{
        $sql =  "update scratchcard_main
                 set scratchcard_title='$thisscratchcard_title',
                     scratchcard_description='$thisscratchcard_description',
                     scratchcard_times=$thisscratchcard_times,
                     scratchcard_Integral=$thisscratchcard_Integral,
                     scratchcard_beginDate='$thisscratchcard_beginDate',
                     scratchcard_endDate='$thisscratchcard_endDate',
                     scratchcard_expirationDate='$thisscratchcard_expirationDate',
                     scratchcard_address='$thisscratchcard_address',
                     scratchcard_count=$thisscratchcard_count,
                     scratchcard_detail_name = '$newTitle',
                     scratchcard_detail_description='$newDescription',
                     scratchcard_detail_probability='$newProbability',
                     scratchcard_detail_count='$newCount',
                     scratchcard_editTime = '$nowtime'
                 where scratchcard_id=$scratchcard_id
                 AND WEIXIN_ID = $weixinID";
    }
    $resultErrorNo = SaeRunSql($sql);
    
    if($resultErrorNo != 0)
    {
        $arr['success'] = "NG";
        $arr['msg'] = "设置失败！";
    }else{
        $arr['success'] = "OK";
        $arr['msg'] = "设置成功！";
    }
    echo json_encode($arr);	
    exit;
}
