<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/API.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/categories/get_categories.php');

$api_key = _DB_EXPORT['auth_key'];
$domain = _DB_EXPORT['domain'];

function PR_GET_Categories(){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    return CallGETAPI($url);
}
function PR_GET_Category($id){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full&filter[id]=$id";
    return CallGETAPI($url);
}

function PR_POST_Category($parent, $catNameET, $catNameRU, $enabled, $urlET, $urlRU){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $xml = '
    <prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <category>
        <id_parent>'.$parent.'</id_parent>
        <active>'.$enabled.'</active>
        <name>
          <language id="2"><![CDATA['.$catNameET.']]></language>
          <language id="3"><![CDATA['.$catNameRU.']]></language>
        </name>
        <link_rewrite>
          <language id="2"><![CDATA['.$urlET.']]></language>
          <language id="3"><![CDATA['.$urlRU.']]></language>
        </link_rewrite>
    </category>
</prestashop>
    ';
    return CallPOSTAPI($url, $xml)['categories'][0]['id'];
}
function PR_PUT_Category($id, $urlET, $urlRU){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $data = get_category($id);
    $xml = '
    <prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <category>
        <id>'.$id.'</id>
        <id_parent>'.$data['parent'].'</id_parent>
        <active>'.$data['enabled'].'</active>
        <name>
          <language id="2"><![CDATA['.$data['name']['et'].']]></language>
          <language id="3"><![CDATA['.$data['name']['ru'].']]></language>
        </name>
        <link_rewrite>
          <language id="2"><![CDATA['.$urlET.']]></language>
          <language id="3"><![CDATA['.$urlRU.']]></language>
        </link_rewrite>
    </category>
</prestashop>
    ';
    return CallPUTAPI($url, $xml)['categories'][0]['id'];
}
function PR_PUT_Category_parent_only($id, $parent){
    global $domain, $api_key;
    $data1 = PR_GET_Category($id);
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $xml = '
    <prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <category>
        <id>'.$id.'</id>
        <id_parent>'.$parent.'</id_parent>
        <active>'.$data1['active'].'</active>
        <name>
          <language id="2"><![CDATA['.$data1['categories']['name'][0]['value'].']]></language>
          <language id="3"><![CDATA['.$data1['categories']['name'][1]['value'].']]></language>
        </name>
        <link_rewrite>
          <language id="2"><![CDATA['.$data1['categories']['link_rewrite'][0]['value'].']]></language>
          <language id="3"><![CDATA['.$data1['categories']['link_rewrite'][1]['value'].']]></language>
        </link_rewrite>
    </category>
</prestashop>
    ';
    return CallPUTAPI($url, $xml)['categories'][0]['id'];
}

//PR_POST_Category("1232132132131231", "31241353252523");

function PR_DELETE_Category($id){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories/$id?output_format=JSON";
    CallDELETEAPI($url);
}