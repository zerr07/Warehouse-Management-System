<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

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
$urlRU = preg_replace("/[^a-zA-Z]/", "", $urlRU);
$urlRU = preg_replace("/[^a-zA-Z]/", "", $urlRU);
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


$GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*ps_category*} 
                SET id_parent='$parent', active='$enabled', date_upd='$date' WHERE id_category='$id'"));

$GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*ps_category_lang*} WHERE id_category='$id'"));
$GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*ps_category_lang*} 
        (id_category, id_shop, id_lang, `name`, link_rewrite) VALUES ('$id', '1', '1', '$catNameET', '$urlET')"));
$GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*ps_category_lang*} 
        (id_category, id_shop, id_lang, `name`, link_rewrite) VALUES ('$id', '1', '2', '$catNameET', '$urlET')"));
$GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*ps_category_lang*} 
        (id_category, id_shop, id_lang, `name`, link_rewrite) VALUES ('$id', '1', '3', '$catNameRU', '$urlRU')"));
header("Location: /cp/WMS/category/");