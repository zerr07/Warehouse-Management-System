<?php

$date = new DateTime();

$req_dump = print_r($_POST, TRUE);
$fp = fopen($_SERVER["DOCUMENT_ROOT"]."/dump/".$date->getTimestamp().'.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);
function writeFile($file, $txt, $UploadFolder){
    $WriteFile = fopen($UploadFolder.$file, "wb");
    fwrite($WriteFile,$txt);
    fclose($WriteFile);
}
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
session_start();

if (isset($_COOKIE['id_shard'])){
    $shard = $_COOKIE['id_shard'];
} else {
    $shard = _ENGINE['id_shard'];
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */"SELECT tag_prefix FROM {*shards*} WHERE id='$shard'"));
$prefix = $q->fetch_assoc()['tag_prefix'];


$itemActPrice = $_POST['itemActPrice'];
$itemTagID = $_POST['itemTagID'];
$catID = $_POST['cat'];
$quantity = $_POST['itemQuantity'];
if ($_POST['override'] == "Yes"){
    $override = 1;
} else {
    $override = 0;
}

$marginPercent = $_POST['itemMarginPercent'];
$marginNumber = $_POST['itemMarginNumber'];

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
                        (id_category, actPrice, tag, quantity, override, def_margin_percent, def_margin_number) 
                        VALUES ('$catID', '$itemActPrice', '$itemTagID', '$quantity', '$override', '$marginPercent', '$marginNumber')"));

$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*products*}"));
$last = $q->fetch_assoc()['id'];
if (isset($_POST['itemEAN']) && $_POST['itemEAN'] != "") {
    $ean = $_POST['itemEAN'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_codes*}
                        (id_product, ean) VALUES ('$last', '$ean')"));
}

$itemNameET = htmlentities($_POST['itemNameET'], ENT_QUOTES, 'UTF-8');
$itemNameRU = htmlentities($_POST['itemNameRU'], ENT_QUOTES, 'UTF-8');
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameET', '$last', '3')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameRU', '$last', '1')"));

for ($i = 0; $i < sizeof($_POST['itemSupplierName']); $i++){
    $name = htmlentities($_POST['itemSupplierName'][$i], ENT_QUOTES, 'UTF-8');
    $url = $_POST['itemURL'][$i];
    $price = $_POST['itemPrice'][$i];
    $priceVAT = $_POST['itemPriceVAT'][$i];
    if ($url != "" || $name != "" || $price != "" || $priceVAT != "") {
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*supplier_data*}
                                                    (id_item, URL, price, priceVAT, supplierName)
                                                    VALUES ('$last', '$url', '$price', '$priceVAT', '$name')"));
    }
}
if (isset($_POST['customCarrier'])){
    foreach ($_POST['customCarrier'] as $key => $value){
        if (isset($_POST['customCarrier'][$key]) && $_POST['customCarrier'][$key] == "Yes"){
            $price = $_POST['carrierPrice'][$key];
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_custom*}
                                                    (id_carrier, id_product, price)
                                                    VALUES ('$key', '$last', '$price')"));
        }
    }
}

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
foreach ($_POST['platformID'] as $key => $value){
    $PLid = $_POST['platformID'][$key];
    $PLurl = $_POST['platformURL'][$key];
    $PLprice = $_POST['platformPrice'][$key];
    if ($_POST['platformCustom'][$key] == "Yes"){
        $PLcustom = 1;
    } else {
        $PLcustom = 0;
    }
    if ($_POST['export'][$key] == "Yes"){
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

for ($i = 0; $i < sizeof($_POST['itemLocation']); $i++){
    $loc = $_POST['itemLocation'][$i];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_locations*}
                                                    (id_item, location)
                                                    VALUES ('$last', '$loc')"));
}
$plTXT = "\xEF\xBB\xBF".$_POST['PL'];
$ruTXT = "\xEF\xBB\xBF".$_POST['RUS'];
$enTXT = "\xEF\xBB\xBF".$_POST['ENG'];
$etTXT = "\xEF\xBB\xBF".$_POST['EST'];
$lvTXT = "\xEF\xBB\xBF".$_POST['LV'];
$product = array("product" => array(
    'pl' => array("description" => htmlentities($plTXT, ENT_QUOTES)),
    'ru' => array("description" => htmlentities($ruTXT, ENT_QUOTES)),
    'en' => array("description" => htmlentities($enTXT, ENT_QUOTES)),
    'et' => array("description" => htmlentities($etTXT, ENT_QUOTES)),
    'lv' => array("description" => htmlentities($lvTXT, ENT_QUOTES))
));
$json = json_encode($product);
file_put_contents($_SERVER['DOCUMENT_ROOT']."/translations/products/$last.json", $json);


function deleteImages($images, $id){
    include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_products.php');
    $DBimages = get_images($id);
    foreach ($DBimages as $key => $value){
        if (!in_array("/uploads/images/products/".$value['image'], $images)){
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */"DELETE FROM {*product_images*} WHERE id='$key'"));
            unlink($_SERVER["DOCUMENT_ROOT"] . '/uploads/images/products/'.$value['image']);
        }
    }
}

$images = json_decode($_POST['imagesJSON'], true);
$existImages = array();
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
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*product_images*}
                                            (id_item, image, `primary`) VALUES ('$last','$filename','$val[2]')"));

    }
    deleteImages($existImages, $last);
}


header("Location: /cp/WMS/");
?>