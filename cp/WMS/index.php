<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once ($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_products.php';
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/pagination.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');

if (isset($_GET['page'])) {
    $pages = get_product_pages($_GET['page']);
    $arr = array_filter(get_product_range($_GET['page']-1, "Normal", $_COOKIE['id_shard']));
    $smarty->assign("current_page", $_GET['page']);
} else {
    $pages = get_product_pages(1);
    $arr = array_filter(get_product_range(0, "Normal", $_COOKIE['id_shard']));
    $smarty->assign("current_page", 1);
}
$smarty->assign("pageBase" , GETPageLinks("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
$smarty->assign("pages" , $pages);
$smarty->assign('products', $arr);
$smarty->assign('onlyFilter', $_GET['only']);

$smarty->assign('cat_tree', array_filter(get_tree()));
if (isset($_GET['searchName'])) {
    $smarty->assign("searchName", $_GET['searchName']);
}
$smarty->display('cp/WMS/index.tpl');

?>
