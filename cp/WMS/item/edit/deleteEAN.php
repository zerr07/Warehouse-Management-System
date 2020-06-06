<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
$id = $_POST['index'];
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_codes*} WHERE id='$id'"));