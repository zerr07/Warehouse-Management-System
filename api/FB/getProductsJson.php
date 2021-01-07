<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/log.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/api/checkLogin.php');
$user = getUser();
$pass = getPass();
if ($pass == null || $user == null){
    exit("Username or password is not specified");
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0) {
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT tag FROM products"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        array_push($arr, $row['tag']);
    }
    $arr = array("tags"=>$arr);
    echo json_encode($arr);
} else {
    exit("Username or password is incorrect");
}