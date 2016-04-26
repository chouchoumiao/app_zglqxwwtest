<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理公众号</title>

<link rel="stylesheet" href="css/pageFormart.css" type="text/css" media="screen" />
<link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

<style>
.account{padding:15px; margin:0; overflow:hidden;}
.account .pull-right a{font-size:16px; margin-left:10px; font-weight:normal;}
.account .input-append{width:90%; position:relative;margin-bottom:0;}
.account .input-append input{width:90%; width:87%\9;}
.account .input-append button{width:8%; min-width:40px;}
.account .tbody{background:#FAFAFA; margin-bottom:10px; border:1px #DDD solid; border-top:1px #FFF solid; padding:5px 10px;}
.account .tbody .con{margin:10px 0; overflow:hidden;}
.account .tbody .name{width:10%; overflow:hidden;}
.account .thead{padding:0 10px; border-color:#DDD; border-bottom:1px #EEE solid; border-top:2px #00AFF0 solid; -webkit-border-radius:0; -moz-border-radius:0; border-radius:0;}
.account h4{color:#333;font-weight:normal;overflow:hidden;}
.main {
	margin-left:0px;
	background:#FFF;
	position:relative;
	word-wrap:break-word;
	/*min-width:800px;*/
	width:100%;
}

</style>

<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');
    $user = $_SESSION['user'];
    $sql = "select * from AdminToWeiID
            where weixinStatus = 1
            AND username = '$user'";
    $weixinInfo = getDataBySql($sql);
?>
</head>
<body>
<div class="div_from_aoto">
    <form>
    <?php 
        if($weixinInfo){
            $weixinInfoCount = count($weixinInfo);
            for($i=0; $i<$weixinInfoCount; $i++){
    ?>
            <div class = "main">
                <div class="account">
                    <div class="navbar-inner thead form-group">
                        <h4>
                            <span class="pull-right"><a onclick="return confirm('删除帐号将同时删除全部规则及回复，确认吗？');return false;" href="./weixinIDAddNew.php?action=delete&weixinID=<?php echo $weixinInfo[$i]['id'];?>">删除</a><a href="./weixinIDAddNew.php?weixinID=<?php echo $weixinInfo[$i]['id'];?>">编辑</a></span>
                            <span class="pull-left"><?php echo $weixinInfo[$i]['weixinName'];?> <small>（微信号：<?php echo $weixinInfo[$i]['weixinAppId'];?>）</small></span>
                        </h4>
                    </div>
                    <div class="tbody">
                        <div class="con">
                            <div class="name pull-left">API地址</div>
                            <div class="input-append pull-left">
                                <p><input id="" type="text" class="form-control" value=<?php echo $weixinInfo[$i]['weixinUrl'];?>>
                            </div>
                        </div>
                        <div class="con">
                            <div class="name pull-left">Token</div>
                            <div class="input-append pull-left">
                                <input id="" type="text" class="form-control" value=<?php echo $weixinInfo[$i]['weixinToken'];?>>
                           </div>
                        </div>
                    </div>
                </div>
            </div>    
    <?php   
            }
        }else{
            $msg = "当前未设置过公众号，请添加公众号信息！";
            echoInfo($msg);
        }
    ?>
	
    </form>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
</body>

</html>