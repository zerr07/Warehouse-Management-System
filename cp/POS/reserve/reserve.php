<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/saveCart.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');


function reserveCart($note, $cart){
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*reserved*} (`comment`) 
                                                                                                VALUES ('$note')"));
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));
    while($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        foreach ($cart as $key => $value){
            if($value['tag'] != "Buffertoode") {
                $quantity = $value['quantity'];
                $price = $value['price'];
                $basePrice = $value['basePrice'];
                mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
                    "UPDATE {*products*} SET quantity=quantity-$quantity WHERE id='$key'"));

                mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
                    "INSERT INTO {*reserved_products*} (id_reserved, id_product, price, quantity, basePrice
                                        ) VALUES ('$id', '$key', '$price', '$quantity', '$basePrice')"));
            }
        }
    }
    updateCart();
}

function getReservedCarts(){
    $arr = array(array());
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*}"));
    while ($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        $arr[$id] = readReservationResult($row);
    }
    return array_filter($arr);
}

function getSingleCartReservation($id){
    $arr = array();
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} WHERE id='$id'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr = readReservationResult($row);
    }
    return $arr;
}

function readReservationResult($row){
    $id = $row['id'];
    $arr = $row;
    $q_products = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM 
                                                                    {*reserved_products*} WHERE id_reserved='$id'"));
    while ($row_products = mysqli_fetch_assoc($q_products)){
        $arr['products'][$row_products['id']] = $row_products;
        $arr['products'][$row_products['id']]['tag'] = get_tag($row_products['id_product']);
        $arr['products'][$row_products['id']]['name'] = get_name($row_products['id_product']);
    }
    return $arr;
}

function cancelReservationFull($id){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, quantity, id_product FROM 
                    {*reserved_products*} WHERE id_reserved='$id'"));
    while ($row = mysqli_fetch_assoc($q)){
        $quantity = $row['quantity'];
        $id_product = $row['id_product'];
        $reserved_prod_row_id = $row['id'];
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "UPDATE {*products*} SET quantity=quantity+$quantity WHERE id='$id_product'"));
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "DELETE FROM {*reserved_products*} WHERE id='$reserved_prod_row_id'"));
    }
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "DELETE FROM {*reserved*} WHERE id='$id'"));

}

function cancelReservationProduct($id, $id_prod_res){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, quantity, id_product FROM 
                    {*reserved_products*} WHERE id='$id_prod_res'"));
    while ($row = mysqli_fetch_assoc($q)){
        $quantity = $row['quantity'];
        $reserved_prod_row_id = $row['id'];
        $id_product = $row['id_product'];
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
        "UPDATE {*products*} SET quantity=quantity+$quantity WHERE id='$id_product'"));
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "DELETE FROM {*reserved_products*} WHERE id='$reserved_prod_row_id'"));
    }
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT COUNT(*) as count FROM 
                    {*reserved_products*} WHERE id_reserved='$id'"));
    while ($row = mysqli_fetch_assoc($q)){
        if ($row['count'] == 0){
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "DELETE FROM {*reserved*} WHERE id='$id'"));
        }
    }
}

if (isset($_POST['req'])){
    $request = json_decode($_POST['req']);
    if ($request->reserve == "true") {
        reserveCart($request->note, $_SESSION['cart']);
        $_SESSION['cart'] = array();
        $_SESSION['cartTotal'] = 0.00;
        echo "Reserve success";
    }
}
if (isset($_GET['getReservationItemsJSON'])){
    $reservation = getSingleCartReservation($_GET['getReservationItemsJSON']);
    echo json_encode($reservation);
}


