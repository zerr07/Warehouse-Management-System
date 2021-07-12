<?php
Route::add("/api/product/locations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        if (isset($_GET['id']) || isset($_GET['reference'])){
            $q = false;
            if (isset($_GET['id'])){
                $id = $_GET['id'];
                $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_locations*} WHERE id_item='$id'"));
            } elseif (isset($_GET['reference'])){
                $tag = $_GET['reference'];
                $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_locations*} WHERE id_item=(SELECT id FROM {*products*} WHERE tag='$tag');"));
            }
            if ($q){

                if ($q->num_rows == 0){
                    exit(json_encode(array("error" => "No results retrieved", "code"=>"1501")));
                } else {
                    $arr = array();
                    while ($row = $q->fetch_assoc()){
                        array_push($arr, $row);
                    }
                    exit(json_encode($arr));
                }
            } else {
                exit(json_encode(array("error" => "Query error", "code"=>"1502")));
            }
        } else {
            exit(json_encode(array("error" => "No product id or reference supplied", "code"=>"1500")));
        }

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/product/locations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "POST");
Route::add("/api/product/locations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");
Route::add("/api/product/locations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");