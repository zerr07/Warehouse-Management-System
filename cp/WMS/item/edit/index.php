<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');
include_once ($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_products.php';

$smarty->assign('cat_tree', array_filter(get_tree()));
$smarty->assign('platforms', get_platforms());
$smarty->assign("item", get_product($_GET['edit']));

$smarty->display('cp/WMS/item/edit/index.tpl');

?>
