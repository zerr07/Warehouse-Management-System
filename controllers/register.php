<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function registerUser($user, $pass): bool
{
    if (!checkRow("users", "username='$user'"))
        return false;
    $password = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 10]);
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*users*} (username, `password`) VALUES ('$user', '$password')"));
    return true;
}
function registerXmlUser($user, $pass): bool
{
    if (!checkRow("XML_users", "username='$user'"))
        return false;
    $password = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 10]);
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*XML_users*} (username, `password`) VALUES ('$user', '$password')"));
    return true;
}