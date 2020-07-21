<?php
/**
 * Created by PhpStorm.
 * User: AZdev
 * Date: 18.01.2019
 * Time: 13:24
 */
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$smarty->assign('platforms', get_platforms());
$smarty->assign("item", get_product($_GET['view']));
if (isset($_GET['searchName'])) {
    $smarty->assign("searchName", $_GET['searchName']);
}
$smarty->display('cp/WMS/view/index.tpl');