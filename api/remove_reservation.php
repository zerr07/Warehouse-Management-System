<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

if (isset($_GET['username']) && isset($_GET['password'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $id = $_GET['id'];
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $id = $_POST['id'];
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0) {
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    include_once ($_SERVER['DOCUMENT_ROOT']."/cp/POS/reserve/reserve.php");
    cancelReservationFull($id);
} else {
    exit("Username or password is incorrect");
}