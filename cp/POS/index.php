<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_products.php';

include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (isset($_GET['success'])){
    $smarty->assign('success', 'true');
}
$smarty->display('cp/POS/index.tpl');
