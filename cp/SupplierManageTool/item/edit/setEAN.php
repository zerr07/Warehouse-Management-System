<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
$id = $_POST['prodID'];
$ean = $_POST['EAN'];
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_codes*} 
                                                                        (id_product, ean) VALUES ('$id', '$ean')"));