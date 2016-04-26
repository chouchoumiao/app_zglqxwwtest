<?php
	function C($name, $method){
		require_once('/libs/controller/'.$name.'Controller.class.php');
		eval('$obj = new '.$name.'Controller();$obj->'.$method.'();');
	}

	function M($name){
		require_once('/libs/Model/'.$name.'Model.class.php');
		//$testModel = new testModel();
		eval('$obj = new '.$name.'Model();');
		return $obj;
	}
	
	function V($name){
		require_once('/libs/View/'.$name.'View.class.php');
		//$testView = new testView();
		eval('$obj = new '.$name.'View();');
		return $obj;
	}
	
	function ORG($path, $name, $params=array()){// path 是路径  name是第三方类名 params 是该类初始化的时候需要指定、赋值的属性，格式为 array(属性名=>属性值, 属性名2=>属性值2……)
		require_once('libs/ORG/'.$path.$name.'.class.php');
		//eval('$obj = new '.$name.'();');
		$obj = new $name();
		if(!empty($params)){
		foreach($params as $key=>$value){
				//eval('$obj->'.$key.' = \''.$value.'\';');
				$obj->$key = $value;
			}
		}
		return $obj;
	}

	function daddslashes($str){
		return (!get_magic_quotes_gpc())?addslashes($str):$str;
	}

function getConfigWithMMC($weixinID)
{
	//取得config的数据并存入缓存
	$sql = "select CONFIG_INTEGRALINSERT,
				   CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
				   CONFIG_INTEGRALREFERRER,
				   CONFIG_INTEGRALSETDAILY,
				   CONFIG_DAILYPLUS,
				   CONFIG_VIP_NAME from ConfigSet
			where WEIXIN_ID = $weixinID";
	$configLineData = DB::findOne($sql);

	//如果取得不存在 则初始化为 0,0,0,0,0,'积分'
	if (!$configLineData) {
		$sql = "insert into ConfigSet
							(WEIXIN_ID,
							CONFIG_INTEGRALINSERT,
							CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP,
							CONFIG_INTEGRALREFERRER,
							CONFIG_INTEGRALSETDAILY,
							CONFIG_DAILYPLUS,
							CONFIG_VIP_NAME
							) values (
							$weixinID,
							0,
							0,
							0,
							0,
							0,
							'积分'
							)";
		$isOK = DB::query($sql);
		if (!$isOK) {
			return array(
				"CONFIG_INTEGRALINSER" => 0,
				"CONFIG_INTEGRAL_REFERRER_FOR_NEW_VIP" => 0,
				"CONFIG_INTEGRALREFERRER" => 0,
				"CONFIG_INTEGRALSETDAILY" => 0,
				"CONFIG_DAILYPLUS" => 0,
				"CONFIG_VIP_NAME" => '积分'
			);
		} else {
			return array();
		}
	}
	return $configLineData;
}

/**
 * 跳转函数
 * @param $url
 */
function gotoUrl($url){
	echo "<script>window.location.href='$url'</script>";
	exit;
}


//获取IP地址
function GetIP()
{
	$unknown = 'unknown';
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	/**
	 * 处理多层代理的情况
	 * 或者使用正则方式：$ip = <a href="https://www.baidu.com/s?wd=preg_match&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1d9mHf1m1Pbm1bsnj61PHcs0AP8IA3qPjfsn1bkrjKxmLKz0ZNzUjdCIZwsrBtEXh9GuA7EQhF9pywdQhPEUiqkIyN1IA-EUBt1PWDvnWRdPWcvn10drjD3PHc" target="_blank" class="baidu-highlight">preg_match</a>("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
	 */
	//修改追加变量来接受，去除警告 20160312
	$tmp1 = strpos($ip, ',');
	if (false !== $tmp1) {
		$tmp2 = explode(',', $ip);
		$ip = reset($tmp2);
	}
	return $ip;
}

//翻页

