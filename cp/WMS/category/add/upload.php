<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Category.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/generateURL.php');


session_start();
$catNameRU = $_POST['catNameRU'];
$catNameET = $_POST['catNameET'];
$catNameEN = $_POST['catNameEN'];
$catNameLV = $_POST['catNameLV'];
$catNameLT = $_POST['catNameLT'];
if ($_POST['cat'] == 'None'){
    $parent = 2;
} else {
    $parent = $_POST['cat'];
}
$urlEN = get_EN_URL($catNameEN);
$urlET = get_ET_URL($catNameET);
$urlRU = get_RU_URL($catNameRU);
$date = date("Y-m-d H:i:s");
$enabled = 1;
$last = PR_POST_Category($parent, $catNameET, $catNameRU, $catNameEN, $enabled, $urlET, $urlRU, $urlEN);

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*categories*} (id, `parent`, enabled) 
                                                                                           VALUES ('$last','$parent', '$enabled')"));

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '1', '$catNameRU')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '2', '$catNameEN')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '3', '$catNameET')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '4', '$catNameLV')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '6', '$catNameLT')"));
if (isset($_POST['request'])){
    exit(json_encode(array("status"=>$last)));
} else {
    header("Location: /cp/WMS/category/");
}





