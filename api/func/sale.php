<?php
function performSale($data, $cart, $id_type)
{
    if (!defined('PRODUCTS_INCLUDED')) {
        include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
    }
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/updateQuantity.php');

    $sum = 0;

    foreach ($cart as $key => $value) {
        $sum += $value['price'];
    }
    if (array_key_exists("card", $data)) {
        if ($data['card'] == 1) {
            $cash = 0;
            $card = $sum;
        } else {
            $card = 0;
            $cash = $sum;
        }
    } else {
        exit(json_encode(array("error"=>"No card key found.", "code"=>"601")));
    }

    include_once ($_SERVER["DOCUMENT_ROOT"]) . '/cp/POS/orderMode.php';
    if (!array_key_exists("mode", $data)) {
        exit(json_encode(array("error"=>"No mode key found.", "code"=>"600")));
    } else {
        $mode = $data['mode'];
    }
    if (!array_key_exists("ostja", $data)) {
        if (!array_key_exists("client", $data)) {
            $ostja = "";
        } else {
            $ostja = $data['client'];
        }
    } else {
        $ostja = $data['ostja'];
    }
    $ostja = orderMode($mode, $ostja);

    if (!array_key_exists("tellimuseNr", $data)) {
        if (!array_key_exists("shipmentNr", $data)) {
            $telli = "";
        } else {
            $telli = $data['shipmentNr'];
        }

    } else {
        $telli = $data['tellimuseNr'];
    }

    $stamp = date_timestamp_get(date_create()) * 9;
    $mysqldate = date("Y-m-d H:i:s");
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*sales*}
                                (cartSum, card, cash, arveNr, saleDate, ostja, modeSet, tellimuseNr) 
                                VALUES ('$sum', '$card', '$cash', '$stamp','$mysqldate', '$ostja', '$mode', '$telli')"));

    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM sales"));
    $row = mysqli_fetch_assoc($q);
    $id = $row['id'];
    foreach ($cart as $key => $value) {
        $price = $value['price'];
        $quantity = $value['quantity'];
        $basePrice = $value['basePrice'];
        $loc = $value['id_location'];
        if (isset($value['id_location'])) {
            update_quantity($key, $value['id_location'], "-", $quantity);
        } else {
            $loc = get_single_location_with_type($id_type, get_locations($key)['locationList']);
            update_quantity($key, "", "-", $quantity);
        }
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "INSERT INTO {*sold_items*} (id_sale, id_item, price, quantity, basePrice, statusSet, id_location
                                        ) VALUES ('$id', '$key', '$price', '$quantity', '$basePrice','Müük', '$loc')"));
    }
}