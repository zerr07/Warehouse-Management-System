<?php
define('PRODUCTS_INCLUDED', TRUE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/getLang.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/applyRule.php');

function get_product_range($page, $status, $shard){
    global $search, $select, $searchSelect, $searchSearch;
    $onPage = _ENGINE['onPage'];
    $start = $page*$onPage;
    if ($status == "Search"){
        return /** @lang text */ "SELECT $searchSelect FROM {*products*} WHERE id_shard='$shard' $searchSearch";
    }
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *$select FROM {*products*} 
                                                        WHERE id_shard='$shard' $search
                                                        ORDER BY id DESC LIMIT $start, $onPage"));
    if($result){
        return read_result_multiple($result);
    }
    return null;
}


function get_products($shard){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE id_shard='$shard'"));
    if($result){
        return read_result_multiple($result);
    }
    return null;
}
function get_product($index){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                    WHERE id='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()) {
            return read_result_single($row);
        }
    }
    return null;
}
function get_product_by_ean($index){
    $result_id = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_codes*} 
                                                                                    WHERE ean='$index' LIMIT 1"));
    if($result_id){
        $id = $result_id->fetch_assoc()['id_product'];
        $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                    WHERE id='$id'"));
        while ($row = $result->fetch_assoc()) {
            return read_result_single($row);
        }
    }
    return null;
}

function get_product_by_tag($index){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                    WHERE tag='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()) {
            return read_result_single($row);
        }
    }
    return null;
}
function read_result_multiple($result){
    global $langID, $lang;
    $arr = array(array());
    while ($row = $result->fetch_assoc()){
        $id = $row['id'];
        $arr[$id] = read_result_single($row);

    }
    return $arr;
}
function read_result_single($row){
    $id = $row['id'];
    $arr = $row;
    $arr['name'] = get_name($id);
    $arr = array_merge($arr, get_locations($id));
    $arr['suppliers'] = get_supplier_data($id);
    $arr['platforms'] = get_platform_data($id);
    $arr['descriptions'] = get_desc($id);
    $arr['images'] = get_images($id);
    $arr['mainImage'] = get_main_image($id);
    $arr['carrier'] = get_carrier($id);
    $arr['exportStatus'] = get_export_state($id);
    $arr['category_name'] = get_product_category_name($arr['id_category']);
    return $arr;
}

function get_locations($index){
    $arr = array();
    $arr['locationList'] = array();
    $arr['locations'] = "";
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_locations*}
        WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($query)) {

        array_push($arr['locationList'], $row['location']);
        $arr['locations'] .= " " . $row['location'];
    }
    return $arr;
}
function get_product_category_name($index){
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*category_name*}
        WHERE id_category='$index' AND id_lang='3'"));
    while ($row = mysqli_fetch_assoc($query)) {
        return $row['name'];
    }
    return null;
}
function get_supplier_data($index){
    $arr = array();
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*}
        WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($query)) {
        $arr[$row['id']] = $row;
    }
    return $arr;
}
function get_platform_data($index){
    $arr = array();
    $platforms = get_platforms();
    foreach ($platforms as $platform){
        $id = $platform['id'];
        $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, URL, price, custom, export
        FROM {*product_platforms*} WHERE id_item='$index' AND id_platform='$id'"));
        while ($row = mysqli_fetch_assoc($query)) {
            $arr[$id] = $row;
        }
    }
    //$arr[2]['price'] = applyRule($index, 2, 2);
    return $arr;
}

function get_desc($index){
    $arr = array();
    $desc = json_decode( file_get_contents(
        $_SERVER["DOCUMENT_ROOT"].'/translations/products/'.$index.'.json'), true);
    $get_lang = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*}"));
    while ($row = mysqli_fetch_assoc($get_lang)){
        $arr[$row['lang']] = html_entity_decode($desc['product'][$row['lang']]['description']);
    }
    return $arr;
}

function get_images($index){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, image, `primary` 
                                                                FROM {*product_images*} WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id']] = $row;
    }
    return array_filter($arr);
}

function get_main_image($index){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT image
                                FROM {*product_images*} WHERE id_item='$index' AND `primary`=1"));
    if (mysqli_num_rows($q) == 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT image 
                                FROM {*product_images*} WHERE id_item='$index' AND `primary`=0 LIMIT 1"));
    }
    while ($row = mysqli_fetch_assoc($q)){
        return $row['image'];
    }
}

