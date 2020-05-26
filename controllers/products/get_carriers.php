<?php
function get_carrier_default(){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
                                FROM {*carriers*}"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id']] = $row;
        $arr[$row['id']]['custom'] = 0;
    }
    return $arr;
}