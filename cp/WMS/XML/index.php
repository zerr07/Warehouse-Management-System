<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$platforms = get_platforms();
$arr = array();
foreach ($platforms as $k => $v){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*XML_profiles*} WHERE id_platform='$k'"));
    if ($q->num_rows != 0){
        $arr[$k] = $v;
    }
}
$smarty->assign('platforms', $arr);


$smarty->display('cp/WMS/XML/xml_Generator.tpl');