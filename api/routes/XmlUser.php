<?php
Route::add("/api/user/xml", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, username FROM {*XML_users*}"));
        $arr = array();
        while ($row = $q->fetch_assoc()){
            array_push($arr, array($row['id']=>$row['username']));
        }
        exit(json_encode($arr));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");
Route::add("/api/user/xml", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['username']))
            exit(json_encode(array("error" => "No username supplied.", "code"=>"3300")));
        if (!isset($data['password']))
            exit(json_encode(array("error" => "No password supplied.", "code"=>"3301")));
        $username = $data['username'];
        $password = $data['password'];
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/register.php";
        registerXmlUser($username, $password);
        exit(json_encode(array("success"=>"User ".$username." successfully registered.")));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");
Route::add("/api/user/xml", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");
Route::add("/api/user/xml", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id']))
            exit(json_encode(array("error" => "No user id supplied.", "code"=>"3400")));
        $id = $data['id'];
        if (!checkRow("XML_users", "id='$id'"))
            exit(json_encode(array("error" => "No user with id: ".$id, "code"=>"3401")));

        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*XML_users*} WHERE id='$id'"));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");