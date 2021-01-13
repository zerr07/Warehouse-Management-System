<?php
Route::add("/api/sale", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/controllers/getSales.php";

        if (isset($_GET['id'])) {
            $sale = get_single_sale($_GET['id']);
            if (is_null($sale)) {
                exit(json_encode(array("error" => "No sale with this id", "code" => "400")));
            } else {
                exit(json_encode($sale));
            }
        } elseif (isset($_GET['invoice'])) {
            $nr = $_GET['invoice'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sales*} WHERE arveNr='$nr'"));

            $sale = read_sale_result_single_query($q);
            if (is_null($sale)) {
                exit(json_encode(array("error" => "No sale with this id", "code" => "400")));
            } else {
                exit(json_encode($sale));
            }
        } else {
            $sales = get_sales_n(true);
            if (is_null($sales)){
                exit(json_encode(array()));
            } else {
                exit(json_encode($sales));
            }
        }

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/sale", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/api/func/fromCart.php");
        $data = json_decode(file_get_contents('php://input'), true);

        $cart = array();
        foreach ($data['products'] as $key => $value){
            $cart = $cart + formCart($key, $value);
        }
        $cart = array_filter($cart);
        include_once ($_SERVER['DOCUMENT_ROOT']."/api/func/sale.php");
        performSale($data, $cart, 2);
        exit(json_encode(array("success"=>"Sale performed successfully.")));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");

Route::add("/api/sale", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");

Route::add("/api/sale", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/SMT/restoreQuantity.php');

            if (isset($data["products"])) {
                foreach ($data["products"] as $value) {
                    if (is_numeric($value)) {
                        restore_single($value);
                    } else {
                        if (!defined('PRODUCTS_INCLUDED')) {
                            include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
                        }
                        $id = $data['id'];
                        $id_prod = get_product_id_by_tag($value);
                        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM 
                                                                    {*sold_items*} WHERE id_sale='$id' AND id_item='$id_prod'"));
                        if ($q) {
                            if ($q->num_rows != 1) {
                                exit(json_encode(array("error" => "There is on or multiple products with this tag for $value. Please contact administrator.", "code"=>"500")));
                            } else {
                                restore_single($q->fetch_assoc()['id']);
                            }
                        } else {
                            exit(json_encode(array("error" => "Could not get product in sale by its tag for $value.", "code"=>"501")));
                        }
                    }
                }
                exit(json_encode(array("success" => "Sale products cancelled.")));
            } else {
                restore_full($data['id']);
                exit(json_encode(array("success" => "Sale cancelled.")));
            }
        } else {
            exit(json_encode(array("error" => "No Sale id supplied.", "code"=>"502")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");