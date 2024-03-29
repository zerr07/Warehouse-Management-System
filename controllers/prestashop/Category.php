<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/API.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/categories/get_categories.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/generateURL.php');

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

function PR_POST_Category($parent, $catNameET, $catNameRU, $catNameEN, $enabled, $urlET, $urlRU, $urlEN){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $xml = '
    <prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <category>
        <id_parent>'.$parent.'</id_parent>
        <active>'.$enabled.'</active>
        <name>
          <language id="'._LANG['ps_en'].'"><![CDATA['.$catNameEN.']]></language>
          <language id="'._LANG['ps_ru'].'"><![CDATA['.$catNameET.']]></language>
          <language id="'._LANG['ps_et'].'"><![CDATA['.$catNameRU.']]></language>
        </name>
        <link_rewrite>
          <language id="'._LANG['ps_en'].'"><![CDATA['.$urlEN.']]></language>
          <language id="'._LANG['ps_ru'].'"><![CDATA['.$urlRU.']]></language>
          <language id="'._LANG['ps_et'].'"><![CDATA['.$urlET.']]></language>
        </link_rewrite>
    </category>
</prestashop>
    ';
    return CallPOSTAPI($url, $xml)['categories'][0]['id'];
}
function PR_PUT_Category($id){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $data = get_category($id);
    if(!array_key_exists("en", $data['name'])){
        $data['name']['en'] = "";
    }
    if(!array_key_exists("et", $data['name'])){
        $data['name']['et'] = "";
    }
    if(!array_key_exists("ru", $data['name'])){
        $data['name']['ru'] = "";
    }
    foreach ($data['name'] as $key => $value){
        if ($value == ""){
            foreach ($data['name'] as $name){
                if ($name != ""){
                    $data['name'][$key] = $name." ".$key;
                    break;
                }
            }
        }
    }


    $xml = '
    <prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <category>
        <id>'.$id.'</id>
        <id_parent>'.$data['parent'].'</id_parent>
        <active>'.$data['enabled'].'</active>
        <name>
            <language id="'._LANG['ps_en'].'"><![CDATA['.$data['name']['en'].']]></language>
            <language id="'._LANG['ps_ru'].'"><![CDATA['.$data['name']['ru'].']]></language>
            <language id="'._LANG['ps_et'].'"><![CDATA['.$data['name']['et'].']]></language>
        </name>
        <link_rewrite>
          <language id="'._LANG['ps_en'].'"><![CDATA['.get_EN_URL(trim($data['name']['en'])).']]></language>
          <language id="'._LANG['ps_ru'].'"><![CDATA['.get_RU_URL(trim($data['name']['ru'])).']]></language>
          <language id="'._LANG['ps_et'].'"><![CDATA['.get_ET_URL(trim($data['name']['et'])).']]></language>
        </link_rewrite>
    </category>
</prestashop>
    ';
    echo '<pre>'; print_r ($data['name']); echo '</pre>';
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