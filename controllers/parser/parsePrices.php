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
                array_push($prices, number_format($a['price'], 2, '.', ''));
            }
        }
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT MAX(priceVAT) as price 
            FROM supplier_data WHERE id_item='$id'"));
    $price_supp = round($q->fetch_assoc()['price'], 2);
    if ($price_supp == 0.00){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT actPrice as price 
            FROM products WHERE id='$id'"));
        $price_supp = round($q->fetch_assoc()['price'], 2);
    }
    if ($price_supp == 0.00){
        return array("error" => "Supplier price is 0");
    }
    if (!empty($prices)){
        $min_price = number_format(min($prices), 2, '.', '');
        foreach ($platforms as $key => $value){
            $price = $price_supp/$value['profitMargin'];
            $margin_multiplier = ($price_supp+$value['minMargin'])/($price*$value['profitMargin']);

            if ($key == 1){
                if (round(($price*1.2)-$price, 2) < $value['minMargin'])
                    $pr_supp = $price*$margin_multiplier*1.2;
                else
                    $pr_supp = ($price * 1.2) * 1.2;
            } else {
                if (round(($price*1.2)-$price, 2) < $value['minMargin'])
                    $pr_supp = $new_price = $price*$margin_multiplier;
                else
                    $pr_supp = $price * 1.2;
            }
            $pr_supp = round($pr_supp, 2);
            if ($pr_supp < $min_price){
                echo '<pre>'; print_r (array("Min"=>$min_price)); echo '</pre>';
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_platforms*} 
                    SET price='$min_price' WHERE id_item='$id' AND id_platform='$key' AND custom='0'"));
            } else {
                echo '<pre>'; print_r (array("pr_supp"=>$pr_supp)); echo '</pre>';
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_platforms*} 
                    SET price='$pr_supp' WHERE id_item='$id' AND id_platform='$key' AND custom='0'"));
            }
        }
        return array("success"=>$price_supp." < ".$min_price);
    } else {
        foreach ($platforms as $key => $value){
            $price = $price_supp/$value['profitMargin'];
            $margin_multiplier = ($price_supp+$value['minMargin'])/($price*$value['profitMargin']);
            if ($key == 1){
                if (round($price*1.2*$value['profitMargin']-$price_supp, 2) < $value['minMargin']){
                    $new_price = $price*$margin_multiplier*1.2;
                } else {
                    $new_price = ($price * 1.2) * 1.2;
                }

            } else {
                if (round($price*1.2*$value['profitMargin']-$price_supp, 2) < $value['minMargin']){
                    $new_price = $price*$margin_multiplier;
                } else {

                    $new_price = $price * 1.2;
                }


            }
            $new_price = round($new_price, 2);
            echo '<pre>'; print_r (array("price"=>$new_price)); echo '</pre>';

            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_platforms*}
                   SET price='$new_price' WHERE id_item='$id' AND id_platform='$key' AND custom='0'"));
        }
        return array("success" => "Price generated");
    }

}


if (isset($_GET['getProducts'])){
    exit(json_encode(get_parser_products()));
}
if (isset($_GET['setPrice'])){
    exit(json_encode(get_parser_prices($_GET['setPrice'], $platforms)));
}

