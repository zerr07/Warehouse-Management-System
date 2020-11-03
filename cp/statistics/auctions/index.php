<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('_DB_DRUN')){
    define("_DB_DRUN", SETTINGS['database_drundel']);
}
$DRUNCONN = new PDO("pgsql:host="._DB_DRUN['dbhost'].";dbname="._DB_DRUN['dbname'], _DB_DRUN['dbuser'], _DB_DRUN['dbpass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

function getAuctionsSummary($start, $end){
    global $DRUNCONN;
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT SUM(auctions.profit::numeric) as profitSum,
                                        SUM(finalprice::numeric) as finalSum,
                                        COUNT(*) as countSum,
                                        AVG(auctions.profit::numeric) as profitAVG,
                                        SUM(lisateenused::numeric) as lisaSum,
                                        SUM(products.buyprice::numeric) as buySUM,
                                        SUM(auctions.profit::numeric)/SUM(products.buyprice::numeric) as roi
                                    FROM auctions, products
                                    WHERE TO_DATE(enddate,'DD.MM.YYYY') <= TO_DATE('$start','MM/DD/YYYY')
                                      AND TO_DATE(enddate,'DD.MM.YYYY') >= TO_DATE('$end','MM/DD/YYYY') 
                                      AND auctions.profit != '' 
                                      AND finalprice != '' 
                                      AND lisateenused != '' 
                                      AND products.buyprice != '' 
                                      AND products.sku = auctions.productsku");
        $arr = array();
        foreach ($q as $row) {
            $arr = $row;
        }
        return $arr;
    }
    return null;
}

function getSKU($start, $end){
    global $DRUNCONN;
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT productsku
                                        FROM auctions
                                        WHERE TO_DATE(enddate,'DD.MM.YYYY') <= TO_DATE('$start','MM/DD/YYYY')
                                      AND TO_DATE(enddate,'DD.MM.YYYY') >= TO_DATE('$end','MM/DD/YYYY')
                                        GROUP BY productsku
                                        ORDER  BY left(productsku, 2)
                                             , substring(productsku, '\d+')::int NULLS FIRST
                                             , productsku");
        $arr = array();
        foreach ($q as $row) {
            array_push($arr, $row['productsku']);
        }
        return $arr;
    }
    return null;
}
if (isset($_GET['between'])){
    $dates = explode(" - ", $_GET['between']);
    $smarty->assign("date1", $dates[0]);
    $smarty->assign("date2", $dates[1]);
    $smarty->assign("between", $_GET['between']);
    $smarty->assign("AuctionsSKU", getSKU( $dates[1],$dates[0]));
    $smarty->assign("AuctionsSummary", getAuctionsSummary($dates[1],$dates[0]));
} else {
    $smarty->assign("showing", True);
    $smarty->assign("between", "");
    $smarty->assign("AuctionsSKU", getSKU(date("m/d/Y"), date("m/d/Y",strtotime(date("m/d/Y") . "-14 days"))));
    $smarty->assign("AuctionsSummary", getAuctionsSummary(date("m/d/Y"), date("m/d/Y",strtotime(date("m/d/Y") . "-14 days"))));
}
$smarty->display('cp/statistics/auctionsStatistics.tpl');
