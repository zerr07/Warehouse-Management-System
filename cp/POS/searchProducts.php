<?php
header('Content-Type: application/json');
include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/update.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_location_types.php');

include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/session.php';
if (isset($_POST['searchTagID']) && $_POST['searchTagID'] != ""){
    addToCartByTag($_POST['searchTagID']);
}
else if (isset($_POST['searchName']) && $_POST['searchName'] != ""){
    $items = array(array());
    searchByName($_POST['searchName']);
    $name = $_POST['searchName'];
}
else if (isset($_GET['addID']) && $_GET['addID'] != ""){
    addToCartByID($_GET['addID']);
}
function searchByName($name){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                        WHERE `name` LIKE '%$name%'"));
    $items = array(array());
    while ($row = mysqli_fetch_assoc($q)) {
        if (mysqli_num_rows($q) == 1) {
            addToCartByID($row['id']);
            exit();
        } else {
            $items[$row['id']] = $row;
            $items[$row['id']]['mainImage'] = get_main_image($row['id']);
        }
    }
    outputSearch($items);

}
function addToCartByTag($tag){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                                WHERE tag='$tag'"));
    addToCart($q);
}
function addToCartByID($id){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                            WHERE id='$id'"));
    addToCart($q);
}
function addToCart($q){
    $items = array(array());
    while ($row = mysqli_fetch_assoc($q)) {
        if (mysqli_num_rows($q) == 1) {
            $dbID = $row['id'];
            if (isset($_SESSION['cart'][$row['id']])){
                $_SESSION['cart'][$row['id']]['quantity']++;
            } else {
                $itemID =  $row['id'];
                $_SESSION['cart'][$row['id']]['IMG'] = get_main_image($itemID);
                $_SESSION['cart'][$row['id']]['id'] = $row['id'];
                $_SESSION['cart'][$row['id']]['tag'] = $row['tag'];
                $_SESSION['cart'][$row['id']]['name'] = html_entity_decode($row['name'], ENT_QUOTES, "UTF-8");
                $_SESSION['cart'][$row['id']]['quantity'] = 1;
                $_SESSION['cart'][$row['id']]['Available'] = get_quantity_sum($row['id']);
                $_SESSION['cart'][$row['id']]['loc'] = get_locations($row['id']);
                $_SESSION['cart'][$row['id']]['loc']['selected'] =
                    get_single_location_with_type($_COOKIE['default_location_type'], $_SESSION['cart'][$row['id']]['loc']['locationList']);
                $query = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM 
                                                {*product_platforms*} WHERE id_item='$dbID' AND id_platform='1'"));
                while ($rowQ = mysqli_fetch_assoc($query)) {
                    $_SESSION['cart'][$row['id']]['price'] = number_format($rowQ['price'], 2, ".", "");
                    if (is_numeric($rowQ['price'])){
                        $_SESSION['cart'][$row['id']]['basePrice'] = number_format($rowQ['price'], 2, ".", "");
                    } else {
                        $_SESSION['cart'][$row['id']]['basePrice'] = number_format(0, 2, ".", "");
                    }
                }
                if (mysqli_num_rows($query) != 1){
                    $_SESSION['cart'][$row['id']]['basePrice'] = number_format(0, 2, ".", "");
                }
            }
            updateCart();
            exit();
        } else {
            $items[$row['id']] = $row;
            $items[$row['id']]['mainImage'] = get_main_image($row['id']);
        }
        outputSearch($items);
    }
}
function outputSearch($items){
    echo json_encode(array_filter($items));
}