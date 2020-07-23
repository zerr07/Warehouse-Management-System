<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
function get_location_types(){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*location_type*}"));
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = $row;
        }
        return array_filter($arr);
    }
    return null;
}
function get_location_type_name($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT `name` FROM {*location_type*} WHERE
    id='$index'"));
    if ($q) {
        return $q->fetch_assoc()['name'];
    }
    return null;
}

function get_single_location_with_type($id_type, $locationList){
    foreach ($locationList as $key => $value){
        if ($value['id_type'] == $id_type){
            return $key;
        }
    }
    return array_key_first($locationList);
}