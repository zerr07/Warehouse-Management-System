<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);


include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');

$smarty->display('changelog.tpl');

