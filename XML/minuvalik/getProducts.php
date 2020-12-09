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
                                                                                        WHERE username='$user' AND id_platform='11'"));
$shard = _ENGINE['id_shard'];

$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0){
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    header('Content-type: text/xml');
    $platform = $res['id_platform'];
    $coefficient_q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT profitMargin FROM {*platforms*} WHERE id='$platform'"));
    $coefficient = mysqli_fetch_assoc($coefficient_q)['profitMargin'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *, (SELECT URL FROM {*product_platforms*} WHERE id_platform='$platform' AND id_item={*products*}.id) as platform_url
        FROM {*products*} WHERE tag LIKE 'AZ%' AND id_shard='$shard' AND
        id IN (SELECT id_item FROM {*product_platforms*} WHERE id_platform='$platform' AND export='1')"));


    $xml = new XMLWriter();
    $xml->openURI("test.xml");
    $xml->startDocument('1.0', 'utf-8');
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument();
    $xml->startElement('SHOP');

    while ($row = mysqli_fetch_assoc($q)){
        if($row['tag'] != "" && $row['quantity'] != "" ) {
            $arr = read_result_single($row);

            $xml->startElement('PRODUCT');

            $xml->startElement('ID');
            $xml->text($row['platform_url']);
            $xml->endElement();

            $xml->startElement('PRODUCT_ID');
            $xml->text($arr['tag']);
            $xml->endElement();

            $xml->startElement('QNT');
            if($arr['quantity'] <= 0){
                $arr['quantity'] = 0;
            }
            $xml->text($arr['quantity']);
            $xml->endElement();

            $xml->startElement('PRICE');
            $xml->text($arr['platforms'][$platform]['price']);
            $xml->endElement();
            
            $xml->endElement();

        }
    }

    $xml->endElement();

    echo $xml->flush();


} else {
    exit("Username or password is incorrect");
}

