<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$weixinID = $_SESSION['weixinID'];
$sql = "select * from AdminToWeiID where weixinStatus = 1 AND id =$weixinID";
$weixinInfo = getLineBySql($sql);


$APPID = $weixinInfo['weixinAppId'];
$APPSECRET = $weixinInfo['weixinAppSecret'];

$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;

$json=file_get_contents($TOKEN_URL);
$result=json_decode($json,true);

$ACC_TOKEN= $result['access_token'];

$sql="select * from menuInfo where WEIXIN_ID = '$weixinID'";

$menuInfo = getlineBySql($sql);

$menu_name1 = $menuInfo['menu_name1'];
$menu_msgType1 = $menuInfo['menu_msgType1'];
$menu_content1 = $menuInfo['menu_content1'];

$menu_name2 = $menuInfo['menu_name2'];
$menu_msgType2 = $menuInfo['menu_msgType2'];
$menu_content2 = $menuInfo['menu_content2'];

$menu_name3 = $menuInfo['menu_name3'];
$menu_msgType3 = $menuInfo['menu_msgType3'];
$menu_content3 = $menuInfo['menu_content3'];


$menu_subNameForBtn1 = json_decode($menuInfo['menu_subNameForBtn1']);
$menu_subMsgTypeForBtn1 = json_decode($menuInfo['menu_subMsgTypeForBtn1']);
$menu_subContentForBtn1 = json_decode($menuInfo['menu_subContentForBtn1']);

$menu_subNameForBtn2 = json_decode($menuInfo['menu_subNameForBtn2']);
$menu_subMsgTypeForBtn2 = json_decode($menuInfo['menu_subMsgTypeForBtn2']);
$menu_subContentForBtn2 = json_decode($menuInfo['menu_subContentForBtn2']);

$menu_subNameForBtn3 = json_decode($menuInfo['menu_subNameForBtn3']);
$menu_subMsgTypeForBtn3 = json_decode($menuInfo['menu_subMsgTypeForBtn3']);
$menu_subContentForBtn3 = json_decode($menuInfo['menu_subContentForBtn3']);

$str = '{"button":[';

$menu_subMsgTypeForBtn1Count = count($menu_subMsgTypeForBtn1);
$menu_subMsgTypeForBtn2Count = count($menu_subMsgTypeForBtn2);
$menu_subMsgTypeForBtn3Count = count($menu_subMsgTypeForBtn3);
if($menu_subMsgTypeForBtn1Count == 0){
    if($menu_msgType1){
        if($menu_msgType1=='click'){
            $str.='{
                    "type":"'.$menu_msgType1.'",
                    "name":"'.$menu_name1.'",
                    "key":"'.$menu_content1.'"
                    }';
        }else if ($menu_msgType1=='view'){
            $str.='{
                    "type":"'.$menu_msgType1.'",
                    "name":"'.$menu_name1.'",
                    "url":"'.$menu_content1.'"
                    }';
        }
        if($menu_msgType2 || $menu_subMsgTypeForBtn2 || $menu_msgType3 || $menu_subMsgTypeForBtn3){
            $str.=',';
        }
    }
}else{
    $str.='{"name":"'.$menu_name1.'","sub_button":[';

    for($i=0; $i<$menu_subMsgTypeForBtn1Count; $i++){

        if($menu_subMsgTypeForBtn1[$i]=='click'){
            $str.='{
                    "type":"'.$menu_subMsgTypeForBtn1[$i].'",
                    "name":"'.$menu_subNameForBtn1[$i].'",
                    "key":"'.$menu_subContentForBtn1[$i].'"
                    }';
        }else if ($menu_subMsgTypeForBtn1[$i]=='view'){
            $str.='{
                    "type":"'.$menu_subMsgTypeForBtn1[$i].'",
                    "name":"'.$menu_subNameForBtn1[$i].'",
                    "url":"'.$menu_subContentForBtn1[$i].'"
                    }';
        }
        if($i != ($menu_subMsgTypeForBtn1Count-1) ){
            $str.=',';
        }else{
            $str.=']}';
            if($menu_msgType2 || $menu_subMsgTypeForBtn2 || $menu_msgType3 || $menu_subMsgTypeForBtn3){
                $str.=',';
            }
        }
    }
}

