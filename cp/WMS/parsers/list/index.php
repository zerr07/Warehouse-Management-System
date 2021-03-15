<?php

ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');
include_once ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$images_collection = $GLOBALS['PARSERCONN']->images->images_matches1;
$cursor = $images_collection->find([])->toArray();
$arr = array();
foreach ($cursor as $value){
    if (!array_key_exists($value->id_product, $arr)){
        $arr[$value->id_product]['name'] = get_name($value->id_product);
        $arr[$value->id_product]['mainImage'] = get_main_image($value->id_product);
    }
}
$smarty->assign("matches", $arr);
$smarty->display('cp/WMS/parsers/listOfParsedByImages.tpl');