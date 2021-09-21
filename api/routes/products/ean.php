<?php
Route::add("/api/product/codes", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        if (isset($_GET['tag']) && $_GET['tag'] != ""){
            $tag = $_GET['tag'];
            $id = checkRow("products", "tag='$tag'");
            if (!$id)
                exit(json_encode(array("error" => "Product not found using tag: ". $tag, "code"=>"2401")));
        } else if (isset($_GET['id']) && $_GET['id'] != "") {
            $id = checkRow("products", "id='".$_GET['id']."'");
            if (!$id)
                exit(json_encode(array("error" => "Product not found using id: ". $_GET['id'], "code"=>"2402")));
        } else {
            exit(json_encode(array("error" => "Product identifier not set", "code"=>"2400")));
        }
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, ean FROM {*product_codes*} WHERE id_product='$id'"));
        $arr = array();
        while ($row = $q->fetch_assoc()){
            array_push($arr, array("id"=>$row['id'], "code"=>$row['ean']));
        }
        exit(json_encode($arr));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/product/codes", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['tag']) && $data['tag'] != ""){
            $tag = $data['tag'];
            $id = checkRow("products", "tag='$tag'");
            if (!$id)
                exit(json_encode(array("error" => "Product not found using tag: ". $tag, "code"=>"2501")));
        } else if (isset($data['id']) && $data['id'] != "") {
            $id = checkRow("products", "id='".$data['id']."'");
            if (!$id)
                exit(json_encode(array("error" => "Product not found using id: ". $data['id'], "code"=>"2502")));
        } else {
            exit(json_encode(array("error" => "Product identifier not set", "code"=>"2500")));
        }
        if (isset($data['codes'])){
            insertEAN($id, $data['codes']);
        }

        exit(json_encode(array("success"=>"Images updated for product: ".$id)));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");

Route::add("/api/product/codes", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");

Route::add("/api/product/codes", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id']) && $data['id'] != "") {
            $id = checkRow("product_codes", "id='".$data['id']."'");
            if (!$id)
                exit(json_encode(array("error" => "Code not found using id: ". $id, "code"=>"2601")));
            else
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_codes*} WHERE id='$id'"));
            exit(json_encode(array("success"=>"Code successfully deleted.")));
        } else {
            exit(json_encode(array("error" => "Code identifier not set.", "code"=>"2600")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");