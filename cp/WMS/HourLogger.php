<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
$id = $_COOKIE['user_id'];
if (!array_key_exists('user_id', $_COOKIE)){
    exit("No user id found. Contact administrator");
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*hour_logger*} WHERE date_check_out IS NULL AND user_id='$id'"));
if ($q){
    if ($q->num_rows > 1){
        exit("There are more that one active session for this user. Contact administrator immediately!");
    } else {
        $smarty->assign("HourLoggerSession", $q->fetch_assoc());
    }
} else {
    exit("SQL error");
}
$smarty->display('cp/WMS/HourLogger.tpl');

?>