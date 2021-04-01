<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/libs/simple_html_dom.php');
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
$platforms = get_platforms();
function get_parser_products(){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_product FROM {*parser_match*} GROUP BY id_product"));
    while ($row = $q->fetch_assoc()) {
        array_push($arr, $row['id_product']);
    }
    return $arr;
}
function get_parser_prices($id, $platforms){
    $prices = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT url FROM {*parser_match*} WHERE id_product='$id'"));
    while ($row = $q->fetch_assoc()){
        $domain = str_ireplace('www.', '', parse_url($row['url'], PHP_URL_HOST));
        if (array_key_exists($domain, _PARSER_PROFILE)){
            include_once $_SERVER['DOCUMENT_ROOT']."/controllers/parser/profiles/"._PARSER_PROFILE[$domain]['parser'];
            $a = json_decode(call_user_func('GetParserSearchData_' . _PARSER_PROFILE[$domain]['tag'], $row['url'], false), true);
            if (isset($a['price']) && $a['price'] != 0){
                array_push($prices, floatval(str_replace(",", ".", $a['price'])));
            }
        }
    }

    if (!empty($prices)){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT MAX(priceVAT) as price FROM supplier_data WHERE id_item='$id'"));
        $price_supp = round($q->fetch_assoc()['price']*1.2, 2);
        $min_price = number_format(round(min($prices), 2), 2);
        foreach ($platforms as $key => $value){
            if (round($price_supp*1.1/$value['profitMargin'], 2) < $min_price){
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_platforms*} SET price='$min_price' WHERE id_item='$id' AND id_platform='$key'"));

            } else {
                $new_price = number_format(round($price_supp*1.1/$value['profitMargin'], 2), 2);
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_platforms*} SET price='$new_price' WHERE id_item='$id' AND id_platform='$key'"));
            }
        }
        return array("success"=>$price_supp." < ".$min_price);
    } else {
        return array("fail" => "No price");
    }

}


if (isset($_GET['getProducts'])){
    exit(json_encode(get_parser_products()));
}
if (isset($_GET['setPrice'])){
    exit(json_encode(get_parser_prices($_GET['setPrice'], $platforms)));
}

