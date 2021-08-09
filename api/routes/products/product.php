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

        insertSupplier($id, $data['supplier']['name'], $data['supplier']['url'], null, $data['supplier']['price'], $data['supplier']['sku']);
        insertCategory($id, $data['category']);
        setMainCategory($id, $data['category']);
        insertCarrier($id, 1, 1);
        insertCarrier($id, 2, 1);
        insertPlatform($id, 1, $data['supplier']['url'],  $data['supplier']['recPrice'], 0, 0);
        insertLocation($id, $data['supplier']['idTypeLocation'], $data['supplier']['nameLocation'], $data['supplier']['quantity']);
        insertDescriptions(
            $id,
            $data['description']['ru'],
            $data['description']['en'],
            $data['description']['et'],
            $data['description']['lv'],
            $data['description']['lt'],
            $data['description']['FB'],
        );

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