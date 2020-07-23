<?php
if (session_status() != PHP_SESSION_ACTIVE) {	    //Checks if session started or not
    ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
    ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
    session_start();
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
    }
}
