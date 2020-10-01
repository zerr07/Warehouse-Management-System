<?php
header('Content-Type: text/plain');
if (!defined('SETTINGS')){
    define("SETTINGS", json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/configs/config.json'), true));
}
if (!defined('_DB_DRUN')){
    define("_DB_DRUN", SETTINGS['database_drundel']);
}
$DRUNCONN = new PDO("pgsql:host="._DB_DRUN['dbhost'].";dbname="._DB_DRUN['dbname'], _DB_DRUN['dbuser'], _DB_DRUN['dbpass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
if(isset($_GET['tag'])){
    $tag = $_GET['tag'];
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT id, TO_DATE(enddate,'DD.MM.YYYY') as enddate, startdate, profit, finalprice, lisateenused,
            (SELECT buyprice FROM products WHERE sku=auctions.productsku) as buyprice FROM auctions 
            WHERE productsku='$tag' AND status != 'NotFinished' ORDER BY enddate ASC");
        $arr = array(array());
        foreach ($q as $row){
            $arr[$row['id']] = $row;
        }
    }

    echo json_encode(array_filter($arr));
}
if(isset($_GET['tagSUM'])){
    $tag = $_GET['tagSUM'];
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT TO_DATE(enddate,'DD.MM.YYYY') as enddate, SUM(profit::numeric)
            FROM auctions WHERE productsku='$tag' AND status != 'NotFinished' GROUP BY enddate ORDER BY enddate ASC;");
        $arr = array(array());
        foreach ($q as $row){
            $arr[$row['enddate']] = $row;
        }
    }

    echo json_encode(array_filter($arr));
}
if(isset($_GET['tagAVG'])){
    $tag = $_GET['tagAVG'];
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT TO_DATE(enddate,'DD.MM.YYYY') as enddate,
       (SELECT AVG(profit::numeric) FROM auctions WHERE productsku='$tag' AND TO_DATE(enddate,'DD.MM.YYYY')<= TO_DATE(a1.enddate,'DD.MM.YYYY') AND status != 'NotFinished')
            FROM auctions as a1 WHERE productsku='$tag' AND status != 'NotFinished' GROUP BY enddate ORDER BY enddate ASC;
");
        $arr = array(array());
        foreach ($q as $row){
            $arr[$row['enddate']] = $row;
        }
    }
    echo json_encode(array_filter($arr));
}
if(isset($_GET['tagAVG7'])){
    $tag = $_GET['tagAVG7'];
    if (isset($DRUNCONN)) {
        $q = $DRUNCONN->query("SELECT TO_DATE(enddate,'DD.MM.YYYY') as enddate,
                    (SELECT AVG(profit::numeric) FROM auctions WHERE productsku='$tag' 
                        AND TO_DATE(enddate,'DD.MM.YYYY')<= TO_DATE(a1.enddate,'DD.MM.YYYY') 
                        AND TO_DATE(enddate,'DD.MM.YYYY')> TO_DATE(a1.enddate,'DD.MM.YYYY') - INTERVAL '7 DAYS'
                        AND status != 'NotFinished')
                    FROM auctions as a1 WHERE productsku='$tag' 
                        AND status != 'NotFinished' 
                        GROUP BY enddate 
                        ORDER BY enddate DESC 
                        LIMIT 7");
                    $arr = array(array());
        foreach ($q as $row){
            $arr[$row['enddate']] = $row;
        }
    }
    echo json_encode(array_reverse(array_filter($arr)));
}