<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);


include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');

if (isset($_COOKIE['Authenticated']) && $_COOKIE['Authenticated'] != ""){
    header("Location: /cp");
}
$smarty->display('index.tpl');

?>
