<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

if (isset($_GET['username']) && isset($_GET['password'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $data = json_decode($_GET['data'], true);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $data = json_decode($_POST['data'],true);
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0) {
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    if (!defined('PRODUCTS_INCLUDED')){
        include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
    }
    $sum = 0;
    // Formatting cart
    include_once ($_SERVER['DOCUMENT_ROOT']."/api/fromCart.php");
    $cart = array();
    foreach ($data['products'] as $key => $value){
        $cart = $cart + formCart($key, $value);
    }
    $cart = array_filter($cart);
    foreach ($cart as $key => $value){
        $sum += $value['price'];
    }
    echo '<pre>'; print_r ($cart); echo '</pre>';
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

} else {
    exit("Username or password is incorrect");
}