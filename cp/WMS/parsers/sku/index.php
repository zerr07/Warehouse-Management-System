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
$name = 'products_data_'.explode(".", $_GET['platform'])[0];

$collection  = $GLOBALS['PARSERCONN']->products->$name;

$search = "";
$select = ", (SELECT COUNT(export) FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1) as count1";
$search .= "HAVING count1=0";
$searchSelect = "COUNT((SELECT COUNT(export) as count1 FROM {*product_platforms*} 
            WHERE {*products.id*} = {*product_platforms*}.id_item AND export=1 HAVING count1=0)) as count";
$onPage = _ENGINE['onPage'];;
$shard = $_COOKIE['id_shard'];
$start = 0;

$arr = array();
while (true){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id$select FROM {*products*} 
                                                        WHERE id_shard='$shard' $search
                                              ORDER BY id DESC LIMIT $start, $onPage"));
    while ($row = $result->fetch_assoc()){
        $suppliers = get_supplier_data($row['id']);
        foreach ($suppliers as $supp){
            $filter  = array('sku' => $supp['SKU']);
            $cursor = $collection->find($filter)->toArray();
            if (count($cursor) != 0)
                $arr[$row['id']] = get_product($row['id']);
        }
    }
    if (count($arr) >= $onPage)
        break;
    else
        $start += $onPage;

}

$smarty->assign("matches", $arr);
$smarty->display('cp/WMS/parsers/listOfParsedBySku.tpl');