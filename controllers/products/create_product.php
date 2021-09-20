<?php
// Tag check
function check_tag($tag){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT count(*) as product_count FROM 
                                                                        {*products*} WHERE tag='$tag'"));
    $count = $q->fetch_assoc()['product_count'];
    if ($count == 0){
        return True;
    } else {
        return False;
    }
}

function createProduct($prefix, $itemTagID, $itemActPrice, $override, $marginPercent,
                       $marginNumber, $width, $height, $depth, $weight){
    while (True){
        if (check_tag($itemTagID)){
            break;
        } else {
            $tag = explode($prefix, $itemTagID);
            $tag = $tag[1]+1;
            while(strlen($tag) < 3){
                $tag = "0".$tag;
            }
            $itemTagID = $prefix.$tag;
        }
    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*products*}
                        (actPrice, tag, override, def_margin_percent, def_margin_number, width, height, depth, weight) 
                        VALUES ('$itemActPrice', '$itemTagID', '$override', '$marginPercent',
                                '$marginNumber', '$width', '$height', '$depth', '$weight')"));
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*products*}"));
    return $q->fetch_assoc()['id'];
}

function insertEAN($id, $ean){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_codes*}
                        (id_product, ean) VALUES ('$id', '$ean')"));
}
function insertCategory($id, $id_category){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_categories*}
                        (id_category, id_product) 
                        VALUES ('$id_category', '$id')"));
}
function insertMultipleCategories($id, $id_categories){
    foreach ($id_categories as $v){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_categories*}
                        (id_category, id_product) 
                        VALUES ('$v', '$id')"));
    }
}
function deleteCategory($id, $id_category){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_categories*} 
       WHERE id_category='$id_category' AND id_product='$id'"));
}

