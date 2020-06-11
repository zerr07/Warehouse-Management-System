<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

// +++++++++++++ Remove styles from descriptions +++++++++++++
/*
$files = glob($_SERVER['DOCUMENT_ROOT']."/translations/products/*.{json}", GLOB_BRACE);
foreach($files as $file) {
    $f = file_get_contents($file);
    $arr = json_decode($f, true);

    foreach ($arr['product'] as $key => $val){
        $res = html_entity_decode($arr['product'][$key]['description']);
        $arr['product'][$key]['description'] = preg_replace('/style=".*?"/', '', $res);
    }

    $plTXT = "\xEF\xBB\xBF".$arr['product']['pl']['description'];
    $ruTXT = "\xEF\xBB\xBF".$arr['product']['ru']['description'];
    $enTXT = "\xEF\xBB\xBF".$arr['product']['en']['description'];
    $etTXT = "\xEF\xBB\xBF".$arr['product']['et']['description'];
    $lvTXT = "\xEF\xBB\xBF".$arr['product']['lv']['description'];
    $product = array("product" => array(
        'pl' => array("description" => htmlentities($plTXT, ENT_QUOTES)),
        'ru' => array("description" => htmlentities($ruTXT, ENT_QUOTES)),
        'en' => array("description" => htmlentities($enTXT, ENT_QUOTES)),
        'et' => array("description" => htmlentities($etTXT, ENT_QUOTES)),
        'lv' => array("description" => htmlentities($lvTXT, ENT_QUOTES))
    ));


    $json = json_encode($product);
    file_put_contents($file, $json);
}
*/
//+++++++++++++ Minuvalik tree +++++++++++++

/*$xml = simplexml_load_file("https://www.minuvalik.ee/ru/xml/shop_fields");
foreach ($xml as $key => $val){
    if ($key == "CATEGORY_LEVEL_1"){
        echo $val->ID."<br>";
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text *//*"INSERT INTO {*TreeMinuvalik*}
        (id_category, `name`, `parent`) VALUES ('$val->ID','$val->TITLE', '0')"));
        if (isset($val->CATEGORY_LEVEL_2)){
            foreach ($val->CATEGORY_LEVEL_2 as $value){
                mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text *//*"INSERT INTO {*TreeMinuvalik*}
                (id_category, `name`, `parent`) VALUES ('$value->ID','$value->TITLE', '$val->ID')"));
            }
        }

    }

}*/