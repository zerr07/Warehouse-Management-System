<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (isset($_GET['id']) && isset($_GET['location'])){
    $id = $_GET['id'];
    $loc = $_GET['location'];
    if (isset($_COOKIE['default_location_type'])){
        $id_type = $_COOKIE['default_location_type'];
    } else {
        $id_type = 1;
    }

    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_locations*}
                                          (id_item, location, id_type) VALUES ('$id', '$loc', '$id_type')"));
}