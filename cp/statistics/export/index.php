<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';

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
function getOutOfStock(): array
{
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
    SELECT
       pl.id,
       COUNT(*) as count
            FROM product_platforms
            LEFT JOIN platforms pl on pl.id = product_platforms.id_platform
            WHERE export='1'
              AND (SELECT SUM(quantity) FROM product_locations WHERE product_locations.id_item=product_platforms.id_item) < 1
    GROUP BY product_platforms.id_platform
    "));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']]['outOfStock'] = $row['count'];
    }
    return $arr;
}
function getOutOfStockFromPlatform($platform): array
{
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
        SELECT id_item as id, pn.name FROM product_platforms
        LEFT JOIN product_name pn on pn.id_product = id_item AND pn.id_lang=2
        WHERE export='1' AND id_platform='$platform'
            AND (SELECT SUM(quantity) FROM product_locations WHERE product_locations.id_item=product_platforms.id_item) < 1
    "));
    while ($row = $q->fetch_assoc()){
        array_push($arr, $row);
    }
    return $arr;
}
if (isset($_GET['platform']) && isset($_GET['action']) && $_GET['action']=='outOfStock'){
    $smarty->assign("products", getOutOfStockFromPlatform($_GET['platform']));
    $smarty->assign("platform", $_GET['platformName']);
    $smarty->display('cp/statistics/exportStatisticsOutOfStock.tpl');
} else {
    $smarty->assign("statistics", array_replace_recursive(updateStatistics(), getOutOfStock()));
    $smarty->display('cp/statistics/exportStatistics.tpl');
}
