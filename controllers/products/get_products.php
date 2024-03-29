<?php
define('PRODUCTS_INCLUDED', TRUE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/getLang.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/applyRule.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/properties.php');

include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_location_types.php');
function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    if (sizeof($arr) !== 0){
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

}

function get_product_range($page, $status, $shard, $full=true){
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
        return read_result_multiple($result, $full);
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


function get_products_tags_only($shard): ?array{
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, tag FROM {*products*} WHERE id_shard='$shard'"));
    $arr = array();
    if($result){
        while($row = $result->fetch_assoc()){
            $arr[$row['id']] = $row['tag'];
        }
        return $arr;
    }
    return null;
}

function get_products_from_array($shard, $id_array){
    $str = "";
    $i = 0;
    foreach ($id_array as $a){
        if ($i == 0){
            $str = "id='" . $a . "'";
        } else {
            $str .= " OR id='" . $a . "'";
        }
        $i++;
    }
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE id_shard='$shard' AND $str"));
    if($result){
        return array_filter(read_result_multiple($result));
    }
    return null;
}
function generateDatalistName($tag, $name): string {
    return $tag. " | ".html_entity_decode($name, ENT_QUOTES, "UTF-8");
}

function get_products_dataList($shard){
    $arr = array(array());
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, tag, 
        (SELECT `name` FROM {*product_name*} WHERE id_product={*products*}.id AND id_lang='3') as `name`
        FROM {*products*} WHERE id_shard='$shard'"));
    if($result){
        while ($row = $result->fetch_assoc()){
            $id = $row['id'];
            //$sku = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT `SKU` FROM {*supplier_data*} WHERE id_item='$id'"));
            $prod = generateDatalistName($row['tag'], $row['name']);

            /*if ($sku){
                while ($rowSKU = $sku->fetch_assoc()){
                    if (!is_null($rowSKU['SKU'])){
                        $prod .= " | ".html_entity_decode($rowSKU['SKU'], ENT_QUOTES, "UTF-8");
                    }
                }
            }*/
            $arr[$id] = $prod;
        }
        return array_filter($arr);
    }
    return null;
}
function get_product($index, $full=true){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                    WHERE id='$index'"));
    if($result){
        while ($row = $result->fetch_assoc()) {
            return read_result_single($row, $full);
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

function get_product_id_by_tag($index){
    if (is_numeric($index)){
        $index = "AZ".$index;
    }
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} 
                                                                                    WHERE tag='$index'"));
    if($result){
        return $result->fetch_assoc()['id'];
    }
    return null;
}

function read_result_multiple($result, $full=true){
    global $langID, $lang;
    $arr = array(array());
    while ($row = $result->fetch_assoc()){
        $id = $row['id'];
        $arr[$id] = read_result_single($row, $full);

    }
    return $arr;
}
function read_result_single($row, $full=true){
    $id = $row['id'];
    $arr = $row;
    $arr['name'] = get_name($id);
    $arr['quantity'] = get_quantity_sum($id);
    $arr['home_qty'] = get_quantity_sum_home($id);
    $arr['supp_qty'] = get_quantity_sum_supp($id);
    $arr = array_merge($arr, get_locations($id));
    $arr['platforms'] = get_platform_data($id);
    $arr['categories'] = get_product_categories($id);
    $arr['main_category'] = get_main_category($id);
    $arr['main_category_export'] = get_main_category_export($id);
    if ($full){
        $arr['suppliers'] = get_supplier_data($id);
        $arr['reservations'] = get_reserve_info($id);
        $arr['descriptions'] = get_desc($id);
        $arr['FB_description'] = get_FB_desc($id);
        $arr['images_live'] = get_images_live($id);
        $arr['mainImage_live'] = get_main_image_live($id);
        $arr['carrier'] = get_carrier($id);
        $arr['category_name'] = get_product_category_name($arr['categories']);
        $arr['properties'] = get_product_properties($id, 2);
    }
    $arr['images'] = get_images($id);
    $arr['mainImage'] = get_main_image($id);
    $arr['exportStatus'] = get_export_state($id);
    //$arr['attributes'] = get_attributes($id);
    $arr['manufacturer'] = get_manufacturer_name($arr['id_manufacturer']);
    return $arr;
}
function get_manufacturer_name($index){
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*manufacturer*}
        WHERE id='$index'"));
    if ($query){
        while ($row = mysqli_fetch_assoc($query)) {
            return $row['name'];
        }
    }

    return null;
}
function get_quantity_sum($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT SUM(quantity) as q FROM {*product_locations*} WHERE id_item='$index'"));
    if ($q){
        $res = $q->fetch_assoc()['q'];
        if ($res != ""){
            return $res;
        } else {
            return 0;
        }
    }
    return null;
}

function get_quantity_sum_home($index){
    if (!empty(_ENGINE['home_warehouse'])){
        $append = array();
        foreach (_ENGINE['home_warehouse'] as $id){
            array_push($append, "id_type='$id'");
        }
        $append = " AND (".implode(" OR ", $append).")";
    } else {
        $append = "";
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT SUM(quantity) as q FROM {*product_locations*} WHERE id_item='$index'$append"));
    if ($q){
        return $q->fetch_assoc()['q'];
    }
    return null;
}

function get_quantity_sum_supp($index){
    if (!empty(_ENGINE['home_warehouse'])){
        $append = array();
        foreach (_ENGINE['home_warehouse'] as $id){
            array_push($append, "NOT id_type='$id'");
        }
        $append = " AND (".implode(" AND ", $append).")";
    } else {
        $append = "";
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT SUM(quantity) as q FROM {*product_locations*} WHERE id_item='$index'$append"));
    if ($q){
        return $q->fetch_assoc()['q'];
    }
    return null;
}

function get_product_categories($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_category FROM {*product_categories*} WHERE id_product='$index'"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        array_push($arr, $row['id_category']);
    }
    return $arr;
}
function get_main_category($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_category FROM {*product_categories*} WHERE id_product='$index' AND `main`='1'"));
    if ($q->num_rows === 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_category FROM {*product_categories*} WHERE id_product='$index' LIMIT 1"));
    }
    if ($q->num_rows !== 0) {
        while ($row = $q->fetch_assoc()) {
            return $row['id_category'];
        }
    }
    return null;
}
function get_main_category_export($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_category FROM {*product_categories*} WHERE id_product='$index' AND `main`='1' AND (SELECT export FROM {*categories*} WHERE id={*product_categories*}.id_category)='1'"));
    if ($q->num_rows === 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_category FROM {*product_categories*} WHERE id_product='$index' AND (SELECT export FROM {*categories*} WHERE id={*product_categories*}.id_category)='1' LIMIT 1"));
    }
    if ($q->num_rows !== 0) {
        while ($row = $q->fetch_assoc()) {
            return $row['id_category'];
        }
    }
    return null;
}
function get_attributes($index){
    $arr = array(array());
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_attributes*}
        WHERE id_product='$index'"));
    if ($query){
        while ($row = mysqli_fetch_assoc($query)) {
            $arr[$row['id']] = $row['attribute'];
        }
        return $arr;
    }
    return null;
}
function get_locations($index){
    $arr = array();
    $arr['locationList'] = array();
    $arr['locations'] = "";
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_locations*}
        WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($query)) {
        $arr['locationList'][$row['id']] = $row;
        $arr['locationList'][$row['id']]['type_name'] = get_location_type_name($row['id_type']);
        $arr['locations'] .= " " . $row['location'];
    }
    return $arr;
}
function get_product_category_name($index){
    if (is_array($index)){
        $arr = array();
        foreach ($index as $id){
            $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT `name` FROM {*category_name*}
                WHERE id_category='$id' AND id_lang='3'"));
            while ($row = mysqli_fetch_assoc($query)) {
                $arr[$id] = $row['name'];
            }
        }
        return $arr;
    } else {
        $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*category_name*}
            WHERE id_category='$index' AND id_lang='3'"));
        while ($row = mysqli_fetch_assoc($query)) {
            return $row['name'];
        }
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
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, id_platform, URL, price, custom, export
       FROM {*product_platforms*} WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($query)) {
        $arr[$row['id_platform']] = $row;
    }
    //$arr[2]['price'] = applyRule($index, 2, 2);
    return $arr;
}

