<?php
$config = array(
	'viewconfig' => array(
		'left_delimiter' => '{',
		'right_delimiter' => '}',
		'template_dir' => 'tpl',
		'compile_dir' => 'data/template_c',
		'caching' => false   //È¥³ý»º´æ
	),
	'dbconfig' => array(
		'dbhost' => 'localhost',
		'dbuser'=>'root',
		'dbpsw' => 'root' ,
		'dbname' => 'app_zglqxwwtest',
		'dbcharset' => 'utf8')
);
define("ROOTURL",'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')+1));
