<?php
Route::add("/api/locations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*location_type*}"));
        $arr = array();
        while ($row = $q->fetch_assoc()){
            array_push($arr, array($row['id']=>$row['name']));
        }
        exit(json_encode($arr));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");
Route::add("/api/locations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "POST");
Route::add("/api/locations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");
Route::add("/api/locations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");