function get_desc($index){
    $arr = array();
    if (file_exists($_SERVER["DOCUMENT_ROOT"].'/translations/products/'.$index.'.json')){
        $desc = json_decode( file_get_contents(
            $_SERVER["DOCUMENT_ROOT"].'/translations/products/'.$index.'.json'), true);
        $get_lang = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*}"));
        while ($row = mysqli_fetch_assoc($get_lang)){
            if (array_key_exists($row['lang'], $desc['product']))
                $arr[$row['lang']] = html_entity_decode($desc['product'][$row['lang']]['description']);
        }
    }
    return $arr;
}
function get_FB_desc($index){
    $arr = "";
    if (file_exists($_SERVER["DOCUMENT_ROOT"].'/translations/products/'.$index.'.json')){
        $desc = json_decode( file_get_contents(
            $_SERVER["DOCUMENT_ROOT"].'/translations/products/'.$index.'.json'), true);
        if (array_key_exists("FB", $desc['product'])){
            $arr = html_entity_decode($desc['product']["FB"]['description']);
        }
    }
    return $arr;
}
function get_images($index){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, image, `position` 
                                                                FROM {*product_images*} WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id']] = $row;
    }
    $arr = array_filter($arr);
    array_sort_by_column($arr, 'position');
    return $arr;
}
function get_main_image($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT image
                                FROM {*product_images*} WHERE id_item='$index' AND `position`=1"));
    if (mysqli_num_rows($q) == 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT image 
                                FROM {*product_images*} WHERE id_item='$index' AND `position`!=1 LIMIT 1"));
    }
    while ($row = mysqli_fetch_assoc($q)){
        return $row['image'];
    }
    return null;
}
function get_images_live($index){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, image, `position` 
                                                                FROM {*product_images_live*} WHERE id_item='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id']] = $row;
    }
    $arr = array_filter($arr);
    array_sort_by_column($arr, 'position');
    return $arr;
}
function get_main_image_live($index){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT image
                                FROM {*product_images_live*} WHERE id_item='$index' AND `position`=1"));
    if (mysqli_num_rows($q) == 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT image 
                                FROM {*product_images_live*} WHERE id_item='$index' AND `position`!=1 LIMIT 1"));
    }
    while ($row = mysqli_fetch_assoc($q)){
        return $row['image'];
    }
    return null;
}

