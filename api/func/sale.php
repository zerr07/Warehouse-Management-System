<?php
function performSale($data, $cart){
    if (!defined('PRODUCTS_INCLUDED')){
        include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
    }
    $sum = 0;

    foreach ($cart as $key => $value){
        $sum += $value['price'];
    }
    if (array_key_exists("card", $data)){
        if ($data['card'] == 1){
            $cash = 0;
            $card = $sum;
        } else {
            $card = 0;
            $cash = $sum;
        }
    } else {
        exit("No card key found.");
    }

    include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/orderMode.php';
    if (!array_key_exists("mode", $data)){
        exit("No mode key found.");
    } else {
        $mode = $data['mode'];
    }
    if (!array_key_exists("ostja", $data)){
        $ostja = "";
    } else {
        $ostja = $data['ostja'];
    }
    $ostja = orderMode($mode, $ostja);

    if (!array_key_exists("tellimuseNr", $data)){
        $telli = "";
    } else {
        $telli = $data['tellimuseNr'];
    }

    $stamp = date_timestamp_get(date_create())*9;
    $mysqldate = date("Y-m-d H:i:s");
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*sales*}
                                (cartSum, card, cash, arveNr, saleDate, ostja, modeSet, tellimuseNr) 
                                VALUES ('$sum', '$card', '$cash', '$stamp','$mysqldate', '$ostja', '$mode', '$telli')"));

    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM sales"));
    $row = mysqli_fetch_assoc($q);
    $id = $row['id'];
    foreach ($cart as $key => $value){
        $price = $value['price'];
        $quantity = $value['quantity'];
        $basePrice = $value['basePrice'];
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "UPDATE {*products*} SET quantity=quantity-$quantity WHERE id='$key'"));
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "INSERT INTO {*sold_items*} (id_sale, id_item, price, quantity, basePrice, statusSet
                                        ) VALUES ('$id', '$key', '$price', '$quantity', '$basePrice','Müük')"));
    }
}