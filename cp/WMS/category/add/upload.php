<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
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


define('DEBUG', false);

define('_PS_DEBUG_SQL_', false);

define('PS_SHOP_PATH', 'http://bigshop.ee/');

define('PS_WS_AUTH_KEY', '***REMOVED***');

require_once($_SERVER['DOCUMENT_ROOT'].'/PSWebServiceLibrary.php');


$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, False);
$xml = $webService->get(array('url' => PS_SHOP_PATH . '/api/categories?schema=synopsis'));

$resources = $xml->children()->children();
unset($resources->id);
unset($resources->position);
unset($resources->date_add);
unset($resources->date_upd);
//unset($resources ->id_parent); //if unset category will be root. If set it must have id existing parent category!
unset($resources->level_depth);
unset($resources->nb_products_recursive);
$resources->id_parent = $parent;
$resources->name->language[0][0] = $catNameET;
$resources->link_rewrite->language[0][0] = $urlET;
$resources->name->language[1][3] = $catNameRU;
$resources->link_rewrite->language[1][3] = $urlRU;
$resources->active = $enabled;
$resources->id_shop_default = 1;
$resources->is_root_category = 0;

$opt = array('resource' => 'categories');

$opt['postXml'] = $xml -> asXML();

$xml = $webService -> add($opt);

$q = $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "SELECT MAX(id_category) as id FROM {*ps_category*}"));
$last = $q->fetch_assoc()['id'];
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*categories*} (id, `parent`, enabled) 
                                                                                           VALUES ('$last','$parent', '$enabled')"));

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '1', '$catNameRU')"));
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO  {*category_name*} (id_category, id_lang, `name`) 
                                                                            VALUES ('$last', '3', '$catNameET')"));

header("Location: /cp/WMS/");