function setMainCategory($id, $id_category){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_categories*} 
                        SET `main`='1' WHERE id_category='$id_category' AND id_product='$id'"));
}
function insertName($id, $id_lang, $name){
    $name = htmlentities($name, ENT_QUOTES, 'UTF-8');
    if (is_numeric($id_lang)){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$name', '$id', '$id_lang')"));
    } else {
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO product_name 
            (id_product, id_lang, `name`) SELECT '$id', id, '$name' FROM languages WHERE lang='$id_lang'"));
    }
}
function updateName($id, $id_lang, $name){
    $name = htmlentities($name, ENT_QUOTES, 'UTF-8');
    if (is_numeric($id_lang)){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_name*}
                         SET `name`='$name' WHERE id_product='$id' AND id_lang='$id_lang'"));
    } else {
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_name*} SET `name`='$name'
            WHERE id_product='$id' AND id_lang=(SELECT id FROM languages WHERE lang='$id_lang')"));
    }
}
function insertSupplier($id ,$supplierName, $URL, $price, $priceVAT, $suppSKU) {
    if (is_null($price)){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*supplier_data*}
                                            (id_item, URL, priceVAT, supplierName, SKU) VALUES 
                                            ('$id', '$URL', '$priceVAT', '$supplierName', '$suppSKU')"));
    } else {
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*supplier_data*}
                                            (id_item, URL, price, priceVAT, supplierName, SKU) VALUES 
                                            ('$id', '$URL', '$price', '$priceVAT', '$supplierName', '$suppSKU')"));
    }

}
function insertCarrierCustom($id, $id_carrier, $price){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_custom*}
                                                    (id_carrier, id_product, price)
                                                    VALUES ('$id', '$id_carrier', '$price')"));
}
function insertCarrier($id, $id_carrier, $enabled){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_details*}
                                                                    (id_carrier, id_product, enabled)
                                                                    VALUES ('$id_carrier', '$id', '$enabled')"));
}
function insertProperties($id, $prop){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */
        "INSERT INTO {*product_properties*} (id_product, id_value) VALUES ('$id', '$prop')"));
}
function insertPlatform($id, $id_platform, $url, $price, $custom, $export){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_platforms*}
                                                    (id_item, id_platform, URL, price, custom, export) VALUES 
                                                    ('$id', '$id_platform', '$url', '$price', '$custom', '$export')"));
}
function insertLocation($id, $id_type, $name, $quantity){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_locations*}
                                                        (id_item, location, id_type, quantity)
                                                        VALUES ('$id', '$name', '$id_type', '$quantity')"));
}
function updateLocation($id, $id_type, $name, $quantity){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_locations*} SET location='$name', 
                                                            id_type='$id_type', quantity='$quantity' WHERE id='$id'"));
}
function insertDescriptions($id, $ru, $en, $et, $lv, $lt, $fb){
    $product = array("product" => array(
        'pl' => array("description" => htmlentities("", ENT_QUOTES)),
        'ru' => array("description" => htmlentities("\xEF\xBB\xBF".$ru, ENT_QUOTES)),
        'en' => array("description" => htmlentities("\xEF\xBB\xBF".$en, ENT_QUOTES)),
        'et' => array("description" => htmlentities("\xEF\xBB\xBF".$et, ENT_QUOTES)),
        'lv' => array("description" => htmlentities("\xEF\xBB\xBF".$lv, ENT_QUOTES)),
        'lt' => array("description" => htmlentities("\xEF\xBB\xBF".$lt, ENT_QUOTES)),
        'FB' => array("description" => htmlentities("\xEF\xBB\xBF".$fb, ENT_QUOTES))
    ));
    $json = json_encode($product);
    file_put_contents($_SERVER['DOCUMENT_ROOT']."/translations/products/$id.json", $json);
}
function UpdateDescriptions($id, $ru = null, $en = null, $et = null, $lv = null, $lt = null, $fb = null){
    $desc = get_desc($id);
    if (is_null($ru)){
        $ru = $desc['ru'];
    }
    if (is_null($ru)){
        $en = $desc['en'];
    }
    if (is_null($ru)){
        $et = $desc['et'];
    }
    if (is_null($ru)){
        $lv = $desc['lv'];
    }
    if (is_null($ru)){
        $lt = $desc['lt'];
    }
    if (is_null($ru)){
        $fb = get_FB_desc($id);
    }
    insertDescriptions($id, $ru, $en, $et, $lv, $lt, $fb);
}
function deleteImages($images, $id, $prefix){
    include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_products.php');
    if ($prefix == "_live"){
        $DBimages = get_images_live($id);
    } else {
        $DBimages = get_images($id);
    }
    foreach ($DBimages as $key => $value){
        if (!in_array("/uploads/images/products/".$value['image'], $images)){
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */"DELETE FROM {*product_images$prefix*} WHERE id='$key'"));
            unlink($_SERVER["DOCUMENT_ROOT"] . '/uploads/images/products/'.$value['image']);
        }
    }
}

function insertImages($id, $images, $prefix, $delete = true){
    $existImages = array();
    $position = 1;
    if (!empty($images)) {
        foreach ($images as $val) {
            if(mime_content_type($val[1])) {
                $extension = explode('/', mime_content_type($val[1]))[1];
                list($type, $val[1]) = explode(';', $val[1]);
                list(, $val[1]) = explode(',', $val[1]);
                $img = $val[1];
            } else {
                $extension = 'jpeg';
                $img = $val[1];
            }
            $value = base64_decode($img);

            $filename = $id . rand(1, 100000000000000) . "$prefix." . $extension;
            $name = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/products/' . $filename;

            while (True) {
                if (file_exists($name)) {
                    $filename = $id . rand(1, 100000000000000) . "." . $extension;
                    $name = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/products/' . $filename;
                } else {
                    break;
                }
            }
            file_put_contents($name, $value);
            array_push($existImages, '/uploads/images/products/' . $filename);
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*product_images$prefix*}
                                            (id_item, image, `position`) VALUES ('$id','$filename','$position')"));
            $position++;
        }
        if ($delete)
            deleteImages($existImages, $id, $prefix);
    }
}