//分页
function multi($num, $perpage, $curpage, $mpurl, $ajax=0, $ajax_f='',$flag='') {


	$page = 5;
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = @ceil($num / $perpage);
		$pages = $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$multipage = '';
		if($curpage - $offset > 1 && $pages > $page) {
			$multipage .= "<a ";
			if($ajax) {
				$multipage .= "href=\"javascript:{$ajax_f}($flag,1);\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
			}
			$multipage .= " class=\"first\">首</a>";
		}
		if($curpage > 1) {
			$multipage .= "<a ";
			if($ajax) {
				$multipage .= "href=\"javascript:{$ajax_f}($flag,".($curpage-1).");\" ";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage-1)."$urlplus\"";
			}
			$multipage .= " class=\"prev\">&lt;&lt; </a>";
		}
		for($i = $from; $i <= $to; $i++) {
			if($i == $curpage) {
				$multipage .= '<a href="###" class="cur">'.$i.'</strong>';
			} else {
				$multipage .= "<a ";
				if($ajax) {
					$multipage .= "href=\"javascript:{$ajax_f}($flag,$i);\" ";
				} else {
					$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
				}
				$multipage .= ">&nbsp$i&nbsp</a>";
			}
		}
		if($curpage < $pages) {
			$multipage .= "<a ";
			if($ajax) {
				$multipage .= "href=\"javascript:{$ajax_f}($flag,".($curpage+1).");\" ";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage+1)."{$urlplus}\"";
			}
			$multipage .= " class=\"next\"> &gt;&gt;</a>";
		}
		if($to < $pages) {
			$multipage .= "<a ";
			if($ajax) {
				$multipage .= "href=\"javascript:{$ajax_f}($flag,$pages);\" ";
			} else {
				$multipage .= "href=\"{$mpurl}page=$pages{$urlplus}\"";
			}
			$multipage .= " class=\"last\">尾</a>";
		}
		if($multipage) {
			//$multipage = '<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage;
		}
	}
	return $multipage;
}

//判断输入data是否为空
function strIsNull($data,$message)
{
	if($data==='')
	{
		echo "<script>alert('$message');history.back();</Script>";
		exit;

	}
}

//判断输入data是否为空，已经是否数字
function isNum($data,$message1,$message2)
{
	if($data === '')
	{
		echo "<script>alert('$message1');history.back();</Script>";
		exit;

	}else if(!is_numeric($data)){
		echo "<script>alert('$message2');history.back();</Script>";
		exit;
	}
}


//判断输入data是否是fromdata和todata范围内的数据  -by 20150320
function isThisRangeNum($data,$fromdata,$todata,$message)
{
	//echo "<script>alert('$message');history.back();</Script>";
	if(($data < $fromdata)||($data > $todata))
	{
		echo "<script>alert('必须是'+$fromdata+'到'+$todata+'范围内的数字');history.back();</Script>";
		exit;
	}
}

//比较date1，date2相差的天数
function dateDiffer($date1,$date2,$message)
{
	if((strtotime($date1) - strtotime($date2))/86400 < 0){
		echo "<script>alert('$message');history.back();</Script>";
		exit;
	}
}

//判断date是否为日期格式
function isDateOrNot($data,$message)
{
	if(!isdate($data))
	{
		echo "<script>alert('$message');history.back();</Script>";
		exit;

	}
}
//判断日期格式子函数
function isdate($str,$format="Y-m-d"){
	$strArr = explode("-",$str);
	if(empty($strArr)){
		return false;
	}
	foreach($strArr as $val){
		if(strlen($val)<2){
			$val="0".$val;
		}
		$newArr[]=$val;
	}
	$str =implode("-",$newArr);
	$unixTime=strtotime($str);
	$checkDate= date($format,$unixTime);
	if($checkDate==$str)
		return true;
	else
		return false;
}

//获取用户真实IP
function get_client_ip() {
	$ip=$_SERVER["REMOTE_ADDR"];
	return $ip;
}

//判断是否是会员，不是则跳转到注册页面
function isVipByOpenid($openid,$weixinID,$nowUrl){
	if(!isset($openid) || !isset($weixinID)){
		echo "OpenID OR  WeixinID Error";
		exit;
	}

	if(!vipInfo($openid,$weixinID)){
		$url  =  "http://".$_SERVER['HTTP_HOST']."/APP/01_vipCenter/VipBD.php?openid=".$openid."&weixinID=".$weixinID."&url=".$nowUrl;

		echo "<script> location = '$url'</script>";
		exit;
	}
}

