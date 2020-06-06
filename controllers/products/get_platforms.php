<?php

include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

$get_lang = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*}"));
$lang = array();
while ($row = mysqli_fetch_assoc($get_lang)){
    $lang[$row['id']] = $row['lang'];
}
function get_platforms(){
    $arr = array();
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*platforms*}"));
    while ($row = mysqli_fetch_assoc($query)) {
        $arr[$row['id']] = $row;
        $arr[$row['id']]['desc'] = get_platform_desc($row['id']);
    }
    return $arr;
}

function get_platform_desc($id){
    global $lang;
    $arr = array();
    foreach ($lang as $key=>$value){
        $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */
                "SELECT * FROM {*platform_descriptions*} WHERE id_platform='$id' AND id_lang='$key'"));
        if (mysqli_num_rows($query) == 1){
            $arr[$value] = mysqli_fetch_assoc($query)['desc'];
        } else {
            $arr[$value] = "";
        }
    }
    return $arr;
}
function get_platform_name($id){
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT name FROM {*platforms*} WHERE id='$id'"));
    return mysqli_fetch_assoc($query)['name'];
}

function get_platform_desc_decoded($id){
    global $lang;
    $arr = array();
    foreach ($lang as $key=>$value){
        $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */
            "SELECT * FROM {*platform_descriptions*} WHERE id_platform='$id' AND id_lang='$key'"));
        if (mysqli_num_rows($query) == 1){
            $arr[$value] = html_entity_decode(mysqli_fetch_assoc($query)['desc']);
        } else {
            $arr[$value] = "";
        }
    }
    return $arr;
}
