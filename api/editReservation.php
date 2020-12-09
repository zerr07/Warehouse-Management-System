<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/log.php');

if (!defined('PRODUCTS_INCLUDED')) {
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
if (isset($_GET['username']) && isset($_GET['password'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $data = json_decode($_GET['data'], true);
    sys_log(array("GET" => $_GET, "OTHERDATA" => $data));

}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $data = json_decode($_POST['data'], true);
    sys_log(array("POST" => $_POST, "OTHERDATA" => $data));

}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0) {
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */

    include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
    if (isset($_GET['comment'])){
        $comment = $_GET['comment'];
    } else {
        $comment = $_POST['comment'];
    }
    if (isset($_GET['id'])){
        $id = $_GET['id'];
    } else {
        $id = $_POST['id'];
    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved*} SET `comment`='$comment' WHERE id='$id'"));
    exit();
} else {
    exit("Username or password is incorrect");
}
