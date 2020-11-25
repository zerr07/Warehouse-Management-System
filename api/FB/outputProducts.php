<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
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
        $image = get_main_image_live($row['id']);
        if (is_null($image)){
            $image = get_main_image($row['id']);
        }
        array_push($arr, array("tag"=>$row['tag'], "quantity"=>get_quantity_sum($row['id']), "image"=>$image));
    }
    $arr = array("tags"=>$arr);
    return json_encode($arr);
}
function getFinishedAuctions(){
    $q = $GLOBALS['FBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*auction_winners*}"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        array_push($arr, array("id"=>$row['id'], "PhotoID"=>$row['PhotoID'], "CommentID"=>$row['CommentID']));
    }
    $arr = array("auctions"=>$arr);
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
if (isset($_GET['getAuctions'])){
    echo getFinishedAuctions();
}
if (isset($_GET['delete'])){
    deleteOutputProduct($_GET['delete']);
}