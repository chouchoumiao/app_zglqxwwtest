<?php /* Smarty version Smarty-3.1.16, created on 2016-04-01 14:06:52
         compiled from "tpl\admin\login.html" */ ?>
<?php /*%%SmartyHeaderCode:2590756fb8a5dcb6ed1-52219530%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c80e3fb42803f2b44cb2faa36ed4473503b114f' => 
    array (
      0 => 'tpl\\admin\\login.html',
      1 => 1459490802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2590756fb8a5dcb6ed1-52219530',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_56fb8a5dd18965_92159944',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56fb8a5dd18965_92159944')) {function content_56fb8a5dd18965_92159944($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="Admin/login/css/login.css" type="text/css" media="screen" />
<title>管理员设置画面-测试环境</title>
</head>
<body>
<div id="main">
    <div id="login">
      <h3>管理员登录</h3>
      <div id="login_form">
          <p>
              <label>用户名：</label>
              <input type="text" class="input" name="user" id="user" />
          </p>
          <p>
              <label>密 码：</label>
              <input type="password" class="input" name="pass" id="pass" />
          </p>
          <div class="sub">
              <input type="submit" class="btn" value="登 录" />
          </div>
      </div>
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="Admin/login/js/login.js?v=20150935"></script>
</body>
</html>
<?php }} ?>
