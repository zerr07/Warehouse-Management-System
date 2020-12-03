<?php
if (! (isset($_COOKIE['Authenticated']))){
    header("Location: /");
} elseif ($_COOKIE['Authenticated'] != ""){
    setcookie("Authenticated", $_COOKIE['Authenticated'], time() + (86400 * 30), "/");
    setcookie("user_id", $_COOKIE['user_id'], time() + (86400 * 30), "/");
    setcookie("default_location_type", $_COOKIE['default_location_type'], time() + (86400 * 30), "/");
    $actual_link = "$_SERVER[REQUEST_URI]";
    $allowed = array("/", "/cp/FB/auctions/?logout", "/cp/FB/auctions/");
    if ($_COOKIE['user_id'] == "9"){
        //if (!in_array($actual_link, $allowed)){
            echo "This endpoint is not allowed for this user. <a href='/cp/FB/auctions/'>Go here</a>";
            exit("");
        //}
    }
}