function get_reserve_info($index){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT {*reserved_products*}.*,
    (SELECT `comment` FROM {*reserved*} WHERE {*reserved*}.id={*reserved_products*}.id_reserved) as `comment`,
    {*shipment_status*}.id_status
    FROM {*reserved_products*}
    LEFT JOIN {*shipment_status*} ON ({*reserved_products*}.id_reserved = {*shipment_status*}.id_shipment)
    WHERE id_product='$index' AND ({*shipment_status*}.id_status IS NULL OR {*shipment_status*}.id_status!=6)"));

    if ($q){
        $arr['reserved_sum'] = 0;
        while ($row = $q->fetch_assoc()){
            $arr['reserved_list'][$row['id']] = $row;
            $arr['reserved_sum'] += $row['quantity'];
        }
        if(!empty($arr['reserved_list'])){
            ksort($arr['reserved_list']);
            $arr['reserved_list'] = array_reverse($arr['reserved_list']); 
        }
        
        return $arr;
    }
    return null;
}



function get_ean_codes($index){
    $arr = array();
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_codes*} WHERE id_product='$index'"));
    while($row = mysqli_fetch_assoc($query)){
        $arr[$row['id']] = $row;
    }
    return $arr;
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
function get_name($index){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
                                FROM {*product_name*} WHERE id_product='$index'"));
    while ($row = mysqli_fetch_assoc($q)){
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
        $arr[$lang] = html_entity_decode($row['name'], ENT_QUOTES, "UTF-8");
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
function get_product_sales($index){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sold_items*} 
        WHERE id_item='$index' LIMIT 50"));
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = $row;
            $i = $row['id_sale'];
            $q1 = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT saleDate FROM {*sales*} 
            WHERE id='$i'"));
            $arr[$row['id']]['saleDate'] = date_format(date_create($q1->fetch_assoc()['saleDate']), "d.m.Y H:i:s");
        }
        return array_filter($arr);
    }
    return null;
}

function get_product_by_supp_sku($sku){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*} WHERE SKU='$sku'"));

    if ($q->num_rows == 0){
        $found = "false";
    } else {
        $found = "true";
    }
    return $found;
}

function isPositiveInt($qty): bool
{
    if (is_numeric($qty) && $qty > 0)
        return true;
    return false;
}

