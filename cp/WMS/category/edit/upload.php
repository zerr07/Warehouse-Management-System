<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Category.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/generateURL.php');

session_start();
$catNameRU = $_POST['catNameRU'];
$catNameET = $_POST['catNameET'];
if ($_POST['cat'] == 'None'){
    $parent = 2;
} else {
    $parent = $_POST['cat'];
}

$urlET = get_ET_URL($catNameET);
$urlRU = get_ET_URL($catNameRU);
$date = date("Y-m-d H:i:s");
if (isset($_POST['enabled']) && $_POST['enabled'] == 'Yes'){
    $enabled = 1;
} else {
    $enabled = 0;
}

$id=$_POST['idEdit'];
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*categories*} 
                                        SET `parent`='$parent', enabled='$enabled' WHERE id='$id'"));

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*category_name*} WHERE id_category='$id'"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$id', '1', '$catNameRU')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$id', '3', '$catNameET')"));


PR_PUT_Category($id, $urlET, $urlRU);

header("Location: /cp/WMS/category/");