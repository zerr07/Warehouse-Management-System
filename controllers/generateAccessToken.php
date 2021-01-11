<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

$p = new OAuthProvider();

$t = bin2hex($p->generateToken(36));
if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != ""){
    while (true){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT access_token FROM {*users*} WHERE access_token='$t'"));
        if ($q->num_rows == 0){
            $id = $_COOKIE['user_id'];
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*users*} SET access_token='$t' WHERE id='$id'"));
            setcookie("access_token", $t, time() + (86400 * 30), "/"); // 86400 = 1 day
            break;
        } else {
            $t = bin2hex($p->generateToken(36));
        }
    }
    exit(json_encode(array("token"=>$t)));
}
exit(json_encode(array("error"=>"Error during token generation.")));
