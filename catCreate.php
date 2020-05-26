<?php



define('DEBUG', true);

define('_PS_DEBUG_SQL_', true);

define('PS_SHOP_PATH', 'http://bigshop.ee/');

define('PS_WS_AUTH_KEY', '***REMOVED***');



ini_set('display_errors','on');

require_once('PSWebServiceLibrary.php');



$webService= new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
$id = 167;
$timestamp_debut = microtime(true);
$xml = $webService->get(array('resource' => 'categories', 'id' => $id));
$category = $xml->children()->children();

unset($category->level_depth);
unset($category->nb_products_recursive);
$category->name ->language[0][0] = $category->name ->language[0][0];
$category->link_rewrite->language[0][0] = $category->link_rewrite->language[0][0];
$category->name ->language[1][3] = $category->name ->language[1][3];
$category->link_rewrite->language[1][3] = $category->link_rewrite->language[1][3];
$category->id_shop_default = 1;
$category->is_root_category = 0;
$category->active = 1;
$opt['putXml'] = $xml->asXML();
$opt['id'] = $id;
$opt['resource'] = 'categories';
$xml = $webService->edit($opt);
echo "DONE";