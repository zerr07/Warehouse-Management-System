<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}

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
        $_SESSION['cartTotal'] = 0;
        if (isset($_SESSION['cart'])){
            $cart = rawurlencode(json_encode($_SESSION['cart']));
            if ($cart != $row['cart']){
                $_SESSION['cart'] = json_decode(rawurldecode($row['cart']),true);
            }
        } else {
            $_SESSION['cart'] = json_decode(rawurldecode($row['cart']),true);
        }
        $cart = rawurlencode(json_encode($_SESSION['cart']));
        if ($cart != $row['cart']){
            $_SESSION['cart'] = json_decode(rawurldecode($row['cart']),true);
        }
        if (isset($_SESSION['cart'])){
            foreach ($_SESSION['cart'] as $k => $v)
                $_SESSION['cartTotal'] += $v['quantity']*$v['basePrice'];
                if (empty($v['loc']['locations'])){
                    $_SESSION['cart'][$k]['loc'] = get_locations($k);
                    $_SESSION['cart'][$k]['loc']['selected'] =
                        get_single_location_with_type($_COOKIE['default_location_type'], $_SESSION['cart'][$k]['loc']['locationList']);
                }
        }

        }

    updateQuantity();
}

function updateQuantity(){
    if (isset($_SESSION['cart'])){
        foreach ($_SESSION['cart'] as $key => $value){
            if ($value['tag'] != "Buffertoode"){
                $_SESSION['cart'][$key]['Available'] = get_quantity_sum($key);
            }
        }
    }

}