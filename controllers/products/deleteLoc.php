<?php

include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

function delete_location($id)
{
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_locations*} WHERE id='$id'"));
}

if (isset($_GET['id'])) {
    delete_location($_GET['id']);
}