if($menu_subMsgTypeForBtn2Count == 0){
    if($menu_msgType2){
        if($menu_msgType2=='click'){
            $str.='{
                    "type":"'.$menu_msgType2.'",
                    "name":"'.$menu_name2.'",
                    "key":"'.$menu_content2.'"
                    }';
        }else if ($menu_msgType2=='view'){
            $str.='{
                    "type":"'.$menu_msgType2.'",
                    "name":"'.$menu_name2.'",
                    "url":"'.$menu_content2.'"
                    }';
        }
        if($menu_msgType3 || $menu_subMsgTypeForBtn3 ){
            $str.=',';
        }
    }
}else{
    $str.='{"name":"'.$menu_name2.'","sub_button":[';

    for($i=0; $i<$menu_subMsgTypeForBtn2Count; $i++){

        if($menu_subMsgTypeForBtn2[$i]=='click'){
            $str.='{
                    "type":"'.$menu_subMsgTypeForBtn2[$i].'",
                    "name":"'.$menu_subNameForBtn2[$i].'",
                    "key":"'.$menu_subContentForBtn2[$i].'"
                    }';
        }else if ($menu_subMsgTypeForBtn2[$i]=='view'){
            $str.='{
                    "type":"'.$menu_subMsgTypeForBtn2[$i].'",
                    "name":"'.$menu_subNameForBtn2[$i].'",
                    "url":"'.$menu_subContentForBtn2[$i].'"
                    }';
        }
        if($i != ($menu_subMsgTypeForBtn2Count - 1) ){
            $str.=',';
        }else{
            $str.=']}';
            if($menu_msgType3 || $menu_subMsgTypeForBtn3){
                $str.=',';
            }
        }
    }
}

if($menu_subMsgTypeForBtn3Count == 0){
    if($menu_msgType3){
        if($menu_msgType3=='click'){
            $str.='{
                    "type":"'.$menu_msgType3.'",
                    "name":"'.$menu_name3.'",
                    "key":"'.$menu_content3.'"
                    }';
        }else if ($menu_msgType3=='view'){
            $str.='{
                    "type":"'.$menu_msgType3.'",
                    "name":"'.$menu_name3.'",
                    "url":"'.$menu_content3.'"
                    }';
        }
    }
}else{
    $str.='{"name":"'.$menu_name3.'","sub_button":[';

    for($i=0; $i<$menu_subMsgTypeForBtn3Count; $i++){

        if($menu_subMsgTypeForBtn3[$i]=='click'){
            $str.='{
                    "type":"'.$menu_subMsgTypeForBtn3[$i].'",
                    "name":"'.$menu_subNameForBtn3[$i].'",
                    "key":"'.$menu_subContentForBtn3[$i].'"
                    }';
        }else if ($menu_subMsgTypeForBtn3[$i]=='view'){
            $str.='{
                    "type":"'.$menu_subMsgTypeForBtn3[$i].'",
                    "name":"'.$menu_subNameForBtn3[$i].'",
                    "url":"'.$menu_subContentForBtn3[$i].'"
                    }';
        }
        if($i != ($menu_subMsgTypeForBtn3Count-1)){
            $str.=',';
        }else{
            $str.=']}';
        }
    }
}
$str .=']}';
$MENU_URL= "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;

$ch = curl_init($MENU_URL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length:'.strlen($str)));

$info = curl_exec($ch);
//创建成功返回：{"errcode":0,"errmsg":"ok"}
$menu = json_decode($info);
//return $menu->errcode;
return $menu;