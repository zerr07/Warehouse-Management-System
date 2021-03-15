<?php
/*
 * This is an example template. Use this as a base for parser profile. GetParserSubmitData function will return
 * JSON string with product name and description (with html). GetParserSearchData will return image url, price and title.
 * Update config to point a site to this profile and change selectors for profile to work. Note that some of the sites
 * do not allow using parsers and can detect its usage.
 */

ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/libs/simple_html_dom.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/libs/get_all_redirects.php');



function GetParserSubmitData($url, $lang, $title=false, $desc=false){

    $domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
    $arr = array();
    $url = "https://".parse_url($url, PHP_URL_HOST)."/".basename($url);
    if (_PARSER_PROFILE[$domain]['languages'][$lang]['query'] == false) {
        $html = str_get_html(GetHTML($url));
        $url = $html->find(_PARSER_PROFILE[$domain]['languages'][$lang]['selector'], 0)->href;
        $html = str_get_html(GetHTML(htmlspecialchars_decode($url)));
    } else {
        $html = file_get_html($url);
        $lang_hotlips = $html->find("html", 0)->lang;
        $url = $url._PARSER_PROFILE[$domain]['languages'][$lang]['query'].$lang_hotlips;
        $url = htmlspecialchars_decode($url);
        $html = str_get_html(GetHTML1($url));
    }
    if ($title) {
        $arr['title'] = $html->find("[class='product-name'] h1", 0)->innertext;
    } else {
        $arr['title'] = null;
    }
    if ($desc){
        $short_desc = $html->find("div[class='short-description'] div[class='std']", 0)->innertext;
        $desc = $html->find("div[id='product_tabs_description_contents'] div[class='std']", 0)->innertext;
        if (strlen($short_desc) < strlen($desc)){
            $arr['desc'] = $desc;
        } else {
            $arr['desc'] = $short_desc;
        }
    } else {
        $arr['desc'] = null;
    }
    exit(json_encode($arr));
}
function GetParserSearchData($url){
    $html = file_get_html($url);
    $arr = array();

    $arr['image'] = $html->find("[class='more-views'] img", 0)->src;
    $arr['price'] = filter_var($html->find("[class='special-price'] span[class='price']", 0)->innertext, FILTER_UNSAFE_RAW, FILTER_FLAG_ENCODE_LOW|FILTER_FLAG_STRIP_HIGH);
    if ($arr['price'] == ""){
        $arr['price'] = filter_var($html->find("[class='regular-price'] span", 0)->innertext, FILTER_UNSAFE_RAW, FILTER_FLAG_ENCODE_LOW|FILTER_FLAG_STRIP_HIGH);
    }
    $arr['price'] = trim($arr['price']);
    $arr['title'] = $html->find("[class='product-name'] h1", 0)->innertext;

    exit(json_encode($arr));
}

function GetHTML($url){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_COOKIESESSION => true

    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
function GetHTML1($url){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: experiment=0; frontend=oj9hph5l1n54gskjq93d5m53s6; frontend_cid=bIL8XSgJnm2Ma3go; store=et'
        ),

    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}