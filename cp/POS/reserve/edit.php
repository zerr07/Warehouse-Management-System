<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$arr = getSingleCartReservation($_GET['edit']);
$sum = 0;
foreach ($arr['products'] as $val){
    $sum+= $val['price'];
}
$smarty->assign("sum", $sum);
$smarty->assign("reservation", $arr);
$smarty->display('cp/POS/reserve/edit.tpl');