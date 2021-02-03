<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

function getUsers(): array{
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, username FROM {*users*}"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row['username'];
    }
    return $arr;
}