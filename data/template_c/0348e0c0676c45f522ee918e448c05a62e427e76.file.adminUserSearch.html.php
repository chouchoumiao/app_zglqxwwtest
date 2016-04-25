<?php /* Smarty version Smarty-3.1.16, created on 2016-04-01 16:46:30
         compiled from "tpl\admin\adminUserSearch.html" */ ?>
<?php /*%%SmartyHeaderCode:463256fe19d5f2b346-51948905%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0348e0c0676c45f522ee918e448c05a62e427e76' => 
    array (
      0 => 'tpl\\admin\\adminUserSearch.html',
      1 => 1459500185,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '463256fe19d5f2b346-51948905',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_56fe19d60fa681_72881957',
  'variables' => 
  array (
    'class_list' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56fe19d60fa681_72881957')) {function content_56fe19d60fa681_72881957($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<!--<script type="text/javascript" src="../../Admin/login/js/adminEdit.js?v=20150107"></script>-->
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="../js/multi.js"></script>
<script>
$(document).ready(function() {
    var pagecount = <<?php ?>?php echo $count;?<?php ?>>;
    var pagesize = <<?php ?>?php echo $page_num;?<?php ?>>;
    var currentpage = <<?php ?>?php echo $page;?<?php ?>>;
    var showCount = <<?php ?>?php echo $showCount?<?php ?>>;
    multi(pagecount,pagesize,currentpage,showCount,"adminUserSearch");
    $("#showPage ").val(<<?php ?>?php echo $showCount;?<?php ?>>); //用于显示select的选中事件
});


function isDelete(id){
    if(confirm("确认删除吗？")){

    }else{
        return false;
    }
};
</script>

</HEAD>
<body>
<!--页面名称-->
<h3>管理员用户管理<a href="tpl/admin/addUserByAdmin.html">新增用户>></a></h3>
<!--列表开始-->

<table class="table table-bordered">
    <thead><tr>
        <th>序号</th>
        <th>用户名</th>
        <th>注册时间</th>
        <th>操作</th>
    </tr></thead>

    <?php if ($_smarty_tpl->tpl_vars['class_list']->value) {?>
    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['class_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
        <?php if ($_smarty_tpl->tpl_vars['value']->value['username']!="admin") {?>
            <tbody>
                <tr>
                    <td><?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['value']->value['username'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['value']->value['loginTime'];?>
</td>
                    <!--<td><a href="javascript:void(0);" onclick="isDelete(<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
)">删除</a></td>-->
                    <td><a href="javascript:void(0);" onclick = "isDelete(<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
)">删除</a></td>
                <tr>
            <tbody>
        <?php }?>
    <?php } ?>
    <?php } else { ?>
        <tr><td colspan=3>无记录</td></tr>
    <?php }?>
</table>
<ul class="pagination" id="pagination"></ul>


</body>
</html>
<?php }} ?>
