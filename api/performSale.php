<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/log.php');

if (isset($_GET['username']) && isset($_GET['password'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $data = json_decode($_GET['data'], true);
    sys_log(array("GET"=>$_GET, "OTHERDATA"=>$data));
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $data = json_decode($_POST['data'],true);
    sys_log(array("POST"=>$_POST, "OTHERDATA"=>$data));
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0) {
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    // Formatting cart
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/func/fromCart.php");
    $cart = array();
    foreach ($data['products'] as $key => $value){
        $cart = $cart + formCart($key, $value);
    }
    $cart = array_filter($cart);
    include_once ($_SERVER['DOCUMENT_ROOT']."/api/func/sale.php");
    performSale($data, $cart, $res['default_location_type']);

} else {
    exit("Username or password is incorrect");
}