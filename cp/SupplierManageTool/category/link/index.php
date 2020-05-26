<?php

ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"] . '/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');
include ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/checkLogin.php';
include ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/categories/get_categories.php';
$smarty->assign("categories", get_categories());
$smarty->display('cp/SupplierManageTool/category/index.tpl');

