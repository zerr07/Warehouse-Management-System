<?php
if (! (isset($_COOKIE['Authenticated']))){
    header("Location: /");
} elseif ($_COOKIE['Authenticated'] != ""){
    setcookie("Authenticated", $_COOKIE['Authenticated'], time() + (86400 * 30), "/");
    setcookie("user_id", $_COOKIE['user_id'], time() + (86400 * 30), "/");
}