<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')) {
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
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

    include_once ($_SERVER['DOCUMENT_ROOT']."/cp/POS/reserve/reserve.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/api/fromCart.php");
    
    $cart = array();
    foreach ($data['products'] as $key => $value){
        $cart = $cart + formCart($key, $value);
    }

    reserveCart($data['note'], array_filter($cart));
} else {
    exit("Username or password is incorrect");
}
