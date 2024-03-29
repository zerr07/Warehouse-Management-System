<?php

include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/getLang.php');

function get_tree(){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
        SELECT c.id, c.enabled, c.parent,cn.name, COUNT(pc.id) as `product_count`
        FROM categories c
            LEFT JOIN product_categories pc on c.id = pc.id_category
            LEFT JOIN category_name cn on c.id = cn.id_category AND cn.id_lang='3'
        WHERE c.id NOT IN ('1', '2')
        GROUP BY c.id, c.parent
        ORDER BY c.parent ASC
        "));
    if($q){
        $temp = array();
        while ($row = $q->fetch_assoc()){
            $temp[$row['id']] = $row;
        }
        return tree_process($temp, 2);
    }
    return null;
}
function tree_process($cats, $parent): array
{
    $temp = array_filter($cats, function ($v) use ($parent){
        return $v['parent'] == $parent;
    });
    $arr = array();
    foreach ($temp as $key => $value){
        if ($value['parent'] == $parent) {
            $arr[$key] = array(
                "id" => $key,
                "enabled" => $value['enabled'],
                "name" => $value['name'],
                "parent" => $value['parent'],
                "child" => tree_process($cats, $key),
                "count" => $value['product_count']
            );
        }
    }
    return $arr;
}
function get_categories(){
    $arr = array();
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*categories*}"));
    if($result){
        while ($row = $result->fetch_assoc()){
            $arr[$row['id']] = $row;
            $arr[$row['id']]['name'] = get_category_name($row['id']);
        }
    }
    return $arr;
}

function get_category_name($index){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*category_name*} 
                                                                WHERE id_category='$index' AND id_lang='3'"));
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            return $row['name'];
        }
    }
    return null;
}
function get_category_names($index){
    $arr = array();
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*category_name*} 
                                                                WHERE id_category='$index'"));
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ($row['id_lang'] == 3) {
                $lang = 'et';
            } elseif ($row['id_lang'] == 2) {
                $lang = 'en';
            } elseif ($row['id_lang'] == 1) {
                $lang = 'ru';
            } elseif ($row['id_lang'] == 4) {
                $lang = 'lv';
            } elseif ($row['id_lang'] == 6) {
                $lang = 'lt';
            }
            $arr[$lang] = html_entity_decode($row['name'], ENT_QUOTES, "UTF-8");;
        }
        return $arr;
    }
    return null;
}
function get_category($index){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*categories*} WHERE id='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()){
            $row['name'] = get_category_names($row['id']);
            return $row;
        }

    }
    return null;
}
function get_category_by_name_from_parent($name, $parent){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM category_name WHERE name='$name' 
                            AND id_category in (SELECT id FROM categories WHERE parent='$parent') LIMIT 1"));
    if($result){
        if ($result->num_rows == 0){
            return array("error"=>"notFound");
        } else {
            return $result->fetch_assoc();
        }


    }
    return array("error"=>"SQL error");
}
function getLinked($id, $id_platform){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id_category_platform FROM {*category_platform*} WHERE id_platform='$id_platform' AND id_category='$id' LIMIT 1"));
    return $q->fetch_assoc()['id_category_platform'];
}

function get_minuvalik_categories($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*TreeMinuvalik*} WHERE id_category='$id'"));
    return $q->fetch_assoc();
}

function getEmptyCategoies(){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *, 
    (SELECT `name` FROM {*category_name*} WHERE {*categories*}.id={*category_name*}.id_category AND {*category_name*}.id_lang='3' LIMIT 1) as `name`
     FROM {*categories*} WHERE (SELECT count(*) FROM {*product_categories*} WHERE {*categories*}.id={*product_categories*}.id_category)=0"));
    $arr = array(array());
    if($result){
        while ($row = $result->fetch_assoc()){
            if ($row['id'] != 1){
                $arr[$row['id']] = $row;
            }
        }
    }
    return $arr;
}
function getCategoryFullPath($id, $lang = "en"){
    $arr = array();
    while (true){
        $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*categories*} WHERE id='$id'"));
        if ($result->num_rows == 0){
            break;
        }

        if($result){
            $row = $result->fetch_assoc();
            array_push($arr, get_category_names($row['id'])[$lang]);
            if ($row['parent'] == 1 || $row['parent'] == 0){
                break;
            } else {
                $id = $row['parent'];
            }
        } else {
            break;
        }
    }
    return array_reverse($arr);
}
if (isset($_POST['getByName']) && isset($_POST['getByName_Parent'])){
    exit(json_encode(get_category_by_name_from_parent($_POST['getByName'], $_POST['getByName_Parent'])));
}