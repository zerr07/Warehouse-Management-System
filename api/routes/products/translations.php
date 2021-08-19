<?php
Route::add("/api/product/translations", function () {
    $check = json_decode(checkToken(), true);
    if (isset($check['user_id'])) {
        if (
            (isset($_GET['ean']) && $_GET['ean'] != "")
            ||
            (isset($_GET['tag']) && $_GET['tag'] != "")
        ){
            if (isset($_GET['ean']) && $_GET['ean'] != "") {
                $prod = get_product_by_ean($_GET['ean']);
            }
            if (isset($_GET['tag']) && $_GET['tag'] != "") {
                $prod = get_product_by_tag($_GET['tag']);
            }
            if (is_null($prod))
                exit(json_encode(array("error" => "No product found", "code"=>"1601")));
            exit(
                json_encode(
                    array(
                        "name" => $prod['name'],
                        "description" => $prod['descriptions']
                    )
                )
            );
        } else {
            exit(json_encode(array("error" => "No product identifier", "code"=>"1600")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "GET");
Route::add("/api/product/translations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "POST");
Route::add("/api/product/translations", function () {
    include_once $_SERVER['DOCUMENT_ROOT']."/controllers/getLanguages.php";
    $check = json_decode(checkToken(), true);
    $languages = getLanguagesAsName();
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($check['user_id'])) {
        if (
            (isset($data['ean']) && $data['ean'] != "")
            ||
            (isset($data['tag']) && $data['tag'] != "")
        ){
            if (isset($data['ean']) && $data['ean'] != "") {
                $prod = get_product_by_ean($data['ean']);
            }
            if (isset($data['tag']) && $data['tag'] != "") {
                $prod = get_product_by_tag($data['tag']);
            }
            if (is_null($prod))
                exit(json_encode(array("error" => "No product found", "code"=>"1701")));
            $id = $prod['id'];
            foreach ($data['name'] as $k => $v) {
                $prod['name'][$k] = $v;
                $idLang = $languages[$k];
                $name = htmlentities($v, ENT_QUOTES, 'UTF-8');
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$name', '$id', '$idLang')"));
            }
            $arr = array("product"=>array());
            foreach ($prod['descriptions'] as $k => $v) {
                $arr['product'][$k] = array("description" => htmlentities("\xEF\xBB\xBF".$v, ENT_QUOTES));
            }
            foreach ($data['description'] as $k => $v) {
                $arr['product'][$k] = array("description" => htmlentities("\xEF\xBB\xBF".$v, ENT_QUOTES));
            }
            $json = json_encode($arr);
            file_put_contents($_SERVER['DOCUMENT_ROOT']."/translations/products/$id.json", $json);
            exit(json_encode(array("success" => "Product with id ".$id." updated")));
        } else {
            exit(json_encode(array("error" => "No product identifier", "code"=>"1700")));
        }
    } else if (isset($check['error'])) {
        exit(json_encode($check));
    } else {
        exit(json_encode(array("error" => "Unknown error", "code"=>"100")));
    }
}, "PUT");
Route::add("/api/product/translations", function () {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}, "DELETE");