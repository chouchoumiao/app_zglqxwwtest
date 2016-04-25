<?php /* Smarty version Smarty-3.1.16, created on 2016-04-25 17:33:10
         compiled from "tpl\admin\adminUserSearch.html" */ ?>
<?php /*%%SmartyHeaderCode:21403571dd127836690-46510083%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0348e0c0676c45f522ee918e448c05a62e427e76' => 
    array (
      0 => 'tpl\\admin\\adminUserSearch.html',
      1 => 1461576756,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21403571dd127836690-46510083',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_571dd1278b36b6_67354966',
  'variables' => 
  array (
    'retArr' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_571dd1278b36b6_67354966')) {function content_571dd1278b36b6_67354966($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

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

    <?php if ($_smarty_tpl->tpl_vars['retArr']->value['class_list']) {?>
    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['retArr']->value['class_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./Static/JS/multi.js?v=20160115"></script>
<script>
 $(document).ready(function() {
    var pagecount = <?php echo $_smarty_tpl->tpl_vars['retArr']->value['count'];?>
;
    var pagesize = <?php echo $_smarty_tpl->tpl_vars['retArr']->value['page_num'];?>
;
    var currentpage =  <?php echo $_smarty_tpl->tpl_vars['retArr']->value['page'];?>
;
    var showCount =  <?php echo $_smarty_tpl->tpl_vars['retArr']->value['showCount'];?>
;
    multi(pagecount,pagesize,currentpage,showCount,"showUserInfo");
    $("#showPage ").val(<?php echo $_smarty_tpl->tpl_vars['retArr']->value['showCount'];?>
); //用于显示select的选中事件
});

function isDelete(id){
    if(confirm("确认删除吗？")){
        $.ajax({
            url:'./admin.php?controller=admin&method=delUserInfoByID'
            ,type:"POST"
            ,data:{
                    "id":id
                }
            ,dataType: "json"
            ,success:function(json){
                alert(json.msg);
                location.reload();
            }
            ,error:function(xhr){
                    alert('PHP页面有错误！'+xhr.responseText);
                }
        });
    }else{
        return false;
    }
};
</script>
</body>
</html><?php }} ?>
