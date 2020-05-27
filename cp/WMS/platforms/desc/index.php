<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');


$smarty->assign('platforms', get_platform_desc($_GET['edit']));
$smarty->assign('platform_name', get_platform_name($_GET['edit']));
$smarty->assign('platform_id', $_GET['edit']);
$smarty->display('cp/WMS/platforms/desc/index.tpl');

?>
