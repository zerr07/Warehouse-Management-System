<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/HourLogger/HourLoggerController.php';

$hour_session = json_decode(get_active_session(), true);
if (!is_null($hour_session)){
    if (!array_key_exists('error', $hour_session)){
        $smarty->assign("HourLoggerSession", $hour_session);
    } else {
        exit($hour_session['error']);
    }
}

$smarty->display('cp/WMS/HourLogger.tpl');

?>