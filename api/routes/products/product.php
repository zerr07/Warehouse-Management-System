<?php
Route::add("/api/product", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "GET");
Route::add("/api/product", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/products/create_product.php";
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/cache.php";
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/prestashop/Products.php";
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['category']))
            exit(json_encode(array("error" => "Category not supplied.", "code"=>"1900")));
        if (!isset($data['prefix']))
            exit(json_encode(array("error" => "Prefix not supplied.", "code"=>"1901")));
        if (!isset($data['tag']))
            exit(json_encode(array("error" => "Tag not supplied.", "code"=>"1902")));
        if (!isset($data['actPrice']))
            exit(json_encode(array("error" => "actPrice not supplied.", "code"=>"1903")));
        if (!isset($data['name'])){
            exit(json_encode(array("error" => "Name not supplied.", "code"=>"1904")));
        }
        if (!isset($data['override']))
            $data['override'] = 0;
        if (!isset($data['marginPercent']))
            $data['marginPercent'] = 0.0;
        if (!isset($data['marginNumber']))
            $data['marginNumber'] = 0.0;
        if (!isset($data['width']))
            $data['width'] = 0.0;
        if (!isset($data['height']))
            $data['height'] = 0.0;
        if (!isset($data['depth']))
            $data['depth'] = 0.0;
        if (!isset($data['weight']))
            $data['weight'] = 0;
        $id = createProduct(
            $data['prefix'],
            $data['tag'],
            $data['actPrice'],
            $data['override'],
            $data['marginPercent'],
            $data['marginNumber'],
            $data['width'],
            $data['height'],
            $data['depth'],
            $data['weight']
        );
        foreach (array_filter($data['name']) as $k => $v){
            insertName($id, $k, $v);
        }
        if(isset($data['ean'])){
            foreach (array_filter($data['ean']) as $v){
                insertEAN($id, $v);
            }
        }
        if (isset($data['supplier']['name']) && $data['supplier']['name'] != "" &&
            isset($data['supplier']['url']) && $data['supplier']['url'] != "" &&
            isset($data['supplier']['price']) && $data['supplier']['price'] != "" &&
            isset($data['supplier']['sku']) && $data['supplier']['sku'] != "")
            insertSupplier($id, $data['supplier']['name'], $data['supplier']['url'], null, $data['supplier']['price'], $data['supplier']['sku']);
        insertCategory($id, $data['category']);
        setMainCategory($id, $data['category']);
        insertCarrier($id, 1, 1);
        insertCarrier($id, 2, 1);
        if (isset($data['supplier']['recPrice']) && $data['supplier']['recPrice'] != "")
            insertPlatform($id, 1, $data['supplier']['url'],  $data['supplier']['recPrice'], 0, 0);
        if (
            isset($data['supplier']['idTypeLocation']) && $data['supplier']['idTypeLocation'] != "" &&
            isset($data['supplier']['nameLocation']) && $data['supplier']['nameLocation'] != "" &&
            isset($data['supplier']['quantity']) && $data['supplier']['quantity'] != ""
        )
            insertLocation($id, $data['supplier']['idTypeLocation'], $data['supplier']['nameLocation'], $data['supplier']['quantity']);
        if (!isset($data['description']['ru']))
            $data['description']['ru'] = "";
        if (!isset($data['description']['en']))
            $data['description']['en'] = "";
        if (!isset($data['description']['et']))
            $data['description']['et'] = "";
        if (!isset($data['description']['lv']))
            $data['description']['lv'] = "";
        if (!isset($data['description']['lt']))
            $data['description']['lt'] = "";
        if (!isset($data['description']['FB']))
            $data['description']['FB'] = "";

        insertDescriptions(
            $id,
            $data['description']['ru'],
            $data['description']['en'],
            $data['description']['et'],
            $data['description']['lv'],
            $data['description']['lt'],
            $data['description']['FB'],
        );
        if (isset($data['images']))
            insertImages($id, $data['images'], "");

        PR_POST_Product($id);
        cacheProductNameBackground($id);
        exit(json_encode(array("success"=>"Successfully created product.")));
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "POST");
Route::add("/api/product", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "PUT");
Route::add("/api/product", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");

Route::add("/api/product/sync", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "GET");
Route::add("/api/product/sync", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "POST");
Route::add("/api/product/sync", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/prestashop/Products.php";
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            PR_PUT_Product($data['id']);
            exit(json_encode(array("success"=>"Product with id: ".$data['id']." synchronized.")));
        } else {
            exit(json_encode(array("error" => "No product id supplied.", "code"=>"1800")));
        }

    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }

    exit();
}, "PUT");
Route::add("/api/product/sync", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");