<?php
header('Content-Type: text/plain');
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

if (!defined('_DB_DRUN')){
    define("_DB_DRUN", SETTINGS['database_drundel']);
}
$DRUNCONN = new PDO("pgsql:host="._DB_DRUN['dbhost'].";dbname="._DB_DRUN['dbname'], _DB_DRUN['dbuser'], _DB_DRUN['dbpass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

function getSKU_summary($sku, $between){
    global $DRUNCONN;
    if ($between == ""){
        $end = date("m/d/Y");
        $start = date("m/d/Y",strtotime(date("m/d/Y") . "-14 days"));
    } else {
        $dates = explode(" - ", $between);
        $start =$dates[0];
        $end = $dates[1];
    }
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT SUM(auctions.profit::numeric) as profitSum,
                                        SUM(finalprice::numeric) as finalSum,
                                        COUNT(*) as countSum,
                                        AVG(auctions.profit::numeric) as profitAVG,
                                        SUM(lisateenused::numeric) as lisaSum,
                                        SUM(products.buyprice::numeric) as buySUM,
                                        SUM(auctions.profit::numeric)/SUM(products.buyprice::numeric) as roi
                                    FROM auctions, products
                                    WHERE TO_DATE(enddate,'DD.MM.YYYY') <= TO_DATE('$end','MM/DD/YYYY')
                                      AND TO_DATE(enddate,'DD.MM.YYYY') >= TO_DATE('$start','MM/DD/YYYY')
                                      AND auctions.profit != '' 
                                      AND finalprice != '' 
                                      AND lisateenused != '' 
                                      AND products.buyprice != '' 
                                      AND auctions.productsku = '$sku'
                                      AND products.sku = auctions.productsku");
        $arr = array();
        foreach ($q as $row) {
            $arr = $row;
        }
        return $arr;
    }
    return null;
}

if(isset($_GET['tag']) && isset($_GET['between'])){
    echo json_encode(array_filter(getSKU_summary($_GET['tag'], $_GET['between'])));
}


