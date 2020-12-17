<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/categories/get_categories.php');
$xml = new XMLWriter();
if (isset($_GET['id'])){
    $id = $_GET['id'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id_product FROM {*XML_lists*} WHERE id_platform='$id'"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        $arr[$row['id_product']] = $row['id_product'];
    }
    $mappings = get_mappings($id);
    $profile = get_profile($id);
    $xml->openURI("test.xml");
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument();
    processXMLTree($profile, "", get_products_from_array(1, array_filter($arr)), $id);
}



function processXMLTree($profile, $parent, $products, $platform){
    global $xml;
    if (!empty($profile)){
        foreach ($profile as $key => $value){
            $tag = parseTagName($key);
            if ($key == 'ParentTag'){
                processXMLProduct($tag, $value, $products, $platform);
            } else if ($tag != "" && $tag != null){
                $xml->startElement(parseTagName($key));
                processXMLTree($value, $parent, $products, $platform);
                $xml->endElement();
            }
        }
    }
}

function processXMLProduct($parentTag, $profile, $products, $platform){
    global $xml;
    foreach ($products as $val){
        $xml->startElement($parentTag);
        foreach ($profile as $key => $value){
            $tag = parseTagName($key);
            if ($tag != "" && $tag != null){
                $xml->startElement(parseTagName($key));
                if (parseTagType($key) == "text"){
                    $xml->text(parseTagTextData($key,$val));
                }
                if (parseTagType($key) == "cData"){
                    $xml->writeCdata(parseTagTextData($key,$val));
                }
                $xml->endElement();
            } else {
                processElement($key, $platform, $val);
            }
        }
        $xml->endElement();
    }

}

function processElement($key, $platform, $product){
    global $xml;
    if ($platform == 11){
        switch ($key) {
            case "ImagesBlock":
                $xml->startElement("IMAGES");
                foreach ($product['images'] as $value){
                    $xml->startElement("IMAGE");
                    $xml->text("http://cp.azdev.eu/uploads/images/products/".$value['image']);
                    $xml->endElement();
                }
                $xml->endElement();
                break;
            case "CategoryBlock":
                $id_category = $product['id_category'];
                $cat = get_category($id_category);
                $xml->startElement("CATEGORY_LEVEL_1");
                $xml->text(trim(getLinked($cat['id'], $platform)));
                $xml->endElement();
                $xml->startElement("CATEGORY_LEVEL_2");
                $xml->text(trim(getLinked($cat['parent'], $platform)));
                $xml->endElement();
                break;
        }
    }
}



//echo htmlentities($xml->flush());
/*header('Content-type: text/xml');

echo $xml->flush();*/
$my_file = 'ProductList.xml';
unlink($my_file);
$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); //implicitly creates file
fwrite($handle, $xml->flush());
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($my_file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($my_file));
flush(); // Flush system output buffer
readfile($my_file);
exit;

function get_mappings($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*XML_mappings*} WHERE id_platform='$id'"));
    $arr = array(array());
    while ($row = $q->fetch_assoc()){
        $arr[$row['tag']]['mapping'] = $row['mapping'];
        $arr[$row['tag']]['type'] = $row['type'];
    }
    return $arr;
}

function get_profile($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*XML_profiles*} WHERE id_platform='$id'"));
    return json_decode($q->fetch_assoc()['profile'],true);
}

function parseTagName($tag){
    global $mappings;
    return $mappings[$tag]['mapping'];
}
function parseTagType($tag){
    global $mappings;
    return $mappings[$tag]['type'];
}
function parseTagTextData($key, $prod)
{
    switch ($key) {
        case "ProductNameEE":
            return $prod['name']['et'];
        case "ProductDescEE":
            return $prod['descriptions']['et'];
        case "ProductPrice":
            return $prod['platforms'][1]['price'] * 2;
        case "ProductPriceSpecial":
            return $prod['platforms'][1]['price'];
        case "ProductTag":
            return $prod['tag'];
        case "ProductNameRU":
            return $prod['name']['ru'];
        case "ProductDescRU":
            return $prod['descriptions']['ru'];
    }
    return "";
}