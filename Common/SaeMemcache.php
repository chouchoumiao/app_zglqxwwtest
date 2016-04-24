<?php
///**
// * 数据库中取得Config数据，并存入缓存
// * User: wujiayu
// * Date: 15/9/27
// * Time: 14:02
// */
//
///**
// * 函数名：getConfigWithMMC
// * 函数内容：取得基本设置内容
// * @param $weixinID 传入微信ID
// * @return 基本设置信息的数组
// */
//function getConfigWithMMC($weixinID){
//    $mmc = memcache_init();
//    $configName = $weixinID.'config';
//    $config = memcache_get($mmc,$configName);
//
//    if(empty($config)){
//
//        //取得config的数据并存入缓存
//        $sql = "select CONFIG_INTEGRALINSERT,
//                       CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
//                       CONFIG_INTEGRALREFERRER,
//                       CONFIG_INTEGRALSETDAILY,
//                       CONFIG_DAILYPLUS,
//                       CONFIG_VIP_NAME from ConfigSet
//                where WEIXIN_ID = $weixinID";
//        $configLineData = getLineBySql($sql);
//
//        //如果取得不存在 则初始化为 0,0,0,0,0,'积分'
//        if(!$configLineData){
//            $sql = "insert into ConfigSet
//                                (WEIXIN_ID,
//                                CONFIG_INTEGRALINSERT,
//                                CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
//                                CONFIG_INTEGRALREFERRER,
//                                CONFIG_INTEGRALSETDAILY,
//                                CONFIG_DAILYPLUS,
//                                CONFIG_VIP_NAME
//                                ) values (
//                                $weixinID,
//                                0,
//                                0,
//                                0,
//                                0,
//                                0,
//                                '积分'
//                                )";
//            $errono = SaeRunSql($sql);
//            if($errono == 0){
//                $configArr = array(
//                    "CONFIG_INTEGRALINSER" =>0,
//                    "CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP"=>0,
//                    "CONFIG_INTEGRALREFERRER"=>0,
//                    "CONFIG_INTEGRALSETDAILY"=>0,
//                    "CONFIG_DAILYPLUS"=>0,
//                    "CONFIG_VIP_NAME"=>'积分'
//                );
//            }else{
//                $configArr = '';
//            }
//        }else{
//            //存在则取得并 还原为数据
//            $configArr =$configLineData;
//        }
//
//        //将数组内容缓存
//        memcache_set($mmc,$configName,$configArr,0,6000);
//        //取得缓存
//        $config = memcache_get($mmc,$configName);
//    }
//    return $config;
//}
//
///**
// * 函数名：getVipWithMMC
// * 函数内容：取得会员基本信息
// * @param $weixinID
// * @param $openid
// * @return 会员信息
// */
///*function getVipWithMMC($weixinID,$openid){
//    $mmc = memcache_init();
//    $vipInfo = memcache_get($mmc,'vipInfo');
//    if(empty($vipInfo)){
//        //取得Vip的数据并存入缓存
//        $sql = "Select * FROM Vip WHERE Vip_openid='$openid' AND Vip_isDeleted = 0 AND WEIXIN_ID = $weixinID";
//        $info = getlineBySql($sql);
//        memcache_set($mmc,'vipInfo',$info,0,600);
//        $vipInfo = memcache_get($mmc,'vipInfo');
//    }
//    return $vipInfo;
//}*/