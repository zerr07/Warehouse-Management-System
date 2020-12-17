<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Category.php');

function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}
session_start();
$toReplace = array("'", "\"");
$catNameRU = $_POST['catNameRU'];
$catNameET = $_POST['catNameET'];
if ($_POST['cat'] == 'None'){
    $parent = 2;
} else {
    $parent = $_POST['cat'];
}

$urlRU = str_replace($toReplace, "", $catNameRU);
$urlET = str_replace($toReplace, "", $catNameET);
$urlRU = translit(htmlentities(str_replace(" ", "-", $urlRU), ENT_QUOTES, 'UTF-8'));
$urlET = translit(htmlentities(str_replace(" ", "-", $urlET), ENT_QUOTES, 'UTF-8'));
$urlET = preg_replace("/[^a-zA-Z]/", "", $urlET);
$urlRU = preg_replace("/[^a-zA-Z]/", "", $urlRU);
$date = date("Y-m-d H:i:s");
$enabled = 1;
$last = PR_POST_Category($parent, $catNameET, $catNameRU, $enabled, $urlET, $urlRU);

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*categories*} (id, `parent`, enabled) 
                                                                                           VALUES ('$last','$parent', '$enabled')"));

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '1', '$catNameRU')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '3', '$catNameET')"));


header("Location: /cp/WMS/category/");




