<?php

$date = new DateTime();
$req_dump = print_r($_POST, TRUE);
$fp = fopen($_SERVER["DOCUMENT_ROOT"]."/dump/".$date->getTimestamp().'.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
function writeFile($file, $txt, $UploadFolder){
    $WriteFile = fopen($UploadFolder.$file, "wb");
    fwrite($WriteFile,$txt);
    fclose($WriteFile);
}
$actPrice = $_POST['itemActPrice'];
$itemTagID = $_POST['itemTagID'];
$catID = $_POST['cat'];
$itemID = $_POST['idEdit'];
$quantity = $_POST['itemQuantity'];


if (isset($_POST['override']) && $_POST['override'] == "Yes"){
    $override = 1;
} else {
    $override = 0;
}

$marginPercent = $_POST['itemMarginPercent'];
$marginNumber = $_POST['itemMarginNumber'];

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} SET 
        id_category='$catID', actPrice='$actPrice', tag='$itemTagID', quantity='$quantity', override='$override', 
        def_margin_percent='$marginPercent' , def_margin_number='$marginNumber' WHERE id='$itemID'"));

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_name*} WHERE id_product='$itemID'"));
$itemNameET = htmlentities($_POST['itemNameET'], ENT_QUOTES, 'UTF-8');
$itemNameRU = htmlentities($_POST['itemNameRU'], ENT_QUOTES, 'UTF-8');
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameET', '$itemID', '3')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*}
                        (`name`, id_product, id_lang) VALUES ('$itemNameRU', '$itemID', '1')"));
$last = $itemID;
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*supplier_data*} WHERE id_item='$last'"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_platforms*} WHERE id_item='$last'"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_locations*} WHERE id_item='$last'"));
if (isset($_POST['itemSupplierName'])) {
    for ($i = 0; $i < sizeof($_POST['itemSupplierName']); $i++) {
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

for ($i = 0; $i < sizeof($_POST['itemLocation']); $i++){
    $loc = $_POST['itemLocation'][$i];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_locations*}
                                                    (id_item, location)
                                                    VALUES ('$last', '$loc')"));}
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

mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "UPDATE {*product_images*}
                                            SET `primary`='0' WHERE id_item='$last'"));
$images = json_decode($_POST['imagesJSON'], true);
$existImages = array();
foreach ($images as $val) {
    if ($val[0] == "exist"){
        if ($val[2] == 1){
            $tmp = str_replace("/uploads/images/products/","", $val[1]);
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "UPDATE {*product_images*}
                                            SET `primary`='1' WHERE id_item='$last' AND image='$tmp'"));
        }
        array_push($existImages, $val[1]);
        continue;
    }
    $extension = explode('/', mime_content_type($val[1]))[1];
    list($type, $val[1]) = explode(';', $val[1]);
    list(, $val[1]) = explode(',', $val[1]);

    $value = base64_decode($val[1]);

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
    array_push($existImages, '/uploads/images/products/'.$filename);
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*product_images*}
                                            (id_item, image, `primary`) VALUES ('$last','$filename','$val[2]')"));

}
deleteImages($existImages, $last);


header("Location: /cp/WMS/");

?>