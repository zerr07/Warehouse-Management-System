<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
if (isset($_GET['id']) && isset($_GET['location'])){
    $id = $_GET['id'];
    $loc = $_GET['location'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_locations*} WHERE
                                          id_item='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_locations*}
                                          (id_item, location) VALUES ('$id', '$loc')"));
}