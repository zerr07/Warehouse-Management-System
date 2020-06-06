<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
$id = $_POST['catID'];
$platformID = $_POST['platformID'];
$platformCat = $_POST['platformCategory'];
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*category_platform*} 
                                        (id_category, id_platform, id_category_platform) VALUES ('$id', '$platformID', '$platformCat')"));