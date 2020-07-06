<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
if (isset($_GET['username']) && isset($_GET['password'])){
    $user = $_GET['username'];
    $pass = $_GET['password'];
}

if (isset($_POST['username']) && isset($_POST['password'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*XML_users*} 
                                                                                        WHERE username='$user'"));

$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0){
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    header('Content-type: text/xml');

    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, quantity, tag,
     (SELECT priceVAT FROM {*supplier_data*} WHERE {*products*}.id={*supplier_data*}.id_item LIMIT 1) as priceVAT,
     (SELECT `name` FROM {*product_name*} WHERE {*products*}.id={*product_name*}.id_product AND id_lang=3) as nameET,
     (SELECT `name` FROM {*product_name*} WHERE {*products*}.id={*product_name*}.id_product AND id_lang=1) as nameRU,
     (SELECT location FROM {*product_locations*} WHERE {*products*}.id={*product_locations*}.id_item LIMIT 1) as location
     FROM {*products*}"));


    $xml = new XMLWriter();
    $xml->openURI("test.xml");
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument();
    $xml->startElement('products');

    while ($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        if($row['tag'] != "" && $row['quantity'] != "" ) {

            $xml->startElement('product');
            $xml->writeAttribute("SKU", $row['tag']);

            $xml->startElement('name_et');
            $xml->writeCdata(html_entity_decode($row['nameET'], ENT_QUOTES, "UTF-8"));
            $xml->endElement();

            $xml->startElement('name_ru');
            $xml->writeCdata(html_entity_decode($row['nameRU'], ENT_QUOTES, "UTF-8"));
            $xml->endElement();

            $xml->startElement('supplier_price');
            $xml->text($row['priceVAT']);
            $xml->endElement();

            $xml->startElement('SKU');
            $xml->text($row['tag']);
            $xml->endElement();

            $xml->startElement('quantity');
            $xml->text($row['quantity']);
            $xml->endElement();

            $xml->startElement('location');
            $xml->text($row['location']);
            $xml->endElement();

            $code = "";
            $queryCode = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_codes*} WHERE id_product='$id' LIMIT 1"));
            while ($rowCode = mysqli_fetch_assoc($queryCode)){
                $code = $rowCode['ean'];
            }

            $xml->startElement('EAN');
            $xml->text($code);
            $xml->endElement();

            $xml->endElement();

        }
    }

    $xml->endElement();

    echo $xml->flush();


} else {
    exit("Username or password is incorrect");
}

