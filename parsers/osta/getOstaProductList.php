<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_platforms.php');
$platform_desc = get_platform_desc_decoded(6);

function getOstaCategoryTree($id){
    $arr = array(array());
    $c = 0;
    $result=$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeOsta*} WHERE id_category_platform='$id'"));

    while ($row = mysqli_fetch_assoc($result)){
        $arr[$c] = $row;
        if ($row['id_category_platform_parent'] == 1000){
            return $arr;
        } else {
            $c++;
            $arr[$c] = getCategoryTree($row['id_category_platform_parent']);

        }
    }
    return $arr;
}

function getCategoryTree($id){
    $arr = array(array());
    $c = 0;
    $result=$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*TreeOsta*} WHERE node_id='$id'"));
    while ($row = mysqli_fetch_assoc($result)){
        $arr[$c] = $row;
        if ($row['id_category_platform_parent'] == 1000){
            return $arr;
        } else {
            $c++;
            $arr[$c] = getCategoryTree($row['id_category_platform_parent']);

        }
    }
    return $arr;
}

function tag_process($str, $tag){
    $string = str_replace("&lt;-TAG-&gt;",$tag , $str);
    $string = str_replace("<-TAG->",$tag , $string);
    return $string;
}

$result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *, (SELECT id_category FROM {*product_categories*} WHERE id_product={*products*}.id LIMIT 1) as id_category1 FROM {*products*} WHERE
id=(SELECT id_item FROM {*product_platforms*} WHERE id_platform='6' AND {*products*}.id=id_item AND export=1) AND
id_category1=(SELECT id_category FROM {*category_platform*} WHERE id_platform='6' AND id_category1=id_category LIMIT 1)
AND quantity>=1"));
$arr = read_result_multiple($result);
$arr = array_filter($arr);

foreach ($arr as $key => $value){
    unset($arr[$key]["locationList"]);
    unset($arr[$key]["locations"]);
    unset($arr[$key]["suppliers"]);
    $cat = $value['id_category'];
    $tempRU = tag_process($platform_desc['ru'], $arr[$key]['tag']);
    $tempET = tag_process($platform_desc['et'], $arr[$key]['tag']);
    $arr[$key]['descriptions']['ru'] = $arr[$key]['descriptions']['ru'].$tempRU;
    $arr[$key]['descriptions']['et'] = $arr[$key]['descriptions']['et'].$tempET;
    unset($arr[$key]['descriptions']['en']);
    unset($arr[$key]['descriptions']['lv']);
    unset($arr[$key]['descriptions']['pl']);
    foreach ($arr[$key]["platforms"] as $pl_key => $pl_value){
        if ($pl_key != 6){
            unset($arr[$key]["platforms"][$pl_key]);
        }
    }
    if (!is_null($cat)){
        $link = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_category_platform FROM
                                        {*category_platform*} WHERE id_category='$cat' AND id_platform='6' LIMIT 1"));
        $arr[$key]['link'] = mysqli_fetch_assoc($link)['id_category_platform'];
        $tree = array_filter(getOstaCategoryTree($arr[$key]['link']));
        if (isset($tree[1])) {
            $arr[$key]['cat_tree'] = array_reverse($tree[1], false);
            array_push($arr[$key]['cat_tree'], $tree[0]);
        } else {
            $arr[$key]['cat_tree'] = $tree[0];
        }
    }
}
function shuffle_assoc($list) {
    if (!is_array($list)) return $list;

    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) {
        $random[$key] = $list[$key];
    }
    return $random;
}
$arr = shuffle_assoc($arr);
$my_file = 'ProductList.json';
unlink($my_file);
$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); //implicitly creates file
fwrite($handle, json_encode(array_filter($arr)));
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($my_file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($my_file));
flush(); // Flush system output buffer
readfile($my_file);
exit;


