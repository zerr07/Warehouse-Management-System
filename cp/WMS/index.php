<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/pagination.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');

if (isset($_GET['page'])) {
    $pages = get_product_pages($_GET['page']);
    $arr = array_filter(get_product_range($_GET['page']-1, "Normal", $_COOKIE['id_shard'], false));
    $smarty->assign("current_page", $_GET['page']);
} else {
    $pages = get_product_pages(1);
    $arr = array_filter(get_product_range(0, "Normal", $_COOKIE['id_shard'], false));
    $smarty->assign("current_page", 1);
}
$smarty->assign("pageBase" , GETPageLinks("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
$smarty->assign("pages" , $pages);
$smarty->assign('products', $arr);
if (isset($_GET['only'])){
    $smarty->assign('onlyFilter', $_GET['only']);
}
$smarty->assign("platforms", get_platforms());

$smarty->assign('cat_tree', array_filter(get_tree()));
if (isset($_GET['searchName'])) {
    $smarty->assign("searchName", $_GET['searchName']);
}
if (isset($_GET['searchSupplierName'])) {
    $smarty->assign("searchSupplierName", $_GET['searchSupplierName']);
}
if (isset($_GET['quantitySearch'])) {
    $smarty->assign("quantitySearch", $_GET['quantitySearch']);
}
if (isset($_GET['platformSearchOff'])) {
    $smarty->assign("platformSearchOff", $_GET['platformSearchOff']);
}
if (isset($_GET['platformSearchOn'])) {
    $smarty->assign("platformSearchOn", $_GET['platformSearchOn']);
}
if (isset($_GET['cat'])) {
    $smarty->assign("cat_search", $_GET['cat']);
}
$smarty->display('cp/WMS/index.tpl');
?>