if (isset($_POST['ifProductExistWithSKU'])){
    exit(json_encode(array("found"=>get_product_by_supp_sku($_POST['ifProductExistWithSKU']))));
}
$search = "";
$select = "";
$searchSelect ="COUNT(*) as count";
$searchSearch ="";
$searchPlatform = "";
if (isset($_GET['searchTagID'])) {
    if ($_GET['searchTagID'] != "") {
        $tagID = $_GET['searchTagID'];
        if (is_numeric($tagID)){
            $id_shard = $_COOKIE['id_shard'];
            $q_prefix = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */"SELECT tag_prefix FROM {*shards*} WHERE id='$id_shard'"));
            if ($q_prefix){
                $tempTag = $q_prefix->fetch_assoc()['tag_prefix'].$tagID;
            }
        } else {
            $tempTag = $tagID;
        }
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} WHERE tag='$tempTag'"));
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
if (isset($_GET['getDataList']) && isset($_GET['getDataListStr'])){
    echo json_encode(get_products_dataList($_COOKIE['id_shard'],$_GET['getDataListStr']));
}
if (isset($_GET['getProductsFromArray'])){
    echo json_encode(get_products_dataList($_COOKIE['id_shard']));
}
if (isset($_GET['getSingleProduct'])){
    exit(json_encode(get_product($_GET['getSingleProduct'])));
}
if (isset($_GET['getSingleProductNotFull'])){
    exit(json_encode(get_product($_GET['getSingleProductNotFull'], false)));
}
if (isset($_GET['searchName']) && $_GET['searchName'] != ""){
    $search .= " AND (id IN (SELECT id_product FROM {*product_name*} WHERE (id_lang='3' OR id_lang='1')";
    $searchString = htmlentities($_GET['searchName'], ENT_QUOTES, "UTF-8");
    $searchString = explode(" ", $searchString);
    foreach ($searchString as $str){
        $search .= " AND `name` LIKE '%".$str."%'";
    }
    $search.=")";
    $searchString = implode(" ", $searchString);
    $search .= " OR id IN (SELECT id_item FROM {*supplier_data*} WHERE SKU LIKE '%$searchString%'))";
    $searchSearch = $search;
}
if (isset($_GET['searchSupplierName']) && $_GET['searchSupplierName'] != ""){

    $searchString = htmlentities($_GET['searchSupplierName'], ENT_QUOTES, "UTF-8");
    $search .= "AND id IN (SELECT id_item FROM {*supplier_data*} WHERE supplierName LIKE '%$searchString%'";
    $searchString = explode(" ", $searchString);
    foreach ($searchString as $str){
        $search .= " OR `URL` LIKE '%".$str."%'";
    }
    $search.=")";
    $searchSearch = $search;
}
if (isset($_GET['quantitySearch']) && $_GET['quantitySearch'] == "on"){
    $search .= " AND (SELECT SUM(quantity) FROM {*product_locations*} WHERE id_item={*products*}.id)>0";
    $searchSearch = $search;
}
if (isset($_GET['platformSearchOn']) || isset($_GET['platformSearchOff'])){
    $search .= " AND (";
    if (isset($_GET['platformSearchOn'])){
        $a = array();
        foreach ($_GET['platformSearchOn'] as $key => $value){
            array_push($a, "(SELECT export FROM {*product_platforms*} WHERE id_item={*products*}.id && id_platform='$key')=1");
        }
        $searchPlatform .= implode(" AND ", $a);
    }
    if (isset($_GET['platformSearchOff'])){
        if ($searchPlatform != ""){
            $searchPlatform .= " AND ";
        }
        $a = array();
        foreach ($_GET['platformSearchOff'] as $key => $value){
            array_push($a, "((SELECT export FROM {*product_platforms*} WHERE id_item={*products*}.id && id_platform='$key')=0 OR id not in (SELECT id_item FROM product_platforms WHERE id_item=products.id && id_platform='$key'))");
        }
        $searchPlatform .= implode(" AND ", $a);
    }
    $search .= $searchPlatform;
    $search .= ")";
    $searchSearch = $search;

}



if (isset($_GET['cat'])){
    $cat = $_GET['cat'];
    if ($cat != "None"){
        $searchSearch .= " AND $cat IN (SELECT id_category FROM {*product_categories*} WHERE id_product={*products.id*})";
        $search .= " AND $cat IN (SELECT id_category FROM {*product_categories*} WHERE id_product={*products.id*})";
    }


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

if (isset($_GET['getTagsOnly'])){
    $tags = get_products_tags_only(1);
    $arr = array();
    foreach ($tags as $key => $value){
        $arr[$key]['tag'] = $value;
        $arr[$key]['images'] = get_images($key);
    }
    exit(json_encode($arr));
}