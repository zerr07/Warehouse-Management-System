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
$name = 'products_data_' . explode(".", $_GET['platform'])[0];

$collection = $GLOBALS['PARSERCONN']->products->$name;

$onPage = _ENGINE['onPage'];
$shard = $_COOKIE['id_shard'];
$tmp_conf = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/configs/tmp.json'), true);
if (!isset($tmp_conf['parser_start'])) {
    $tmp_conf['parser_start'] = 0;
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/configs/tmp.json', json_encode($tmp_conf));
}
$start = $tmp_conf['parser_start'];
$c = 0;
$arr = array();
while (true) {
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, 
    (SELECT COUNT(export) FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1) as count1 
    FROM {*products*} 
    WHERE id_shard='$shard' AND 'Parser_SKU_Exclude' NOT IN (SELECT flag FROM {*product_flags*} WHERE id_product=products.id) HAVING count1=0
    ORDER BY id ASC LIMIT $start, $onPage"));
    if ($result->num_rows == 0){
        echo count($arr);
        break;
    }

    while ($row = $result->fetch_assoc()) {
        $suppliers = get_supplier_data($row['id']);
        foreach ($suppliers as $supp) {
            $filter = array('sku' => $supp['SKU']);
            $cursor = $collection->find($filter)->toArray();
            if (count($cursor) != 0) {
                $arr[$row['id']] = get_product($row['id']);
                if (count($arr) == 1) {
                    $tmp_conf['parser_start'] = $start;
                    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/configs/tmp.json', json_encode($tmp_conf));
                }
            }

        }
    }
    if (count($arr) >= $onPage)
        break;
    else
        if (count($arr) == 0) {
            $tmp_conf['parser_start'] = $start;
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/configs/tmp.json', json_encode($tmp_conf));
        }
    $start += $onPage;
}

$smarty->assign("matches", $arr);
$smarty->display('cp/WMS/parsers/listOfParsedBySku.tpl');