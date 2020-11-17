<?php
header('Content-Type: text/plain');

session_start();
if (isset($_SESSION['cart'])){
    foreach ($_SESSION['cart'] as $key => $value){
        if (!array_key_exists('date_added', $_SESSION['cart'][$key])){
            $_SESSION['cart'][$key]['date_added'] = "1970-01-01 01:01:01";
        }
    }
    echo json_encode($_SESSION['cart']);
}

