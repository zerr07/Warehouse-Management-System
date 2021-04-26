<?php
Route::add("/api/SyncSupplier", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        if (isset($_GET['supplier_name']) && $_GET['supplier_name'] != ""){
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT SKU FROM {*supplier_data*} 
            WHERE supplierName='Dreamlove'"));
            $arr = array();
            while ($row = $q->fetch_assoc()){
                array_push($arr, $row['SKU']);
            }
            exit(json_encode(array("success"=>$arr)));
        } else {
            exit(json_encode(array("error" => "Supplier name either empty or not supplied.", "code"=>"1300")));
        }

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");
Route::add("/api/SyncSupplier", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "POST");
Route::add("/api/SyncSupplier", function () {
    ini_set("display_errors", "on");
    error_reporting(E_ALL ^ E_NOTICE);
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = array();
        if (isset($data['supplier_sku']) && $data['supplier_sku'] != ""){
            $sku = $data['supplier_sku'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_item FROM {*supplier_data*} 
                WHERE SKU='$sku' LIMIT 1"));
            if ($q->num_rows == 0){
                if (isset($data['barcodes']) && !empty($data['barcodes'])){
                    foreach ($data['barcodes'] as $code){
                        $q_prod = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_product FROM 
                            {*product_codes*} WHERE ean='$code'"));
                        if ($q_prod->num_rows >= 1){
                            $id_product = $q_prod->fetch_assoc()['id_product'];
                        }
                    }

                } elseif (isset($data['product_name'])){
                    $name = $data['product_name'];
                    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_product 
                              FROM product_name WHERE id_lang='2' AND name='$name'"));
                    if ($q){
                        if($q->num_rows == 1){
                            $id_product = $q->fetch_assoc()['id_product'];
                            array_push($res, "Found by name");
                        } else {
                            exit(json_encode(array("error" => "No product found by SKU, by barcode and name.", "code"=>"1207")));
                        }
                    } else {
                        exit(json_encode(array("error" => "No product found by SKU, by barcode and name.", "code"=>"1207")));
                    }
                } else {
                    exit(json_encode(array("error" => "No product found by SKU, 
                        barcodes either empty or not supplied.", "code"=>"1204")));
                }
                if (isset($data['price']) && $data['price'] != ""){
                    if (isset($data['supplier_url']) && $data['supplier_url'] != ""){
                        if (isset($data['supplier_name']) && $data['supplier_name'] != ""){
                            $price = $data['price'];
                            $supplier_url = $data['supplier_url'];
                            $supplier_name = $data['supplier_name'];
                            $q_supp = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO
                                         {*supplier_data*} (id_item, URL, priceVAT, supplierName, SKU) 
                                         VALUES ('$id_product', '$supplier_url', '$price', '$supplier_name', '$sku');"));
                            if ($q_supp){
                                array_push($res, "Supplier data inserted");
                            } else {
                                array_push($res, "Supplier data not inserted");
                            }
                        } else {
                            exit(json_encode(array("error" => "Supplier name either empty or not supplied.", "code"=>"1201")));
                        }
                    } else {
                        exit(json_encode(array("error" => "No url supplied hence supplier data cannot be 
                                    inserted.", "code"=>"1206")));
                    }
                } else {
                    exit(json_encode(array("error" => "No price supplied hence supplier data cannot be 
                                    inserted.", "code"=>"1205")));
                }

            } else {
                $id_product = $q->fetch_assoc()['id_item'];
            }
            if (isset($data['qty']) && $data['qty'] != ""){
                if (isset($data['warehouse_id']) && $data['warehouse_id'] != ""){
                    $id_warehouse = $data['warehouse_id'];
                    $qty = $data['qty'];
                    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_locations*}
                           SET quantity='$qty' WHERE id_item='$id_product' AND id_type='$id_warehouse'"));

                    if ($GLOBALS['DBCONN']->affected_rows == 0){
                        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_locations*}
                         WHERE id_item='$id_product' AND id_type='$id_warehouse'"));
                        if ($q->num_rows == 0){
                            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO product_locations
                                (id_item, location, id_type, quantity) VALUES ('$id_product', 'Dreamlove ladu', '1', '$qty')"));
                            if ($q){
                                array_push($res, "QTY inserted");
                            } else {
                                array_push($res, "QTY not inserted");
                            }
                        } else {
                            array_push($res, "QTY already set");
                        }

                    } else {
                        if ($q){
                            array_push($res, "QTY updated");
                        } else {
                            array_push($res, "QTY not updated");
                        }
                    }
                } else {
                    exit(json_encode(array("error" => "Warehouse id either empty or not supplied.", "code"=>"1202")));
                }
            }
            if (isset($data['price']) && $data['price'] != ""){
                if (isset($data['supplier_name']) && $data['supplier_name'] != ""){
                    $supp_name = $data['supplier_name'];
                    $price = $data['price'];

                    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*supplier_data*} 
                           SET priceVAT='$price' WHERE SKU='$sku' AND supplierName='$supp_name'"));
                    if ($q){
                        array_push($res, "Supplier price updated");
                    } else {
                        array_push($res, "Supplier price not updated");
                    }
                    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} 
                           SET actPrice='$price' WHERE id='$id_product'"));
                    if ($q){
                        array_push($res, "Actual price updated");
                    } else {
                        array_push($res, "Actual price not updated");
                    }
                } else {
                    exit(json_encode(array("error" => "Supplier name either empty or not supplied.", "code"=>"1201")));
                }
            }
            exit(json_encode(array("success"=>$res)));
        } else {
            exit(json_encode(array("error" => "Supplier SKU either empty or not supplied.", "code"=>"1200")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "PUT");
Route::add("/api/SyncSupplier", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['supplier_name']) && $data['supplier_name'] != ""){
            if (isset($data['supplier_sku']) && $data['supplier_sku'] != ""){
                $sku = $data['supplier_sku'];
                $name = $data['supplier_name'];
                $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM product_locations 
                WHERE id_type=(SELECT id_type FROM location_type WHERE name='$name') 
                AND id_item=(SELECT id_item FROM supplier_data WHERE supplierName='$name' AND SKU='$sku' LIMIT 1)"));
                if ($q){
                    exit(json_encode(array("success" => "")));
                } else {
                    exit(json_encode(array("error" => "Error while processing delete query.", "code"=>"1402")));
                }
            } else {
                exit(json_encode(array("error" => "Supplier SKU either empty or not supplied.", "code"=>"1401")));
            }
        } else {
            exit(json_encode(array("error" => "Supplier name either empty or not supplied.", "code"=>"1400")));
        }

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");