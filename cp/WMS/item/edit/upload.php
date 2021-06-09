<?php
$date = new DateTime();
$req_dump = print_r($_POST, TRUE);
$fp = fopen($_SERVER["DOCUMENT_ROOT"]."/dump/".$date->getTimestamp().'.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Products.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/cache.php');

function writeFile($file, $txt, $UploadFolder){
    $WriteFile = fopen($UploadFolder.$file, "wb");
    fwrite($WriteFile,$txt);
    fclose($WriteFile);
}
$actPrice = $_POST['itemActPrice'];
$itemTagID = $_POST['itemTagID'];
$itemID = $_POST['idEdit'];
$main_cat = $_POST['catmain'];

$width = $_POST['width'];
$height = $_POST['height'];
$depth = $_POST['depth'];
$weight = $_POST['weight'];
if ($width == ""){
    $width = 0.00;
}
if ($height == ""){
    $height = 0.00;
}
if ($depth == ""){
    $depth = 0.00;
}
if ($weight == ""){
    $weight = 0.00;
}

if (isset($_POST['override']) && $_POST['override'] == "Yes"){
    $override = 1;
} else {
    $override = 0;
}

$marginPercent = $_POST['itemMarginPercent'];
$marginNumber = $_POST['itemMarginNumber'];

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} SET 
        actPrice='$actPrice', tag='$itemTagID', override='$override', 
        def_margin_percent='$marginPercent' , def_margin_number='$marginNumber', width='$width', height='$height', depth='$depth', weight='$weight' WHERE id='$itemID'"));

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_categories*} WHERE id_product='$itemID'"));

foreach ($_POST['cat'] as $value){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_categories*}
                        (id_category, id_product) 
                        VALUES ('$value', '$itemID')"));
}
if ($main_cat != ""){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_categories*} SET `main`='0' WHERE AND id_product='$itemID'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_categories*} SET `main`='1' WHERE id_category='$main_cat' AND id_product='$itemID'"));
}
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_name*} WHERE id_product='$itemID'"));
$itemNameET = htmlentities($_POST['itemNameET'], ENT_QUOTES, 'UTF-8');
$itemNameEN = htmlentities($_POST['itemNameEN'], ENT_QUOTES, 'UTF-8');
$itemNameRU = htmlentities($_POST['itemNameRU'], ENT_QUOTES, 'UTF-8');
$itemNameLV = htmlentities($_POST['itemNameLV'], ENT_QUOTES, 'UTF-8');
$itemNameLT = htmlentities($_POST['itemNameLT'], ENT_QUOTES, 'UTF-8');
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameLT', '$itemID', '6')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameLV', '$itemID', '4')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameET', '$itemID', '3')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameEN', '$itemID', '2')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameRU', '$itemID', '1')"));
$last = $itemID;
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*supplier_data*} WHERE id_item='$last'"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_platforms*} WHERE id_item='$last'"));
if (isset($_POST['itemSupplierName'])) {
    for ($i = 0; $i < sizeof($_POST['itemSupplierName']); $i++) {
        $name = htmlentities($_POST['itemSupplierName'][$i], ENT_QUOTES, 'UTF-8');
        $url = $_POST['itemURL'][$i];
        $price = $_POST['itemPrice'][$i];
        $priceVAT = $_POST['itemPriceVAT'][$i];
        if (isset($_POST['itemSKU'][$i])){
            $suppSKU = htmlentities($_POST['itemSKU'][$i], ENT_QUOTES, 'UTF-8');
        } else {
            $suppSKU = "";
        }
        if ($url != "" || $name != "" || $price != "" || $priceVAT != "") {
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*supplier_data*}
                                                    (id_item, URL, price, priceVAT, supplierName, SKU)
                                                    VALUES ('$last', '$url', '$price', '$priceVAT', '$name', '$suppSKU')"));
        }
    }
}
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*carrier_custom*} WHERE id_product='$last'"));
if(isset($_POST['customCarrier'])){
    foreach ($_POST['customCarrier'] as $key => $value){
        if ($_POST['customCarrier'][$key] == "Yes"){
            $price = $_POST['carrierPrice'][$key];
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_custom*}
                                                    (id_carrier, id_product, price)
                                                    VALUES ('$key', '$last', '$price')"));
        }
    }
}
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*carrier_details*} WHERE id_product='$last'"));

if(isset($_POST['carrierEnabled'])){
    foreach ($_POST['carrierEnabled'] as $key => $value){
        if ($_POST['carrierEnabled'][$key] == "Yes") {
            $enabled = 1;
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_details*}
                                                                    (id_carrier, id_product, enabled)
                                                                    VALUES ('$key', '$last', '$enabled')"));
        }
    }
}

if (isset($_POST['param_val'])){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_properties*} WHERE id_product='$last'"));

    foreach ($_POST['param_val'] as $value){
        if ($value != "" && $value != "None"){
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_properties*} (id_product, id_value) VALUES ('$last', '$value')"));
        }
    }
}

foreach ($_POST['platformID'] as $key => $value){
    $PLid = $_POST['platformID'][$key];
    $PLurl = $_POST['platformURL'][$key];
    $PLprice = $_POST['platformPrice'][$key];
    if (isset($_POST['platformCustom'][$key]) && $_POST['platformCustom'][$key] == "Yes"){
        $PLcustom = 1;
    } else {
        $PLcustom = 0;
    }

    if (isset($_POST['export'][$key]) && $_POST['export'][$key] == "Yes") {
        $PLexport = 1;
    } else {
        $PLexport = 0;
    }

    if ($PLurl != "" || $PLprice != ""){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_platforms*}
                                                    (id_item, id_platform, URL, price, custom, export)
                                                    VALUES ('$last', '$PLid', '$PLurl', '$PLprice', '$PLcustom', '$PLexport')"));
    }
}

