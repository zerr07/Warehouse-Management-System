<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');
$smarty->assign('cat_tree', array_filter(get_tree()));
$smarty->assign("item", get_category($_GET['edit']));
$smarty->display('cp/WMS/category/edit/index.tpl');

?>