//生成token函数
function getToken( $len = 32, $md5 = true ) {
	# Seed random number generator
	# Only needed for PHP versions prior to 4.2
	mt_srand( (double)microtime()*1000000 );
	# Array of characters, adjust as desired
	$chars = array(
		'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
		'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
		'/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
		'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
		'?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
		'=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
		'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
	);
	# Array indice friendly number of chars;
	$numChars = count($chars) - 1;
	$token = '';
	# Create random token at the specified length
	for ( $i=0; $i<$len; $i++ )
		$token .= $chars[ mt_rand(0, $numChars) ];
	# Should token be run through md5?
	if ( $md5 ) {
		# Number of 32 char chunks
		$chunks = ceil( strlen($token) / 32 ); $md5token = '';
		# Run each chunk through md5
		for ( $i=1; $i<=$chunks; $i++ )
			$md5token .= md5( substr($token, $i * 32 - 32, 32) );
		# Trim the token
		$token = substr($md5token, 0, $len);
	}
	return $token;
}

//输出HTML的Warning
function echoWarning($msg){
	echo '<html>
        <head>
        <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
        <style>
        .wrap{margin:0 auto;width:80%;text-align: center;}
        </style>
        </head>
        <body>
            <div class="wrap">
            	<br/>
                <div id="myMsg" class="alert alert-warning">'.$msg.'</div>
            </div>
        </body>
    </html>';
}

//输出HTML的Info
function echoInfo($msg){
	echo '<html>
        <head>
        <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
        <style>
        .wrap{margin:0 auto;width:80%;text-align: center;}
        </style>
        </head>
        <body>
            <div class="wrap">
                <br/>
                <div id="myMsg" class="alert alert-success">'.$msg.'</div>
            </div>
        </body>
    </html>';
}

/* *
* 对变量进行 JSON 编码
* @param mixed value 待编码的 value ，除了resource 类型之外，可以为任何数据类型，该函数只能接受 UTF-8 编码的数据
* @return string 返回 value 值的 JSON 形式
* PHP 5.4版本后直接加  JSON_UNESCAPED_UNICODE 关键字
*
*/
function getPreg_replace( $value)
{
	if ( version_compare( PHP_VERSION,'5.4.0','<'))
	{
		$str = json_encode( $value);
		$str =  preg_replace_callback(
			"#\\\u([0-9a-f]{4})#i",
			function( $matchs)
			{
				return  iconv('UCS-2BE', 'UTF-8',  pack('H4',  $matchs[1]));
			},
			$str
		);
		return  $str;
	}
	else
	{
		return json_encode( $value, JSON_UNESCAPED_UNICODE);
	}
}
//用于json文字转化
//function getPreg_replace($arr){
//    return preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", json_encode($arr));
//}

//URL地址参数加密
function myURLEncode($str){
	return base64_encode(base64_encode($str));
}

//URL地址参数解密
function myURLDecode($str){
	return base64_decode(base64_decode($str));
}

//传入参数 长度判断
function isParameterOK($p,$len){
	if(!isset($p) || strlen($p) != $len){
		echo "传入参数不正确，请确认！";
		exit;
	}
}

//传入参数OpenID和WeixinID 长度判断
//openid固定长：28,weixinID固定长：2
function isOpenIDWeixinIDOK($openid,$weixinID,$msg){
	if(!isset($openid) || !isset($weixinID) || strlen($openid) != 28 || strlen($weixinID) != 2){
		echo '<html>
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
                <link href="//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css" rel="stylesheet">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
                <style>
                .wrap{
                    position: absolute;
                    width:90%;
                    height:200px;
                    left:50%;
                    top:50%;
                    margin-left:-45%;
                    margin-top:-100px;
                    text-align:center;
                }
                </style>
                </head>
                <body>
                    <div class="wrap">
                        <div id="myMsg" class="alert alert-danger"><h3>'.$msg.'</h3></div>
                    </div>
                </body>
            </html>';
		exit;
	}
}
