<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';

$arr = array();
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM XML_export_error"));
while ($row = $q->fetch_assoc())
    $arr[$row['id']] = $row;

$smarty->assign('errors', $arr);


$smarty->display('cp/WMS/logs/errorList.tpl');