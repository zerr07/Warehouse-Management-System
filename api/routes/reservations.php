<?php

Route::add("/api/reservations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php";
        if (isset($_GET['display'])) {
            if ($_GET['display'] == 'both') {
                exit(json_encode(getReservedCarts('unset', true)));
            }
        } elseif (isset($_GET['id'])) {
            exit(json_encode(getSingleCartReservation($_GET['id'])));
        } else {
            exit(json_encode(getReservedCarts(1, true)));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/reservations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
        include_once($_SERVER['DOCUMENT_ROOT'] . "/api/func/fromCart.php");

        $data = json_decode(file_get_contents('php://input'), true);
        $cart = array();
        foreach ($data['products'] as $key => $value) {
            $cart = $cart + formCart($key, $value);
        }

        reserveCartWithoutUpdate($data['note'], array_filter($cart));
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));
        exit(json_encode(array("id" => mysqli_fetch_assoc($q)['id'])));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");

Route::add("/api/reservations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['products'])) {
            editReservation($data['id'], $data['products']);
        }
        if (isset($data['comment'])) {
            $comment = $data['comment'];
            $id = $data['id'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved*} SET `comment`='$comment' WHERE id='$id'"));
            if (!$q) {
                exit(json_encode(array("error" => "Unable to change comment, SQL error.", "code" => "202")));
            }
        }
        exit(json_encode(array("success" => "Reservation updated.")));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "PUT");

Route::add("/api/reservations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            if (isset($data["products"])) {
                foreach ($data["products"] as $value) {
                    if (is_numeric($value)) {
                        cancelReservationProduct($data['id'], $value);
                    } else {
                        if (!defined('PRODUCTS_INCLUDED')) {
                            include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
                        }
                        $id = $data['id'];
                        $id_prod = get_product_id_by_tag($value);
                        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM 
                                                                    {*reserved_products*} WHERE id_reserved='$id' AND id_product='$id_prod'"));
                        if ($q) {
                            if ($q->num_rows != 1) {
                                exit(json_encode(array("error" => "There is on or multiple products with this tag for $value. Please contact administrator.", "code"=>"302")));
                            } else {
                                cancelReservationProduct($id, $q->fetch_assoc()['id']);
                            }
                        } else {
                            exit(json_encode(array("error" => "Could not get product in reservation by its tag for $value.", "code"=>"301")));
                        }
                    }
                }
                exit(json_encode(array("success" => "Reservation products cancelled.")));
            } else {
                cancelReservationFull($data['id']);
                exit(json_encode(array("success" => "Reservation cancelled.")));
            }
        } else {
            exit(json_encode(array("error" => "No reservation id supplied.", "code"=>"300")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");
Route::add("/api/reservations/confirm", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "GET");
Route::add("/api/reservations/confirm", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
        include_once($_SERVER['DOCUMENT_ROOT'] . "/api/func/fromCart.php");

        $data = json_decode(file_get_contents('php://input'), true);

        if (!array_key_exists('id', $data)){
            exit(json_encode(array("error" => "Reservation id not supplied.", "code"=>"700")));
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
        if ($q->num_rows != 0){
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
            exit(json_encode(array("success" => "Reservation confirmed.")));

        } else {
            exit(json_encode(array("error" => "Error processing reservation with id: ".$id.". Check your request.", "code"=>"701")));
        }



    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");
Route::add("/api/reservations/confirm", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");
Route::add("/api/reservations/confirm", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");
Route::add("/api/reservations/merge", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "GET");
Route::add("/api/reservations/merge", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "POST");
Route::add("/api/reservations/merge", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/merge.php");

        $data = json_decode(file_get_contents('php://input'), true);
        foreach ($data as $id){
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} WHERE id='$id'"));
            if ($q){
                if ($q->num_rows == 0){
                    exit(json_encode(array("error" => "Reservation with id ".$id." not found", "code"=>"1202")));
                }
                $row = $q->fetch_assoc();
                if ($row['id_type'] != 1){
                    exit(json_encode(array("error" => "Reservation with id ".$id." is invalid type and cannot be merged", "code"=>"1201")));
                }
            } else {
                exit(json_encode(array("error" => "Failed to process query", "code"=>"1200")));
            }
        }
        $res = mergeReservations($data);
        if (is_null($res)){
            exit(json_encode(array("error" => "Invalid result received, please contact administrator.", "code"=>"1203")));
        } else {
            exit(json_encode(array("id" => $res)));
        }


    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "PUT");
Route::add("/api/reservations/merge", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");