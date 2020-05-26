<?php
header('Content-Type: text/plain');

session_start();
if (isset($_SESSION['cart'])){
    echo json_encode($_SESSION['cart']);
}

