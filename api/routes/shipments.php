<?php

Route::add("/api/shipments", function () {
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
            exit(json_encode(getReservedCarts(2, true)));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/shipments", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/convert.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])){
            convertToShipping($data['id']);
        } else {
            exit(json_encode(array("error" => "Reservation id not found.", "code"=>"800")));
        }
        convertToShipping($data['id']);
        exit(json_encode(array("success" => "Reservation converted to shipment.")));

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");

Route::add("/api/shipments", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");

Route::add("/api/shipments", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $id = $data['id'];

            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id_status FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
            $id_status = $q->fetch_assoc()['id_status'];
            if ($id_status == 1 || $id_status == 2) {
                if (isset($data["products"])) {
                    foreach ($data["products"] as $value) {
                        if (is_numeric($value)) {
                            cancelReservationProduct($data['id'], $value);
                        } else {
                            if (!defined('PRODUCTS_INCLUDED')) {
                                include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
                            }
                            $id_prod = get_product_id_by_tag($value);
                            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM 
                                                                        {*reserved_products*} WHERE id_reserved='$id' AND id_product='$id_prod'"));
                            if ($q) {
                                if ($q->num_rows != 1) {
                                    exit(json_encode(array("error" => "There is on or multiple products with this tag for $value. Please contact administrator.", "code" => "902")));
                                } else {
                                    cancelReservationProduct($id, $q->fetch_assoc()['id']);
                                }
                            } else {
                                exit(json_encode(array("error" => "Could not get product in shipment by its tag for $value.", "code" => "901")));
                            }
                        }
                    }
                    exit(json_encode(array("success" => "Shipment products cancelled.")));
                } else {
                    cancelReservationFull($data['id']);
                    exit(json_encode(array("success" => "Shipment cancelled.")));
                }
            } else {
                exit(json_encode(array("error" => "Shipment status does not allow cancellation.", "code"=>"903")));
            }
        } else {
            exit(json_encode(array("error" => "No shipment id supplied.", "code"=>"900")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");