<?php /* Smarty version Smarty-3.1.16, created on 2016-03-28 17:08:34
         compiled from "tpl\admin\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1576256f361cd806d13-29591601%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '960134157fa259e4e66125a4a4ef37297a26b6d9' => 
    array (
      0 => 'tpl\\admin\\index.html',
      1 => 1459156103,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1576256f361cd806d13-29591601',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_56f361cd85cc36_36812573',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56f361cd85cc36_36812573')) {function content_56f361cd85cc36_36812573($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="Admin/login/css/index.css" type="text/css" media="screen" />
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
<script type="text/javascript" src="Admin/login/js/login.js?v=20150934"></script>
</body>
</html>
<?php }} ?>
