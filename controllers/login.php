<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/configs/config.php');
if (isset($_POST['username']) && isset($_POST['password'])){
    $user = $_POST['username'];
    $check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
    $res = mysqli_fetch_assoc($check);
    if (mysqli_num_rows($check) == 0){
        header("Location: /?code=101");
    } elseif (password_verify($_POST['password'], $res['password'])) {
        $id = $res['id'];
        setcookie("Authenticated", "$user", time() + (86400 * 30), "/");
        setcookie("user_id", "$id", time() + (86400 * 30), "/");
        header("Location: /cp");
    } else {
        header("Location: /?code=101");
    }
}