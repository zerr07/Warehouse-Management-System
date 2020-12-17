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
function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}
function PR_POST_Category($urlET, $urlRU){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $data = get_category(15);
    echo '<pre>'; print_r($data); echo '</pre>';
    $xml = '
    <prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <category>
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
    return CallPOSTAPI($url, $xml)['categories'][0]['id'];
}
function PR_PUT_Category($id, $urlET, $urlRU){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories?output_format=JSON&display=full";
    $data = get_category(15);
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
    return CallPOSTAPI($url, $xml)['categories'][0]['id'];
}

//PR_POST_Category("1232132132131231", "31241353252523");

function PR_DELETE_Category($id){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/categories/$id?output_format=JSON";
    CallDELETEAPI($url);
}