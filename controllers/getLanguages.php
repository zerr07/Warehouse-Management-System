<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
function getLanguagesAsName(): array
{
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*}"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        $arr[$row['lang']] = $row['id'];
    }
    return $arr;
}
function getLanguagesAsId(): array
{
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*}"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row['lang'];
    }
    return $arr;
}