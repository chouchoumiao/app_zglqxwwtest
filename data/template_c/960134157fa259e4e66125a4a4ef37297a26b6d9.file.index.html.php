<?php /* Smarty version Smarty-3.1.16, created on 2016-04-01 14:32:27
         compiled from "tpl\admin\index.html" */ ?>
<?php /*%%SmartyHeaderCode:3150756fb899b67cb50-88667810%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '960134157fa259e4e66125a4a4ef37297a26b6d9' => 
    array (
      0 => 'tpl\\admin\\index.html',
      1 => 1459492312,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3150756fb899b67cb50-88667810',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_56fb899b7fb8b9_45729636',
  'variables' => 
  array (
    'userName' => 0,
    'weixinInfo' => 0,
    'foo' => 0,
    'weixinID' => 0,
    'weixinName' => 0,
    'isEventListExist' => 0,
    'eventNameArr' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56fb899b7fb8b9_45729636')) {function content_56fb899b7fb8b9_45729636($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信管理后台-测试环境</title>
<link rel="stylesheet" href="Admin/login/css/index.css?v=20150435" type="text/css" media="screen" />
<!-- script标签放在底下，刷新时候会有跳动，所以需要放在页面加载前-->
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="Admin/login/js/tendina.min.js?v=20150103"></script>
<script type="text/javascript" src="Admin/login/js/index.js?v=20150123" charset="utf-8"></script>
</head>
<body>

<!--顶部-->
<div class="layout_top_header">
    <div style="float: left">
        <span style="font-size:20px;line-height:45px;padding-left:20px;color:#8d8d8d">微信管理后台</span>
    </div>

    <div id = "ad_setting" class="ad_setting2">
        <a class="ad_setting_a">
            <i class="icon-user glyph-icon" style="font-size: 20px"></i>
            <span>管理员: &nbsp <?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
</span>
            <i class="icon-chevron-down glyph-icon"></i>
        </a>
        <ul class="dropdown-menu-uu" style="display: none" id="ad_setting_ul">
            <li class="ad_setting_ul_li"> <a href="tpl/admin/adminEdit.html" target="menuFrame">
                    <i class="icon-user glyph-icon"></i> 修改密码 </a> </li>
            <li class="ad_setting_ul_li"> <a href="admin.php?controller=admin&method=logout">
                    <i class="icon-signout glyph-icon" ></i>
                    <span class="font-bold" id='logout'>退出</span></a>
            </li>
        </ul>
    </div>
    <div class="ad_setting">
        <a class="ad_setting_a" >
            <span>
            <select style="font-size:15px;color:#8d8d8d" id = "weiIDSelect" onchange="getWeiID();">
                <?php  $_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['foo']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['weixinInfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->key => $_smarty_tpl->tpl_vars['foo']->value) {
$_smarty_tpl->tpl_vars['foo']->_loop = true;
?>
                <option value=<?php echo $_smarty_tpl->tpl_vars['foo']->value['id'];?>
><?php echo $_smarty_tpl->tpl_vars['foo']->value['weixinName'];?>
</option>
                <?php } ?>
            </select>
            </span>
        </a>
    </div>
    <div class="ad_setting">
        <a class="ad_setting_a" href="javascript:; ">
            <i style="font-size: 16px"></i>
            <span >当前公众号:</span>
        </a>
    </div>
</div>
<!--顶部结束-->
<!--菜单-->
<div class="layout_left_menu">
    <ul id="menu">
        <li class="childUlLi">
            <a href="tpl/admin/main.html"  target="menuFrame"><i class="glyph-icon icon-home"></i>首页</a>
            <ul>
                <li><a target="menuFrame" href="javascript:void(0)"><i class="glyph-icon icon-chevron-right"></i>管理公众号</a>
                    <ul>
                        <?php if ($_smarty_tpl->tpl_vars['weixinID']->value) {?>
                        <li><a target="menuFrame" href="javascript:void(0)">
                                <i class="glyph-icon icon-chevron-right2"></i><?php echo $_smarty_tpl->tpl_vars['weixinName']->value;?>

                            </a>
                            <ul>
                                <li><a href="../../Admin/forWexinID/weixinIDAddNew.php?weixinID=<<?php ?>?php echo $weiInfo['id'];?<?php ?>>" target="menuFrame">
                                        <i class="glyph-icon icon-chevron-right3"></i>编辑公众号基本设置</a>
                                </li>
                                <li><a href="../../Admin/forEventReply/eventReplySet.php?weixinID=<<?php ?>?php echo $weiInfo['id'];?<?php ?>>" target="menuFrame">
                                        <i class="glyph-icon icon-chevron-right3"></i>进入活动相关设置</a>
                                </li>
                                <li><a href="../../Admin/forWexinID/menuSet.php?weixinID=<<?php ?>?php echo $weiInfo['id'];?<?php ?>>" target="menuFrame">
                                        <i class="glyph-icon icon-chevron-right3"></i>自定义菜单设置</a>
                                </li>
                            </ul>
                        </li>
                        <?php } else { ?>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;尚未设置公众号信息!</a></li>
                        <?php }?>
                    </ul>
                    <li><a href="../../Admin/forWexinID/weixinIDAddNew.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i><font color="#FF0000">添加新公众号</font> </a>
                    </li>
                    <li><a href="../../Admin/forWexinID/editWeixinID.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>编辑公众号</a>
                    </li>

                    <li><a href="tpl/admin/adminSetMuneClass.html" target="menuFrame"><i class="glyph-icon icon-chevron-right"></i>菜单分类设置</a>
                    </li>
            </ul>
        </li>
        <li class="childUlLi">
            <a target="menuFrame" href="javascript:void(0)"> <i class="glyph-icon icon-reorder"></i>会员管理</a>
            <ul>
                <?php if ($_smarty_tpl->tpl_vars['isEventListExist']->value) {?>

                    <li><a href="../../Admin/froIntegralNewVip/integralNewVip.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>初始化设置</a></li>
                    <li><a href="../../Admin/froIntegralSetDaily/integralSetDaily.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>签到<?php echo $_smarty_tpl->tpl_vars['weixinName']->value;?>
</a></li>

                <?php } else { ?>

                    <li><a target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>尚未设置活动，请设置！</a></li>
                <?php }?>

            </ul>
        </li>
        <li class="childUlLi">
            <a target="menuFrame" href="javascript:void(0)"> <i class="glyph-icon  icon-location-arrow"></i>活动设置</a>
            <ul>
                <?php if ($_smarty_tpl->tpl_vars['isEventListExist']->value) {?>
                    <?php  $_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['foo']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['eventNameArr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->key => $_smarty_tpl->tpl_vars['foo']->value) {
$_smarty_tpl->tpl_vars['foo']->_loop = true;
?>
                        <?php if (strstr($_smarty_tpl->tpl_vars['foo']->value,"答题")) {?>
                            <li><a target="menuFrame" href="javascript:void(0)">
                                    <i class="glyph-icon icon-chevron-right"></i><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
                                    <ul>
                                        <li><a href="../../Admin/forAnswer/question_search.php" target="menuFrame">
                                                <i class="glyph-icon icon-chevron-right2"></i>结果查询</a>
                                        </li>
                                        <li><a href="../../Admin/forAnswer/question_master_manager.php" target="menuFrame">
                                                <i class="glyph-icon icon-chevron-right2"></i>主题信息</a>
                                        </li>
                                        <li><a href="../../Admin/forAnswer/question_manager.php" target="menuFrame">
                                                <i class="glyph-icon icon-chevron-right2"></i>题目信息</a>
                                        </li>
                                    </ul>
                            </li>
                        <?php } else { ?>
                        <?php $_smarty_tpl->tpl_vars['index'] = new Smarty_variable($_smarty_tpl->getVariable('smarty')->value['foreach']['foo']['index'], null, 0);?>
                        <li><a href=<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['foo']['index'];?>
 target="menuFrame">
                                <i class="glyph-icon icon-chevron-right"></i><?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</a>
                        </li>
                        <?php }?>
                    <?php } ?>
                <?php } else { ?>
                    <li><a target="menuFrame" href="javascript:void(0)">
                            <i class="glyph-icon icon-chevron-right"></i>尚未设置活动，请设置！</a>
                    </li>
                <?php }?>
            </ul>
        </li>
        <li class="childUlLi">
            <a target="menuFrame" href="javascript:void(0)"> <i class="glyph-icon icon-reorder"></i>用户管理</a>
            <ul>
                <?php if ($_smarty_tpl->tpl_vars['userName']->value=="gokayuwu") {?>

                    <li><a href="admin.php?controller=admin&method=showUserInfo" target="menuFrame"><i class="glyph-icon icon-chevron-right"></i>管理员权限用户查询</a></li>
                    <li><a href="tpl/admin/addUserByAdmin.html" target="menuFrame"><i class="glyph-icon icon-chevron-right"></i>管理员权限用户添加</a></li>
                <?php }?>
                <li><a href="tpl/admin/adminEdit.html" target="menuFrame">
                        <i class="glyph-icon icon-chevron-right"></i>修改密码</a></li>
            </ul>
        </li>
        <li class="childUlLi">
            <a target="menuFrame" href="javascript:void(0)"> <i class="glyph-icon icon-reorder"></i>查询功能</a>
            <ul>
                <?php if ($_smarty_tpl->tpl_vars['isEventListExist']->value) {?>
                    <li><a href="../../Admin/forSearchInfo/searchVipInfo.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>会员信息查询</a></li>
                    <li><a href="../../Admin/froIntegralSetDaily/getDailyCode.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>查询签到码</a></li>
                    <li><a href="../../Admin/forSearchInfo/searchQAClassInfo.php" target="menuFrame">
                        <i class="glyph-icon icon-chevron-right"></i>答题分类查询</a></li>
                    <li><a href="../../Admin/forSearchInfo/question_OKCountSearch.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>全答对时间排行</a></li>
                    <li><a href="../../Admin/forExchange/exchangeInfoSearch.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>奖品兑换情况查询</a>
                    <li><a href="../../Admin/forExchange/exchange.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>兑奖信息查询</a>
                    <li><a href="../../Admin/forForwardingGift/forwardingGiftInfoSearch.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>转发有礼查询</a>
                    <li><a href="../../Admin/forHongbao/hongbaoInfoSearch.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>红包测试</a>
                    <li><a href="../../Admin/forFlowerCity/flowerCityManger.php" target="menuFrame">
                            <i class="glyph-icon icon-chevron-right"></i>印章商城</a>
                    </li>
                <?php } else { ?>
                    <li><a target="menuFrame" href="javascript:void(0)">
                            <i class="glyph-icon icon-chevron-right"></i>尚未设置活动，请设置！</a>
                    </li>
                <?php }?>
            </ul>
        </li>
    </ul>
</div>
<!--菜单-->
<div id="layout_right_content" class="layout_right_content">
    <div class="mian_content">
        <div id="page_content">
            <iframe id="menuFrame" name="menuFrame" src="tpl/admin/main.html" style="overflow:visible;"
                scrolling="yes" frameborder="no" width="100%" height="100%"></iframe>
        </div>
    </div>
</div>
<div class="layout_footer">
    <p>Copyright © 2015-2016 - 臭臭喵工作室</p>
</div>
<script>
$(document).ready(function(){
    var weiID = "<?php echo $_smarty_tpl->tpl_vars['weixinID']->value;?>
";
    if (weiID){
        $("#weiIDSelect ").val(weiID);
    }
});
</script>
</body>
</html><?php }} ?>
