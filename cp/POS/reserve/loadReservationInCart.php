<?php
include_once ($_SERVER['DOCUMENT_ROOT']."/configs/config.php");
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';

if (isset($_GET['id'])) {   //reservation id
    $reservation = getSingleCartReservation($_GET['id']);
    unset($_SESSION['cartTotal']);
    unset($_SESSION['cart']);
    foreach ($reservation['products'] as $key => $value){
        if (is_numeric($value['id_product'])){
            $row = get_product($value['id_product']);
            $itemID = $row['id'];
            $_SESSION['cart'][$row['id']]['IMG'] = get_main_image($itemID);
            $_SESSION['cart'][$row['id']]['id'] = $row['id'];
            $_SESSION['cart'][$row['id']]['tag'] = $row['tag'];
            $_SESSION['cart'][$row['id']]['quantity'] = $reservation['products'][$key]['quantity'];
            $_SESSION['cart'][$row['id']]['Available'] = get_quantity_sum($row['id']);
            $_SESSION['cart'][$row['id']]['name'] = $reservation['products'][$key]['name']['et'];
            $_SESSION['cart'][$row['id']]['loc'] = get_locations($row['id']);
            $_SESSION['cart'][$row['id']]['loc']['selected'] =
                get_single_location_with_type($_COOKIE['default_location_type'], $_SESSION['cart'][$row['id']]['loc']['locationList']);
            $_SESSION['cart'][$row['id']]['price'] = $reservation['products'][$key]['price'];
            $_SESSION['cart'][$row['id']]['basePrice'] = $reservation['products'][$key]['basePrice'];
        } else {
            $stamp = date_timestamp_get(date_create())*99;
            $_SESSION['cart'][$stamp]['IMG'] = "";
            $_SESSION['cart'][$stamp]['id'] = $stamp;
            $_SESSION['cart'][$stamp]['tag'] = "Buffertoode";
            $_SESSION['cart'][$stamp]['name'] = $value['id_product'];
            $_SESSION['cart'][$stamp]['quantity'] = $value['quantity'];
            $_SESSION['cart'][$stamp]['Available'] = $stamp;
            $_SESSION['cart'][$stamp]['basePrice'] = $value['basePrice'];
            $_SESSION['cart'][$stamp]['price'] = $value['price'];
        }

    }
    updateCart();
}
header("Location: /cp/POS");
