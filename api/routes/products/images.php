<?php
Route::add("/api/product/images", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        if (isset($_GET['tag']) && $_GET['tag'] != ""){
            $tag = $_GET['tag'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} WHERE tag='$tag' LIMIT 1"));
            if ($q->num_rows == 0)
                exit(json_encode(array("error" => "Product not found using tag: ". $tag, "code"=>"2101")));
        } else if (isset($_GET['id']) && $_GET['id'] != "") {
            $id = $_GET['id'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} WHERE id='$id' LIMIT 1"));
            if ($q->num_rows == 0)
                exit(json_encode(array("error" => "Product not found using id: ". $id, "code"=>"2102")));
        } else {
            exit(json_encode(array("error" => "Product identifier not set", "code"=>"2100")));
        }
        $id = $q->fetch_assoc()['id'];
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, image FROM {*product_images*} WHERE id_item='$id'"));
        $arr = array();
        while ($row = $q->fetch_assoc()){
            array_push($arr, array("id"=>$row['id'], "url"=>$_SERVER['SERVER_NAME']."/uploads/images/products/".$row['image']));
        }
        exit(json_encode($arr));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");

Route::add("/api/product/images", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($_GET['tag']) && $_GET['tag'] != ""){
            $tag = $_GET['tag'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} WHERE tag='$tag' LIMIT 1"));
            if ($q->num_rows == 0)
                exit(json_encode(array("error" => "Product not found using tag: ". $tag, "code"=>"2201")));
        } else if (isset($_GET['id']) && $_GET['id'] != "") {
            $id = $_GET['id'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*products*} WHERE id='$id' LIMIT 1"));
            if ($q->num_rows == 0)
                exit(json_encode(array("error" => "Product not found using id: ". $id, "code"=>"2202")));
        } else {
            exit(json_encode(array("error" => "Product identifier not set", "code"=>"2200")));
        }
        $id = $q->fetch_assoc()['id'];
        if (isset($data['images'])){
            insertImages($id, $data['images'], "", false);
        }

        exit(json_encode(array("success"=>"Images updated for product: ".$id)));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");

Route::add("/api/product/images", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");

Route::add("/api/product/images", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id']) && $data['id'] != "") {
            $id = $data['id'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*product_images*} WHERE id='$id' LIMIT 1"));
            if ($q->num_rows == 0)
                exit(json_encode(array("error" => "Image not found using id: ". $id, "code"=>"2301")));
            else
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_images*} WHERE id='$id'"));
            exit(json_encode(array("success"=>"Image successfully deleted.")));
        } else {
            exit(json_encode(array("error" => "Image identifier not set.", "code"=>"2300")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");