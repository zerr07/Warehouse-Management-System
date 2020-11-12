<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/api/checkLogin.php');
$user = getUser();
$pass = getPass();
if ($pass == null || $user == null){
    exit("Username or password is not specified");
}
function insertOutputProduct($tag){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*FB_output*} WHERE tag='$tag'"));
    if ($q->num_rows != 0){
        return json_encode(array("resp"=>"keyExists"));
    } else {
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*FB_output*} (tag) VALUES ('$tag')"));
        return json_encode(array("resp"=>"success"));
    }
}
function getOutputProducts(){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT *, (SELECT id FROM {*products*} WHERE tag={*FB_output*}.tag) as id FROM {*FB_output*}"));
    $arr = array();
    while ($row = $q->fetch_assoc()){

        array_push($arr, array("tag"=>$row['tag'], "quantity"=>get_quantity_sum($row['id'])));
    }
    $arr = array("tags"=>$arr);
    return json_encode($arr);
}

function deleteOutputProduct($tag){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"DELETE FROM {*FB_output*} WHERE tag='$tag'"));
}
if (isset($_GET['insert'])){
    echo insertOutputProduct($_GET['insert']);
}
if (isset($_GET['get'])){
    echo getOutputProducts();
}
if (isset($_GET['delete'])){
    deleteOutputProduct($_GET['delete']);
}