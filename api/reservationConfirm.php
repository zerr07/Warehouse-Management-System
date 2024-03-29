<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/log.php');

if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
if (isset($_GET['username']) && isset($_GET['password'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $data = json_decode($_GET['data'], true);
    sys_log(array("GET"=>$_GET, "OTHERDATA"=>$data));

}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $data = json_decode($_POST['data'], true);
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
    if (!array_key_exists('id', $data)){
        exit("Reservation id not supplied.");
    }
    if (!array_key_exists('products', $data)){
        $id = $data['id'];
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*reserved_products*} WHERE id_reserved='$id'"));
    } else {
        $id = $data['id'];
        $search = "";
        $c = 0;
        foreach ($data['products'] as $value){
            if ($c == 0){
                $search .= "id_product='".get_product_id_by_tag($value)."'";
            } else {
                $search .= " OR id_product='".get_product_id_by_tag($value)."'";
            }
            $c++;
        }
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*reserved_products*} WHERE id_reserved='$id' AND ($search)"));
    }
    if ($q->num_rows == 1){
        // Formatting cart
        include_once($_SERVER["DOCUMENT_ROOT"] . '/cp/POS/reserve/reserve.php');

        $cart = array();
        while ($row = $q->fetch_assoc()){
            $cart[$row['id_product']] = array(
                "quantity" => $row['quantity'],
                "price" => $row['price'],
                "basePrice" => $row['basePrice']
            );
            cancelReservationProduct($data['id'], $row['id']);
        }

        $cart = array_filter($cart);
        include_once ($_SERVER['DOCUMENT_ROOT']."/api/func/sale.php");
        if (isset($_COOKIE['default_location_type'])){
            $def_loc = $_COOKIE['default_location_type'];
        } else {
            $def_loc = 1;
        }
        performSale($data, $cart,$def_loc);
    } else {
        exit("Could not get reservation with id:".$id);
    }


} else {
    exit("Username or password is incorrect");
}