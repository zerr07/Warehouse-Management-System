<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');

function updateCart(){
    if($_SESSION['cart'] != "\"null\"" || $_SESSION['cart'] != "\"[]\""){

        $cart = rawurlencode(json_encode($_SESSION['cart']));
        $id = $_COOKIE['user_id'];
        updateQuantity();
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "UPDATE {*users*} SET cart='$cart' WHERE id='$id'"));
    }
}

function getCart(){

    $id = $_COOKIE['user_id'];
    $result = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT {*cart*} FROM users WHERE id='$id'"));
    while ($row = mysqli_fetch_assoc($result)){

        $cart = rawurlencode(json_encode($_SESSION['cart']));
        if ($cart != $row['cart']){
            $_SESSION['cart'] = json_decode(rawurldecode($row['cart']),true);
        }
    }
    updateQuantity();
}

function updateQuantity(){
    if (isset($_SESSION['cart'])){
        foreach ($_SESSION['cart'] as $key => $value){
            $_SESSION['cart'][$key]['Available'] = get_quantity($key);
        }
    }

}