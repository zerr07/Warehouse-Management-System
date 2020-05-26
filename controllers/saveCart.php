<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
function updateCart(){
    if($_SESSION['cart'] != "\"null\"" || $_SESSION['cart'] != "\"[]\""){

        $cart = rawurlencode(json_encode($_SESSION['cart']));
        $id = $_COOKIE['user_id'];

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
}