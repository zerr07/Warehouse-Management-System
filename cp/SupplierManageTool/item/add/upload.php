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
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
session_start();


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
foreach ($_POST['customCarrier'] as $key => $value){
    if (isset($_POST['customCarrier'][$key]) && $_POST['customCarrier'][$key] == "Yes"){
        $price = $_POST['carrierPrice'][$key];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_custom*}
                                                    (id_carrier, id_product, price)
                                                    VALUES ('$key', '$last', '$price')"));
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

$UploadFolder = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products";
$counter = 0;
$errors = array();
$uploadedFiles = array();
$extension = array("jpeg","jpg","PNG","gif", "png");
$list = "";
foreach($_FILES["image"]["tmp_name"] as $key=>$tmp_name){
    $temp = $_FILES["image"]["tmp_name"][$key];
    $name = $_FILES["image"]["name"][$key];

    if(empty($temp))
    {
        continue;
    }

    $counter++;
    $UploadOk = true;

    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $name = $last."img_".$counter.'.'.$ext;
    if(in_array($ext, $extension) == false){
        echo $ext;
        $UploadOk = false;
        echo "Not valid extension";

    }

    if(file_exists($UploadFolder."/".$name) == true){
        $UploadOk = false;
        echo "File already exists";
    }

    if($UploadOk == true){
        move_uploaded_file($temp,$UploadFolder."/".$name);
        array_push($uploadedFiles, $name);
    }
}
if($counter>0){

    if(count($uploadedFiles)>0){
        echo "<b>Uploaded Files:</b>";
        echo "<br/><ul>";
        $c = 0;
        foreach($uploadedFiles as $fileName)
        {
            echo "<li>".$fileName."</li>";

            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_images*}
                                                    (id_item, image, `primary`) VALUES ('$last', '$fileName', '0')"));


            $c++;
        }

        echo "</ul><br/>";

        echo count($uploadedFiles)." file(s) are successfully uploaded.";
    }
}
if(isset($_POST['defaultImage'])){
    $primaryImg = $_POST['defaultImage'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_images*} SET `primary`=1
                                                    WHERE `image`='$primaryImg'"));
}


header("Location: /cp/SupplierManageTool/");
?>