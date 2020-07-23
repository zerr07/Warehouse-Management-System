<?php

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$price = 0;
$q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*}"));
while ($row = mysqli_fetch_assoc($q)){
    $id = $row['id'];
    $get_price = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*} WHERE id_item='$id' LIMIT 1"));
    if (is_numeric(get_quantity_sum($id))){
        $price += (mysqli_fetch_assoc($get_price)['priceVAT']*get_quantity_sum($id));
    }
}
$smarty->assign("total", $price);
$smarty->display('bruh.tpl');

?>
