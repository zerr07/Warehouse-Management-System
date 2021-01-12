<?php
Route::add("/api/reservations", function (){
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])){
        include_once $_SERVER['DOCUMENT_ROOT']."/cp/POS/reserve/reserve.php";
        if (isset($_GET['display'])){
            if ($_GET['display'] == 'both'){
                exit(json_encode(getReservedCarts('unset', true)));
            }
        } elseif (isset($_GET['id'])) {
            exit(json_encode(getSingleCartReservation($_GET['id'])));
        } else {
            exit(json_encode(getReservedCarts(1, true)));
        }
    } else if(isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error"=>"Unknown error", '100')));
    }
}, "GET");

Route::add("/api/reservations", function (){
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])){
        include_once ($_SERVER['DOCUMENT_ROOT']."/cp/POS/reserve/reserve.php");
        include_once($_SERVER['DOCUMENT_ROOT'] . "/api/func/fromCart.php");

        $data = json_decode(file_get_contents('php://input'), true);
        $cart = array();
        foreach ($data['products'] as $key => $value){
            $cart = $cart + formCart($key, $value);
        }

        reserveCartWithoutUpdate($data['note'], array_filter($cart));
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));
        exit(json_encode(array("id"=>mysqli_fetch_assoc($q)['id'])));
    } else if(isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error"=>"Unknown error", '100')));
    }
}, "POST");

Route::add("/api/reservations", function (){
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])){
        include_once ($_SERVER['DOCUMENT_ROOT']."/cp/POS/reserve/reserve.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['products'])){
            editReservation($data['id'], $data['products']);
        }
        if (isset($data['comment'])){
            $comment = $data['comment'];
            $id = $data['id'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved*} SET `comment`='$comment' WHERE id='$id'"));
            if (!$q){
                exit(json_encode(array("error"=>"Unable to change comment, SQL error.", "code"=>"202")));
            }
        }
        exit(json_encode(array("success"=>"Reservation updated.")));
    } else if(isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error"=>"Unknown error", '100')));
    }
}, "PUT");

Route::add("/api/reservations", function (){
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])){
        include_once ($_SERVER['DOCUMENT_ROOT']."/cp/POS/reserve/reserve.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])){
            if (isset($data["products"])){
                foreach ($data["products"] as $value){
                    if (is_numeric($value)){
                        cancelReservationProduct($data['id'], $value);
                    } else {
                        if (!defined('PRODUCTS_INCLUDED')){
                            include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
                        }
                        $id = $data['id'];
                        $id_prod = get_product_id_by_tag($value);
                        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM 
                                                                    {*reserved_products*} WHERE id_reserved='$id' AND id_product='$id_prod'"));
                        if ($q){
                            if ($q->num_rows != 1){
                                exit(json_encode(array("error"=>"There is on or multiple products with this tag for $value. Please contact administrator.", '302')));
                            } else {
                                cancelReservationProduct($id, $q->fetch_assoc()['id']);
                            }
                        } else {
                            exit(json_encode(array("error"=>"Could not get product in reservation by its tag for $value.", '301')));
                        }
                    }
                }
                exit(json_encode(array("success"=>"Reservation products cancelled.")));
            } else {
                cancelReservationFull($data['id']);
                exit(json_encode(array("success"=>"Reservation cancelled.")));
            }
        } else{
            exit(json_encode(array("error"=>"No reservation id supplied.", '300')));
        }
    } else if(isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error"=>"Unknown error", '100')));
    }
}, "DELETE");

Route::add("/api/editReservation.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/editReservation.php";
});
Route::add("/api/performSale.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/performSale.php";
});
Route::add("/api/remove_reservation.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/remove_reservation.php";
});
Route::add("/api/reservationConfirm.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/reservationConfirm.php";
});
Route::add("/api/reserve.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/reserve.php";
});
