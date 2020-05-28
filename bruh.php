<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
$price = 0;
$q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*}"));
while ($row = mysqli_fetch_assoc($q)){
    $id = $row['id'];
    $get_price = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*} WHERE id_item='$id' LIMIT 1"));
    $price += (mysqli_fetch_assoc($get_price)['priceVAT']*$row['quantity']);

}
$smarty->assign("total", $price);
$smarty->display('bruh.tpl');

?>
