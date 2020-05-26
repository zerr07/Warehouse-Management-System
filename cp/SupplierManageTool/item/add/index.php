<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_carriers.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');

$smarty->assign('cat_tree', array_filter(get_tree()));
$smarty->assign('platforms', get_platforms());
$smarty->assign('carriers', get_carrier_default());
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT tag FROM {*products*} ORDER BY id DESC LIMIT 1"));
$tag = explode("AZ", $q->fetch_assoc()['tag']);
$tag = $tag[1]+1;
$smarty->assign('inputTag', "AZ".$tag);

$smarty->display('cp/SupplierManageTool/item/add/index.tpl');

?>
