<?php

ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');
include_once ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')) {
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$tmp_conf = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/configs/tmp.json'), true);
if (!isset($tmp_conf['parser_start'])) {
    $tmp_conf['parser_start'] = 0;
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/configs/tmp.json', json_encode($tmp_conf));
}
$smarty->assign("platform_sku_start", $tmp_conf['parser_start']);

$smarty->assign("platform_sku", explode(".", $_GET['platform'])[0]);
$smarty->display('cp/WMS/parsers/listOfParsedBySku.tpl');