<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = addslashes($_GET['weixinID']);
$nowDate = date("Y-m-d",time());
if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

$sql = "select * from vipDailySet
        where editDate = '$nowDate'
        and WEIXIN_ID = '$weixinID'
        and flag = 1";
$data = getlineBySql($sql);
if(!$data['dailyCode']){
    $code = randomkeys(12);
}else{
    $code = $data['dailyCode'];
}

//数据库表中已经存在当天的数据
if(!$data['dailyCode']){
    
    $sql = "update vipDailySet set flag = 0 where WEIXIN_ID = '$weixinID'";
    SaeRunSql($sql);
    
    $sql = "insert into vipDailySet
                        (WEIXIN_ID,
                        dailyCode,
                        editDate,flag
                        ) values (
                        $weixinID,
                        '$code',
                        '$nowDate',
                        1
                        )";
    $errono = SaeRunSql($sql);

    if($errono != 0){
        $arr['success'] = "NG";
        $arr['msg'] = "取得失败！";
    }else{
        $arr['success'] = "OK";
        $arr['msg'] = $code;
        $arr['date'] = $nowDate;
    }
}else{
    $arr['success'] = "OK";
    $arr['msg'] = $code;
    $arr['date'] = $nowDate;
}

echo json_encode($arr);
exit;

function randomkeys($length)
{
    $key ="";
    $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for($i=0;$i<$length;$i++)
    {
        $key .= $pattern{mt_rand(0,35)};    //生成php随机数
    }
    return $key;
}
?>