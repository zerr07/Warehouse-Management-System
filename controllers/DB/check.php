<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function checkRow($table, $where)
{
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*$table*} WHERE $where LIMIT 1"));
    if ($q->num_rows == 0)
        return (bool)0;
    else
        return $q->fetch_assoc()['id'];
}