if (isset($_POST['itemLocationNew'])){
    foreach ($_POST['itemLocationNew'] as $key => $value){
        $quantity = $_POST['itemQuantityNew'][$key];
        $id_type = $_POST['loc_type_new'][$key];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_locations*}
                                                        (id_item, location, id_type, quantity)
                                                        VALUES ('$last', '$value', '$id_type', '$quantity')"));
    }
}
if (isset($_POST['itemLocation'])) {
    foreach ($_POST['itemLocation'] as $key => $value) {
        $quantity = $_POST['itemQuantity'][$key];
        $id_type = $_POST['loc_type'][$key];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_locations*} SET location='$value', 
    id_type='$id_type', quantity='$quantity' WHERE id='$key'"));
    }
}

$plTXT = "\xEF\xBB\xBF".$_POST['PL'];
$ruTXT = "\xEF\xBB\xBF".$_POST['RUS'];
$enTXT = "\xEF\xBB\xBF".$_POST['ENG'];
$etTXT = "\xEF\xBB\xBF".$_POST['EST'];
$lvTXT = "\xEF\xBB\xBF".$_POST['LV'];
$ltTXT = "\xEF\xBB\xBF".$_POST['LT'];
$FBTXT = "\xEF\xBB\xBF".$_POST['FB'];

$product = array("product" => array(
    'pl' => array("description" => htmlentities($plTXT, ENT_QUOTES)),
    'ru' => array("description" => htmlentities($ruTXT, ENT_QUOTES)),
    'en' => array("description" => htmlentities($enTXT, ENT_QUOTES)),
    'et' => array("description" => htmlentities($etTXT, ENT_QUOTES)),
    'lv' => array("description" => htmlentities($lvTXT, ENT_QUOTES)),
    'lt' => array("description" => htmlentities($ltTXT, ENT_QUOTES)),
    'FB' => array("description" => htmlentities($FBTXT, ENT_QUOTES))
));
$json = json_encode($product);
file_put_contents($_SERVER['DOCUMENT_ROOT']."/translations/products/$last.json", $json);



mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "UPDATE {*product_images*}
                                            SET `primary`='0' WHERE id_item='$last'"));

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
/* upload images */
$images = json_decode($_POST['ImageUploader_imagesJSON'], true);
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_images*} SET position='0' WHERE id_item='$last'"));

$existImages = array();
$counter = 1;

$id_prestashop = PR_GET_Product_By_Tag($itemTagID)['products'][0]['id'];

if (!empty($images)) {
    foreach ($images as $val) {
        if ($val[0] == "exist"){
            $tmp = str_replace("/uploads/images/products/","", $val[1]);
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "UPDATE {*product_images*}
                SET `position`='$counter' WHERE id_item='$last' AND image='$tmp'"));
            $counter++;
            array_push($existImages, $val[1]);
            continue;
        }
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

        $filename = $last . rand(1, 100000000000000) . "." . $extension;
        $name = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/products/' . $filename;

        while (True) {
            if (file_exists($name)) {
                $filename = $last . rand(1, 100000000000000) . "." . $extension;
                $name = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/products/' . $filename;
            } else {
                break;
            }
        }
        file_put_contents($name, $value);
        array_push($existImages, '/uploads/images/products/' . $filename);
        $res = PR_POST_Product_Image($id_prestashop, $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$filename, false);
        $id_img_prestashop = (string) simplexml_load_string($res)->image->id;
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*product_images*}
                                            (id_item, image, `position`, id_prestashop) VALUES ('$last','$filename','$counter', '$id_img_prestashop')"));


        $counter++;
    }
    $q_img_del = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_images*} WHERE id_item='$last'
        AND position='0'"));
    while ($row = $q_img_del->fetch_assoc()){
        $id_img_prestashop = $row['id_prestashop'];
        PR_DELETE_Product_Image($id_prestashop, $id_img_prestashop);
    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_images*} WHERE id_item='$last'
        AND position='0'"));
    //deleteImages($existImages, $last, "");
}
/* upload images live */
$images = json_decode($_POST['ImageUploader_imagesJSON_live'], true);
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_images_live*} SET position='0' WHERE id_item='$last'"));
$existImages = array();
$counter = 1;

if (!empty($images)) {
    foreach ($images as $val) {
        if ($val[0] == "exist"){
           $tmp = str_replace("/uploads/images/products/","", $val[1]);
           mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "UPDATE {*product_images_live*}
                                            SET `position`='$counter' WHERE id_item='$last' AND image='$tmp'"));

            array_push($existImages, $val[1]);
            $counter++;
            continue;
        }
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

        $filename = $last . rand(1, 100000000000000) . "_live." . $extension;
        $name = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/products/' . $filename;

        while (True) {
            if (file_exists($name)) {
                $filename = $last . rand(1, 100000000000000) . "." . $extension;
                $name = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/products/' . $filename;
            } else {
                break;
            }
        }
        file_put_contents($name, $value);
        array_push($existImages, '/uploads/images/products/' . $filename);
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*product_images_live*}
                                            (id_item, image, `position`) VALUES ('$last','$filename','$counter')"));
        $counter++;

    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_images_live*} WHERE id_item='$last'
        AND position='0'"));
    //deleteImages($existImages, $last, "_live");
}
PR_PUT_Product($last, true);

cacheProductNameBackground($last);
//exit("Redirect temporarily disabled, product saved though");

header("Location: /cp/WMS/");

?>