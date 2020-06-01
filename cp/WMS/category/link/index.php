<?php

ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/setup.php');
$smarty = new Smarty_startup();
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');
include_once ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/checkLogin.php';
include_once ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/categories/get_categories.php';
$smarty->assign("categories", get_categories());
$smarty->display('cp/WMS/category/index.tpl');

