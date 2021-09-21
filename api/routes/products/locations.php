<?php

Route::add("/api/product/location", function () {
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        if (!isset($_GET['id'])){
            exit(json_encode(array("error" => "No product identifier set.", "code"=>"2700")));
        }
        $id = $_GET['id'];
        $arr = array("locations"=>array(), 'sum'=>"0");
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_locations*} WHERE id_item='$id'"));
        while ($row = $q->fetch_assoc()){
            array_push($arr['locations'], array($row['id_type']=>array("quantity"=>$row['quantity'], "name"=>$row['location'])));
        }
        $arr['sum'] = get_quantity_sum($id);
        exit(json_encode($arr));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");
Route::add("/api/product/locations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id_product']) || !isset($data['tag'])){
            exit(json_encode(array("error" => "No product identifier set.", "code"=>"2800")));
        } else {
            if (isset($data['id_product'])){
                $id_product = $data['id_product'];
                if (!checkRow("products", "id='$id_product'"))
                    exit(json_encode(array("error" => "No product found.", "code"=>"2804")));
            } elseif(isset($data['tag'])) {
                $tag = $_GET['tag'];
                $id_product = checkRow("products", "tag='$tag'");
                if (!$id_product)
                    exit(json_encode(array("error" => "Product not found using tag: ". $tag, "code"=>"2805")));
            }
        }
        if (!isset($data['type']))
            exit(json_encode(array("error" => "No location type set.", "code"=>"2801")));
        if (!isset($data['name']))
            exit(json_encode(array("error" => "No location name set.", "code"=>"2802")));
        if (!isset($data['quantity']))
            exit(json_encode(array("error" => "No location quantity set.", "code"=>"2803")));


        $type = $data['type'];
        $name = $data['name'];
        $quantity = $data['quantity'];

        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
            INSERT INTO {*product_locations*} (id_item, location, id_type, quantity) 
                VALUES ('$id_product', '$name', '$type', '$quantity')"));
        exit(json_encode(array("success"=>"Successfully created a location for product id: ".$id_product)));

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");
Route::add("/api/product/locations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id_location']))
            exit(json_encode(array("error" => "No location identifier set.", "code"=>"2900")));
        if (!isset($data['quantity']))
            exit(json_encode(array("error" => "No location quantity set.", "code"=>"2901")));

        $id = $data['id_location'];
        if (!checkRow("product_locations", "id='$id'"))
            exit(json_encode(array("error" => "No location found.", "code"=>"2902")));
        $quantity = $data['quantity'];

        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "
            UPDATE {*product_locations*} SET quantity='$quantity' WHERE id='$id'"));

        exit(json_encode(array("success"=>"Product location id: ".$id." updated.")));

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "PUT");
Route::add("/api/product/locations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id_location']))
            exit(json_encode(array("error" => "No location identifier set.", "code"=>"3000")));
        $id = $data['id_location'];
        if (!checkRow("product_locations", "id='$id'"))
            exit(json_encode(array("error" => "No location found.", "code"=>"3001")));
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_locations*} WHERE id='$id'"));
        exit(json_encode(array("success"=>"Product location id: ".$id." deleted.")));

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "DELETE");