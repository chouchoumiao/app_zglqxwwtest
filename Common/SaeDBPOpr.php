<?php
/*****************************/
/*	公用函数				 */
/*	date:2014/11/02          */
/*	臭臭喵工作室             */
/*****************************/

//根据openid判断是否存在该会员信息
function vipInfo($openid,$weixinID)
{
	$mysql = SaeConfigSet();
	$sql = "Select * FROM Vip
			WHERE Vip_openid='$openid'
			AND Vip_isDeleted = 0
			AND WEIXIN_ID = $weixinID";
	$arr = $mysql->getData($sql);
	$mysql->closeDb();
	return $arr;

}
/************************************************************************************公用数据库操作********************************************************************************/
/*********************************************************************************↓↓↓↓↓↓↓↓↓↓*****************************************************************************/
//根据传入的sql文，更新/插入操作
function SaeRunSql($sql)
{
	$mysql = SaeConfigSet();
	$mysql->runSql($sql);
	$mysql->closeDb();
	return $mysql->errno();
}

//根据sql取得并返回所有数据  代替原所有getData函数
function getDataBySql($sql,$result_type=MYSQL_ASSOC){
	//$mysql = SaeConfigSet();
	//$arr = $mysql->getData($sql);
	//$mysql->closeDb();
	//return $arr;

	$result=mysql_query($sql);
	if ($result && mysql_num_rows($result)>0){
		while ($row=mysql_fetch_array($result,$result_type)){
			$rows[]=$row;
		}
		return $rows;
	}else {
		return false;
	}

}
//根据sql取得并返回所有数据  代替原所有getVar函数
function getVarBySql($sql)
{

	$mysql = SaeConfigSet();
	$count = $mysql->getVar($sql);
	$mysql->closeDb();
	return $count;
}
//根据sql取得并返回所有数据  代替原所有getline函数
function getlineBySql($sql,$result_type=MYSQL_ASSOC)
{
	$mysql = SaeConfigSet();

	$result=$mysql->mysql_query($sql,$mysql);
	if ($result && mysql_num_rows($result)>0){
		return mysql_fetch_array($result,$result_type);
	}else {
		return false;
	}

	//$mysql = SaeConfigSet();
	//$arr = $mysql->getline($sql);
	//$mysql->closeDb();
	//return $arr;
}

/***********************************************************************************数据库连接设置********************************************************************************/
/*********************************************************************************↓↓↓↓↓↓↓↓↓↓*****************************************************************************/

//数据库设置为连接同一个
function SaeConfigSet(){

	//设置DB链接
	//$saeMysql = new SaeMysql();
	$appname  = 'app_zglqxwwtest';   //app名
	$username = 'root'; //MySQL用户名
	$password = 'root'; //MySQL密码
	$conn = mysql_connect($appname,$username,$password);//root是帐号,123456是密码
	$db=mysql_select_db('app_zglqxwwtest',$conn); //testdatabase是mysql数据库名
	//$appname  = $_SERVER['HTTP_APPNAME'];   //app名
	//$username = SAE_MYSQL_USER; //MySQL用户名
	//$password = SAE_MYSQL_PASS; //MySQL密码
	//$port     = SAE_MYSQL_PORT; //MySQL端口
	//$saeMysql->setAppname($appname);
	//$saeMysql->setAuth($username,$password);
	//$saeMysql->setPort($port);

	return $db;


}
//根据SN取得取得该会员的中奖信息 20141201
function getWinningInfoBySNCode($SnCode,$weixinID)
{
	$mysql = new SaeMysql();
	$mysql = SaeConfigSet($mysql);
	$sql = "select * from  bill
			where WEIXIN_ID = $weixinID
			AND Bill_SN
			LIKE '%$SnCode'
			order by Bill_id";
	$arr = $mysql->getline($sql);
	$mysql->closeDb();
	return $arr;
	//return $sql;
}
?>