function get_export_state($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT COUNT(*) as count
                                FROM {*platforms*}"));
    $count = $q->fetch_assoc()['count'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT COUNT(export) as count
                                FROM {*product_platforms*} WHERE id_item='$index' AND export='1'"));
    $countExport = $q->fetch_assoc()['count'];

    if ($count == $countExport){
        return "Full";
    } elseif ($countExport == 0){
        return "No";
    } else {
        return "Partly";
    }
}

function get_carrier($index){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
                                FROM {*carriers*}"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id']] = $row;
        $arr[$row['id']]['custom'] = 0;
        $arr[$row['id']]['enabled'] = 0;
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
                                FROM {*carrier_custom*} WHERE id_product='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id_carrier']]['price'] = $row['price'];
        $arr[$row['id_carrier']]['custom'] = 1;
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
                                FROM {*carrier_details*} WHERE id_product='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id_carrier']]['enabled'] = $row['enabled'];
    }
    return $arr;
}
function get_quantity($index){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT quantity FROM {*products*}
                                                                                    WHERE id='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()) {
            return $row['quantity'];
        }
    }
    return null;
}

function get_name($index){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
                                FROM {*product_name*} WHERE id_product='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
        if ($row['id_lang'] == 3){
            $lang = 'et';
        } else {
            $lang = 'ru';
        }
        $arr[$lang] = html_entity_decode($row['name'], ENT_QUOTES, "UTF-8");;
    }
    return $arr;
}

function get_tag($index){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT tag FROM {*products*} 
                                                                                    WHERE id='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()) {
            return $row['tag'];
        }
    }
    return null;
}

$search = "";
$select = "";
$searchSelect ="COUNT(*) as count";
$searchSearch ="";
if (isset($_GET['searchTagID'])) {
    if ($_GET['searchTagID'] != "") {
        $tagID = $_GET['searchTagID'];
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} WHERE tag='$tagID'"));
        if(mysqli_num_rows($q) == 0){
            $getEAN = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_product FROM {*product_codes*} WHERE ean='$tagID'"));
            while ($row = mysqli_fetch_assoc($getEAN)) {
                $id = $row['id_product'];
                header("Location: /cp/WMS/view/?view=$id");
            }
        } else {
            while ($row = mysqli_fetch_assoc($q)) {
                $id = $row['id'];
                header("Location: /cp/WMS/view/?view=$id");
            }
        }


        //header("Location: /cp/");
    }
}
if (isset($_GET['searchName'])){
    $search = "AND id IN (SELECT id_product FROM {*product_name*} WHERE id_lang='3'";
    $searchString = htmlentities($_GET['searchName'], ENT_QUOTES, "UTF-8");
    $searchString = explode(" ", $searchString);
    foreach ($searchString as $str){
        $search .= " AND `name` LIKE '%".$str."%'";
    }
    $search.=")";
    $searchSearch = $search;
}
if (isset($_POST['cat'])){
    $cat = $_POST['cat'];
    $searchSearch = "AND id_category='$cat'";
    $search = "AND id_category='$cat'";

}
if(isset($_GET['only']) && $_GET['only'] == "Full"){
    if ($arr['exportStatus'] != "Full"){
        $select = ", (SELECT COUNT(export) FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1) as count1";
        $search .= "HAVING count1=(SELECT COUNT(*) FROM {*platforms*})";
        $searchSelect = "COUNT((SELECT COUNT(export) as count1 FROM {*product_platforms*} 
            WHERE {*products.id*} = {*product_platforms*}.id_item AND export=1 HAVING count1=(SELECT COUNT(*) FROM {*platforms*}))) as count";
    }
} elseif (isset($_GET['only']) && $_GET['only'] == "Partly"){
    if ($arr['exportStatus'] != "Partly"){
        $select = ", (SELECT COUNT(export) FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1) as count1";
        $search .= "HAVING count1!=0 AND count1 < (SELECT COUNT(*) FROM {*platforms*})";
        $searchSelect = "COUNT((SELECT COUNT(export) as count1 FROM {*product_platforms*} 
            WHERE {*products.id*} = {*product_platforms*}.id_item AND export=1 HAVING count1!=0 AND count1 < (SELECT COUNT(*) FROM {*platforms*}))) as count";
    }
} elseif (isset($_GET['only']) && $_GET['only'] == "No"){
    if ($arr['exportStatus'] != "No"){
        $select = ", (SELECT COUNT(export) FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1) as count1";
        $search .= "HAVING count1=0";
        $searchSelect = "COUNT((SELECT COUNT(export) as count1 FROM {*product_platforms*} 
            WHERE {*products.id*} = {*product_platforms*}.id_item AND export=1 HAVING count1=0)) as count";
    }
}