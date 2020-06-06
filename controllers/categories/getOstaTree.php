<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
function get_platform_tree(){
    global $langID;
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeOsta*} WHERE `id_category_platform`='1000'"));
    if($result){
        $temp = array();
        $arr = array();
        while ($row = $result->fetch_assoc()){
            $id = $row['node_id'];
            $temp[$id] = array("name"=>$row['category_platform_name'],"cat_id"=>$row['id_category_platform'], "parent"=>$row['id_category_platform_parent'], "child"=>array());
            $temp[$id]['child'] = get_platform_sub_cat($id);
        }
        return $temp;
    }
    return null;
}
function get_platform_sub_cat($index) {
    global $langID;
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeOsta*} WHERE `id_category_platform_parent`='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()){
            $id = $row['node_id'];
            $temp[$id] = array("name"=>$row['category_platform_name'],"cat_id"=>$row['id_category_platform'], "parent"=>$row['id_category_platform_parent'], "child"=>array());
            $temp[$id]['child'] = get_platform_sub_cat($id);
        }
        return $temp;
    }
    return null;
}
function get_platform_categories(){
    $arr = array();
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeOsta*}"));
    if($result){
        while ($row = $result->fetch_assoc()){
            $arr[$row['id']] = $row;
        }
    }
    return $arr;
}

