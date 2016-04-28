<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

    header("Content-type: text/html; charset=utf-8");
    require_once('config.php');
    require_once('framework/pc.php');
    PC::run($config);
