<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');
include_once ($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_products.php';

$smarty->assign('cat_tree', array_filter(get_tree()));
$smarty->assign('platforms', get_platforms());
$smarty->assign("item", get_product($_GET['edit']));

$smarty->display('cp/SupplierManageTool/item/edit/index.tpl');

?>
