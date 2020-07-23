<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
if (isset($_POST['loc_select'])){
    foreach ($_POST['loc_select'] as $key => $value){
        $_SESSION['cart'][$key]['loc']['selected'] = $value;
    }
    updateCart();
}
if (isset($_POST['id'])){
    $id1 = $_POST['id'];
    $i = 0;
    foreach ($id1 as $item){
        $_SESSION['cart'][$item]['basePrice'] = number_format($_POST['price'][$i],2);
        $_SESSION['cart'][$item]['quantity'] = $_POST['quantity'][$i];
        $i++;

    }
    updateCart();
}
if (isset($_POST['bufferID'])){
    $i = 0;
    foreach ($_POST['bufferID'] as $id2){
        $_SESSION['cart'][$id2]['name'] = $_POST['buffer'][$i];
        $i++;
    }
    updateCart();
}
if (isset($_POST['delete'])){
    unset($_SESSION['cart'][$_POST['delete']]);
    updateCart();
}
calcCart();
if (isset($_POST['update']) || isset($_POST['delete'])){

    header('Location: /cp/POS' );
}

function calcCart(){
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {

            $_SESSION['cart'][$key]['price'] = number_format($_SESSION['cart'][$key]['quantity'] * $_SESSION['cart'][$key]['basePrice'], 2);
        }

        $_SESSION['cartTotal'] = 0;

        foreach ($_SESSION['cart'] as $key => $item) {
            $_SESSION['cartTotal'] += $_SESSION['cart'][$key]['price'];
        }
    } else {
        $_SESSION['cartTotal'] = 0;
    }
    updateCart();
}
