<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_platforms.php';

function updateStatistics(): array
{
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
    SELECT
       pl.id,
       COUNT(*) as count,
       pl.name,
       (SELECT count(*) FROM XML_export_error WHERE XML_export_error.id_platform=product_platforms.id_platform) as errors
            FROM product_platforms
            LEFT JOIN platforms pl on pl.id = product_platforms.id_platform
            WHERE export='1' GROUP BY product_platforms.id_platform
    "));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row;
    }
    return $arr;
}
function getOutOfStock($platforms): array
{
    $arr = array();
    foreach ($platforms as $k => $v) {
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
        SELECT id_item as id,
            (SELECT SUM(quantity) FROM product_locations WHERE product_locations.id_item=product_platforms.id_item) as qty
            FROM product_platforms
            WHERE export='1' AND id_platform='$k'
        "));
        while ($row = $q->fetch_assoc()){
            $arr[$k][$row['id']] = $row['qty'];
        }
        $arr[$k] = array_filter($arr[$k], function ($v, $k) {
            return $v < 1 || is_null($v);
        }, ARRAY_FILTER_USE_BOTH);
        $size = sizeof($arr[$k]);
        unset($arr[$k]);
        $arr[$k]['outOfStock'] = $size;
    }
    return $arr;
}
function getOutOfStockFromPlatform($platform): array
{
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
        SELECT id_item as id, pn.name,
            (SELECT SUM(quantity) FROM product_locations WHERE product_locations.id_item=product_platforms.id_item) as qty
            FROM product_platforms
            LEFT JOIN product_name pn on pn.id_product = id_item AND pn.id_lang=2
            WHERE export='1' AND id_platform='$platform'
            ORDER BY id_item ASC
    "));
    while ($row = $q->fetch_assoc()){
        array_push($arr, $row);
    }
    $arr = array_filter($arr, function ($v, $k) {
        return $v['qty'] < 1 || is_null($v['qty']);
    }, ARRAY_FILTER_USE_BOTH);
    return $arr;
}
if (isset($_GET['platform']) && isset($_GET['action']) && $_GET['action']=='outOfStock'){
    $smarty->assign("products", getOutOfStockFromPlatform($_GET['platform']));
    $smarty->assign("platform", $_GET['platformName']);
    $smarty->display('cp/statistics/exportStatisticsOutOfStock.tpl');
} else {
    $smarty->assign("statistics", array_replace_recursive(updateStatistics(), getOutOfStock(get_platforms())));
    $smarty->display('cp/statistics/exportStatistics.tpl');
}
