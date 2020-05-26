<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
$smarty = new Smarty_startup();
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/getSales.php';
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/SMT/restoreQuantity.php';
if (isset($_GET['view'])){
    $smarty->display('cp/POS/sales/view.tpl');
} elseif (isset($_GET['tagastusFull'])){
    $id = $_GET['tagastusFull'];
    restore($id);

    header('Location: /cp/POS/sales' );
} else {
    $smarty->display('cp/POS/sales/index.tpl');
}

?>
