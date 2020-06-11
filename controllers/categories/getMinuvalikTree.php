<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
function get_platform_tree(){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeMinuvalik*} WHERE `parent`='0'"));
    if($result){
        $temp = array();
        $arr = array();
        while ($row = $result->fetch_assoc()){
            $id = $row['id_category'];
            $temp[$id] = array("name"=>$row['name'],"cat_id"=>$row['id_category'], "parent"=>$row['parent'], "child"=>array());
            $temp[$id]['child'] = get_platform_sub_cat($id);
        }
        return $temp;
    }
    return null;
}
function get_platform_sub_cat($index) {
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeMinuvalik*} WHERE `parent`='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()){
            $id = $row['id_category'];
            $temp[$id] = array("name"=>$row['name'],"cat_id"=>$row['id_category'], "parent"=>$row['parent'], "child"=>array());
            $temp[$id]['child'] = get_platform_sub_cat($id);
        }
        return $temp;
    }
    return null;
}

