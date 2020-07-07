<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')) {
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}

function formCart($key, $value) { // $key is product tag, $value is array with at least quantity key
    $cart = array(array());
    $arr = get_product_by_tag($key);
    $cart[$arr['id']]['tag'] = $key;
    if (array_key_exists("quantity", $value)){
        $cart[$arr['id']]['quantity'] = $value['quantity'];
    } else {
        $cart[$arr['id']]['quantity'] = 1;
    }
    if (array_key_exists("price", $value)){
        $cart[$arr['id']]['price'] =  $value['price'];
    } else {
        if (array_key_exists("basePrice", $value)){
            $cart[$arr['id']]['price'] =  $value['basePrice']*$cart[$arr['id']]['quantity'];
        } else {
            $cart[$arr['id']]['price'] =  $arr['platforms'][1]['price']*$cart[$arr['id']]['quantity'];
        }
    }
    if (array_key_exists("basePrice", $value)){
        $cart[$arr['id']]['basePrice'] =  $value['basePrice'];
    } else {
        if (array_key_exists("price", $value)) {
            $cart[$arr['id']]['basePrice'] =  $value['price']/$cart[$arr['id']]['quantity'];
        } else {
            $cart[$arr['id']]['basePrice'] =  $arr['platforms'][1]['price'];
        }
    }
    return $cart;
}