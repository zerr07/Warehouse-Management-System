<?php
/**
 * Created by PhpStorm.
 * User: AZdev
 * Date: 18.01.2019
 * Time: 13:24
 */
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/setup.php');
if (isset($_POST['submit'])){
    $start = $_POST['start'];
    $end = $_POST['end'];
    $percent = $_POST['percent'];
    $allow = 1;
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_price_rules*}"));

    while ($row = mysqli_fetch_assoc($q)){
        if ($start >= $row['startPrice'] && $start <= $row['endPrice']){
            $allow = 0;
        }
        if ($end >= $row['startPrice'] && $end <= $row['endPrice']) {
            $allow = 0;
        }
    }
    if ($allow == 1) {
        if ($_POST['margin'] != ""){
            $margin = $_POST['margin'];
        } else {
            $margin = null;
        }
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_price_rules*}
                    (startPrice, endPrice, percent, minMargin) VALUES ('$start', '$end', '$percent', '$margin')"));
    }
    header("Location: /cp/WMS/priceRule/");
}
if (isset($_GET['delete'])){
    $id = $_GET['delete'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_price_rules*} 
                                                                                            WHERE id='$id'"));
    header("Location: /cp/WMS/priceRule/");
}
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');
include ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/checkLogin.php';
include ($_SERVER["DOCUMENT_ROOT"]) . '/controllers/SMT/getRules.php';
$smarty->display('cp/WMS/priceRules/index.tpl');


