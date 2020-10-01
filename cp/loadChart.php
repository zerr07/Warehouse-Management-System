<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_products.php';
$p = get_product_by_tag($_GET['tag']);
$smarty->assign("name", $p['name']['et']);
$smarty->assign("tag", $_GET['tag']);
$smarty->display('cp/auctions_charts.tpl');
