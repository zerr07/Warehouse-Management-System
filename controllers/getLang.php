<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (isset($_COOKIE['lang'])){
    $lang = $_COOKIE['lang'];
    $get_lang = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*} WHERE lang='$lang'"));
    $langID = $get_lang->fetch_assoc()['id'];
}
