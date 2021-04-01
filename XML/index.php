<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_location_types.php');

function setXMLError($platform, $product, $msg=""){
    deleteXMLError($platform, $product);
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*XML_export_error*} 
        (id_product, id_platform, msg) VALUES ('$product', '$platform', '$msg')"));
}
function deleteXMLError($platform, $product){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*XML_export_error*} WHERE id_product='$product' AND id_platform='$platform'"));

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
    if ($res['profile'] != ""){
        include_once $_SERVER['DOCUMENT_ROOT']."/XML/profiles/".$res['profile'];
        if (isset($_GET['products'])){
            get_products_xml();
        }
        if(isset($_GET['getProducts'])){
            download_products();
        }
        if (isset($_GET['stock'])){
            get_stock_xml();
        }
        if(isset($_GET['getStock'])){
            download_stock();
        }
    }

} else {
    exit("Username or password is incorrect");
}
