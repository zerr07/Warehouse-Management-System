<?php
if (session_status() != PHP_SESSION_ACTIVE) {	    //Checks if session started or not
    ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
    ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
    session_start();
}
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

if  (isset($_COOKIE['Authenticated']) && $_COOKIE['Authenticated'] != ""){
    if (isset($smarty)){
        $smarty->assign("user", $_COOKIE['Authenticated']);
        $smarty->assign("userID", $_COOKIE['user_id']);
        $smarty->assign("default_location_type", $_COOKIE['default_location_type']);

        getCart();
        $smarty->assign("cart", $_SESSION['cart']);
        $smarty->assign("cartTotal", $_SESSION['cartTotal']);

        $smarty->assign("shards", getShards());
        if (!isset($_COOKIE['access_token'])){
            $userId = $_COOKIE['user_id'];
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT access_token FROM {*users*} WHERE id='$userId'"));
            if ($q){
                $tok = $q->fetch_assoc()['access_token'];
                if (!is_null($tok)){
                    setcookie("access_token", $tok, time() + (86400 * 30), "/"); // 86400 = 1 day
                    //header("Refresh:0");
                } else {
                    $smarty->assign("access_token", $tok);
                }
            }
        } else {
            $smarty->assign("access_token", $_COOKIE['access_token']);
        }
    }


}
