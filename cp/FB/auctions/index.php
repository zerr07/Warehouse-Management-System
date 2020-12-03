<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$arr = array();
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*FB_lists*}"));
while ($row = $q->fetch_assoc()){
    $arr[$row['id']] = $row['name'];
}
$smarty->assign("FB_list", $arr);

$smarty->display('cp/FB/auctions/control.tpl');

?>
