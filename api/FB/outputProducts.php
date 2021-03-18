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
function insertOutputProduct($tag, $id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*FB_output*} WHERE tag='$tag'"));
    /*if ($q->num_rows != 0){
        return json_encode(array("resp"=>"keyExists"));
    } else {*/
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*FB_output*} (tag, id_list) VALUES ('$tag', '$id')"));
        return json_encode(array("resp"=>"success"));
    //}
}
function getOutputProducts($id, $mode){
    if ($mode == "onlyPos"){
         $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT *, (SELECT id FROM {*products*} WHERE tag={*FB_output*}.tag) as idProd
     FROM {*FB_output*} WHERE id_list='$id' AND (SELECT SUM(quantity) FROM product_locations WHERE id_item=(SELECT id FROM products WHERE tag=FB_output.tag))>0"));
    } else {
         $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT *, (SELECT id FROM {*products*} WHERE tag={*FB_output*}.tag) as idProd
     FROM {*FB_output*} WHERE id_list='$id'"));
    }
   
    $arr = array();
    while ($row = $q->fetch_assoc()){
        $image = get_main_image_live($row['idProd']);
        if (is_null($image)){
            $image = get_main_image($row['idProd']);
        }
        $images = get_images_live($row['idProd']);
        array_push($arr, array("idInList"=>$row['id'] , "id"=>$row['idProd'] ,"tag"=>$row['tag'], "quantity"=>get_quantity_sum($row['idProd']), "image"=>$image,"images"=>$images, "desc"=>get_FB_desc($row['idProd'])));
    }
    usort($arr, function($a, $b) {
        return $b['idInList'] <=> $a['idInList'];
    });
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
if (isset($_GET['insert']) && isset($_GET['id'])){
    echo insertOutputProduct($_GET['insert'], $_GET['id']);
}
if (isset($_GET['get'])){
    echo getOutputProducts($_GET['get'], "default");
}
if (isset($_GET['getOnlyPos'])){
    echo getOutputProducts($_GET['getOnlyPos'], "onlyPos");
}
if (isset($_GET['getAuctions'])){
    echo getFinishedAuctions();
}
if (isset($_GET['delete'])){
    deleteOutputProduct($_GET['delete']);
}