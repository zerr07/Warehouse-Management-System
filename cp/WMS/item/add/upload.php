<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/cache.php');
$date = new DateTime();
$data = $_POST;

include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/products/create_product.php');

include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Products.php');

session_start();

if (isset($_COOKIE['id_shard'])){
    $shard = $_COOKIE['id_shard'];
} else {
    $shard = _ENGINE['id_shard'];
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */"SELECT tag_prefix FROM {*shards*} WHERE id='$shard'"));
$prefix = $q->fetch_assoc()['tag_prefix'];


$itemActPrice = $data['itemActPrice'];
$itemTagID = $data['itemTagID'];
$catID = $data['cat'];
$main_cat = $data['catmain'];
if ($data['override'] == "Yes"){
    $override = 1;
} else {
    $override = 0;
}

$marginPercent = $data['itemMarginPercent'];
$marginNumber = $data['itemMarginNumber'];


$width = $data['width'];
$height = $data['height'];
$depth = $data['depth'];
$weight = $data['weight'];

$last = createProduct($prefix, $itemTagID, $itemActPrice, $override, $marginPercent, $marginNumber, $width, $height, $depth, $weight);
if (isset($data['itemEAN']) && $data['itemEAN'] != "") {
    insertEAN($last, $data['itemEAN']);
}

foreach ($data['cat'] as $value){
    insertCategory($last, $value);
}
if ($main_cat != ""){
    setMainCategory($last, $main_cat);
}

insertName($last, '1', $data['itemNameRU']);
insertName($last, '2', $data['itemNameEN']);
insertName($last, '3', $data['itemNameET']);
insertName($last, '4', $data['itemNameLV']);
insertName($last, '6', $data['itemNameLT']);

for ($i = 0; $i < sizeof($data['itemSupplierName']); $i++){
    $name = htmlentities($data['itemSupplierName'][$i], ENT_QUOTES, 'UTF-8');
    $url = $data['itemURL'][$i];
    $price = $data['itemPrice'][$i];
    $priceVAT = $data['itemPriceVAT'][$i];
    if (isset($data['itemSKU'][$i])){
        $suppSKU = htmlentities($data['itemSKU'][$i], ENT_QUOTES, 'UTF-8');
    } else {
        $suppSKU = "";
    }

    if ($url != "" || $name != "" || $price != "" || $priceVAT != "") {
        insertSupplier($last, $name, $url, $price, $priceVAT, $suppSKU);
    }
}
if (isset($data['customCarrier'])){
    foreach ($data['customCarrier'] as $key => $value){
        if (isset($data['customCarrier'][$key]) && $data['customCarrier'][$key] == "Yes"){
            $price = $data['carrierPrice'][$key];
            insertCarrierCustom($last, $key, $price);
        }
    }
}

if(isset($data['carrierEnabled'])){
    foreach ($data['carrierEnabled'] as $key => $value){
        if ($data['carrierEnabled'][$key] == "Yes") {
            insertCarrier($last, $key, 1);
        }
    }
}
if (isset($data['param_val'])){
    foreach ($data['param_val'] as $value){
        if ($value != "" && $value != "None"){
            insertProperties($last, $value);
        }
    }
}
foreach ($data['platformID'] as $key => $value){
    $PLid = $data['platformID'][$key];
    $PLurl = $data['platformURL'][$key];
    $PLprice = $data['platformPrice'][$key];
    if ($data['platformCustom'][$key] == "Yes"){
        $PLcustom = 1;
    } else {
        $PLcustom = 0;
    }
    if ($data['export'][$key] == "Yes"){
        $PLexport = 1;
    } else {
        $PLexport = 0;
    }
    if ($PLurl != "" || $PLprice != ""){
        insertPlatform($last, $PLid, $PLurl, $PLprice, $PLcustom, $PLexport);
    }
}
if (isset($data['itemLocationNew'])){
    foreach ($data['itemLocationNew'] as $key => $value){
        $quantity = $data['itemQuantityNew'][$key];
        $id_type = $data['loc_type_new'][$key];
        insertLocation($last, $id_type, $value, $quantity);
    }
}
if (isset($data['itemLocation'])) {
    foreach ($data['itemLocation'] as $key => $value) {
        $quantity = $data['itemQuantity'][$key];
        $id_type = $data['loc_type'][$key];
        updateLocation($id, $id_type, $value, $quantity);
    }
}

insertDescriptions($last, $data['RUS'], $data['ENG'], $data['EST'], $data['LV'], $data['LT'], $data['FB']);

/* upload images */
$images = json_decode($data['ImageUploader_imagesJSON'], true);
insertImages($last, $images, "");
/* upload images live */
$images = json_decode($data['ImageUploader_imagesJSON_live'], true);
insertImages($last, $images, "_live");

PR_POST_Product($last);
cacheProductNameBackground($last);


if (isset($data['request'])){
    exit(json_encode(array("status"=>$last)));
} else {
    header("Location: /cp/WMS/");
}

?>