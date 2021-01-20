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
            exit(json_encode(array("error" => "Reservation id not supplied.", "code"=>"800")));
        }

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

Route::add("/api/shipments/data", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/getShippingData.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/submitShippingClientsData.php";

        if (isset($_GET['id'])) {
            exit(json_encode(array("shipment_data"=>getData_full($_GET['id']), "payment_data"=>json_decode(getShipmentClientData($_GET['id'])))));
        } else {
            exit(json_encode(array("error" => "No shipment id supplied", "code"=>"1000")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/shipments/data", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
        include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/submitShippingClientsData.php");

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])){

            if (isset($data['shipment_data'])){
                if (isset($data['id_type'])){

                    include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/getShippingData.php");

                    if ($data['id_type'] == 1){
                        if (!isset($data['shipment_data']['name'])){
                            exit(json_encode(array("error" => "'name' key is missing, check your request.", "code"=>"1104")));
                        }
                        if (!isset($data['shipment_data']['phone'])){
                            exit(json_encode(array("error" => "'phone' key is missing, check your request.", "code"=>"1104")));
                        }
                        if (!isset($data['shipment_data']['shipmentNr'])){
                            exit(json_encode(array("error" => "'shipmentNr' key is missing, check your request.", "code"=>"1104")));
                        }
                        if (!isset($data['shipment_data']['terminal'])){
                            exit(json_encode(array("error" => "'terminal' key is missing, check your request.", "code"=>"1104")));
                        }
                        $COD = "";
                        if (isset($data['shipment_data']['smartpost_type'])){
                            if ($data['shipment_data']['smartpost_type'] == 1){
                                $type = "defDelivery";
                            } elseif ($data['shipment_data']['smartpost_type'] == 2){
                                $type = "cashOnDelivery";
                                if (isset($data['shipment_data']['smartpost_COD_sum'])){
                                    $COD = $data['shipment_data']['smartpost_COD_sum'];
                                } else {
                                    exit(json_encode(array("error" => "'smartpost_COD_sum' not found.", "code"=>"1106")));
                                }
                            } elseif ($data['shipment_data']['smartpost_type'] == 3){
                                $type = "clientPaysTheDelivery";
                            } else {
                                exit(json_encode(array("error" => "Unknown 'smartpost_type'.", "code"=>"1105")));
                            }
                        } else {
                            $type = "defDelivery";
                        }
                        if (isset($data['shipment_data']['comment'])){
                            $comment = $data['shipment_data']['comment'];
                        } else {
                            $comment = "";
                        }
                        if (isset($data['shipment_data']['email'])){
                            $email = $data['shipment_data']['email'];
                        } else {
                            $email = "";
                        }
                        $data_shipment = array(
                            "name"=>$data['shipment_data']['name'],
                            "phone"=> $data['shipment_data']['phone'],
                            "deliveryNr"=> $data['shipment_data']['shipmentNr'],
                            "terminal"=> $data['shipment_data']['terminal'],
                            "checked"=> $type,
                            "email"=> $email,
                            "comment"=> $comment,
                            "COD_Sum"=> $COD
                        );
                        saveData($data["id"], json_encode($data_shipment), 2, 1);
                    } else if ($data['id_type'] == 4){
                        if (isset($data["phone"])){
                            $data_shipment = array("phone"=>$data["phone"]);
                            saveData($data["id"], json_encode($data_shipment), 8, 4);
                        } else {
                            exit(json_encode(array("error" => "'phone' key is missing, check your request.", "code"=>"1104")));
                        }
                    } else {
                        exit(json_encode(array("error" => "Shipment type id not supported.", "code"=>"1101")));
                    }

                } else {
                    exit(json_encode(array("error" => "Shipment type id not submitted.", "code"=>"1102")));
                }
            }
            if (isset($data['payment_data'])){

                if ($data['payment_data']['card'] == 1){
                    $card = get_reservation_cart_sum($data['id']);
                    $cash = 0.00;
                    if (is_null($card)){
                        exit(json_encode(array("error" => "Unable to get cart sum.", "code"=>"1103")));
                    }
                } else {
                    $card = 0.00;
                    $cash = get_reservation_cart_sum($data['id']);
                    if (is_null($cash)){
                        exit(json_encode(array("error" => "Unable to get cart sum.", "code"=>"1103")));
                    }
                }
                $payment_data = array(
                    "data"=>array(
                        "cash" => $cash,
                        "card" => $card,
                        "ostja" => $data['client'],
                        "tellimuseNr" => $data['shipmentNr'],
                        "mode" => $data['mode'],
                        "id_cart" => $data['id'],
                        "shipmentID" => $data['id'])
                );
                insertShipmentClientData($data['id'], $payment_data);
            }
            if ($data['id_type'] == 1) {
                if (isset($data["barcode"])) {
                    if ($data["barcode"]) {
                        getBarSmartpost($data['id']);
                        $d = getData($data['id']);
                        exit(json_encode(array("barcode" => $d['barcode'])));

                    }

                }
            }
            exit(json_encode(array("success" => "Shipment data submitted.")));

        } else {
            exit(json_encode(array("error" => "Shipment id not found.", "code"=>"1100")));
        }

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");