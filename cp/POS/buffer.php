<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/update.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/saveCart.php';

include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
$stamp = date_timestamp_get(date_create())*99;
$_SESSION['cart'][$stamp]['IMG'] = "";
$_SESSION['cart'][$stamp]['id'] = $stamp;
$_SESSION['cart'][$stamp]['tag'] = "Buffertoode";
$_SESSION['cart'][$stamp]['name'] = "Buffertoode";
$_SESSION['cart'][$stamp]['quantity'] = 1;
$_SESSION['cart'][$stamp]['Available'] = $stamp;
$_SESSION['cart'][$stamp]['basePrice'] = number_format(0, 2);
$_SESSION['cart'][$stamp]['price'] = number_format(0, 2);
updateCart();
header("Location: /cp/POS");
