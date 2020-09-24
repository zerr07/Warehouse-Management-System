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

//+++++++++++++ Categories from boards to cp sync +++++++++++++
/*$q = $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ /*"SELECT *,
(SELECT `name` FROM {*ps_category_lang*} WHERE {*ps_category_lang*}.id_category={*ps_category*}.id_category AND {*ps_category_lang*}.id_lang='2') as nameET,
(SELECT `name` FROM {*ps_category_lang*} WHERE {*ps_category_lang*}.id_category={*ps_category*}.id_category AND {*ps_category_lang*}.id_lang='3') as nameRU
 FROM {*ps_category*}"));


if($q){

    while($row = $q->fetch_assoc()){
        $id = $row['id_category'];
        $nameET = $row['nameET'];
        $nameRU = $row['nameRU'];
        $parent = $row['id_parent'];
        $enabled = $row['active'];

        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text *//*"INSERT INTO {*categories*} (`id`, `parent`, `enabled`)
        VALUES ('$id', '$parent', '$enabled')"));
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text *//*"INSERT INTO {*category_name*} (`id_category`, `id_lang`, `name`)
        VALUES ('$id', '3', '$nameET')"));
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text *//*"INSERT INTO {*category_name*} (`id_category`, `id_lang`, `name`)
        VALUES ('$id', '1', '$nameRU')"));
    }
}*/

//+++++++++++++ Import products from XML +++++++++++++
/*include_once $_SERVER['DOCUMENT_ROOT'].'/api/SimpleXLSX.php';
function ftrim($str){
    return ltrim(rtrim($str));
}
if ( $xlsx = SimpleXLSX::parse('test_2020-06-11.xlsx') ) {
    $i = 0;
    foreach ($xlsx->rows() as $value){
        if ($i >= 2100 && $i < 2400){ // Set loop by 300 and reload

            $url = $value[0];
            $name = htmlentities($value[1], ENT_QUOTES);
            $desc = htmlentities($value[10], ENT_QUOTES);
            $category_name = $value[8];
            $manufacturer = $value[4];
            $actPrice = $value[2];
            $sellPrice = str_replace("€", "", $value[3]);
            $images = explode(",", str_replace(array("[", "]", "'"), "", $value[7]));
            $imagesJSON = array();
            foreach ($images as $img){
                $img = preg_replace('/\s+/', '', $img);
                array_push($imagesJSON, array("new", base64_encode(file_get_contents($img)), "0"));
            }
            $attributes = array_filter(explode(",", str_replace(array("[", "]", "'"), "", $value[11])));

            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT tag FROM {*products*} WHERE tag LIKE 'BR%' ORDER BY id DESC LIMIT 1"));
            if ($q->num_rows > 0){
                $tag = explode("BR", $q->fetch_assoc()['tag']);
                $tag = $tag[1]+1;
            } else {
                $tag = 1;
            }

            while(strlen($tag) < 3){
                $tag = "0".$tag;
            }
            $tag = "BR".$tag;

            $id_cat = 2;
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT id_category FROM {*category_name*} WHERE `name`='$category_name' AND id_lang='1'"));
            if ($q->num_rows != 0){
                while ($row = $q->fetch_assoc()){
                    $id_cat = $row['id_category'];
                }
            }


            $supplier_name = array(0=>"Steez");
            $supplier_url = array(0=>$url);
            $supplier_price = array(0=>"0.00");
            $supplier_priceVAT = array(0=>$actPrice);

            $platformID = array(1=>1);
            $platformURL = array(1=>"none");
            $platformPrice = array(1=>$sellPrice);
            $platformCustom = array(1=>"Yes");
            $platformExport = array(1=>"Yes");

            $plTXT = "";
            $ruTXT = str_replace(array("&lt;", "&gt;"), array("<", ">"), $desc);
            $enTXT = str_replace(array("&lt;", "&gt;"), array("<", ">"), $desc);
            $etTXT = str_replace(array("&lt;", "&gt;"), array("<", ">"), $desc);
            $lvTXT = "";

            $request = array(
                'itemActPrice' => $actPrice,
                'itemTagID' => $tag,
                'cat' => $id_cat,
                'itemQuantity' =>  '999',
                'override' =>  "No",
                'itemMarginPercent' => '0',
                'itemMarginNumber' =>  '0.00',
                'itemNameET' =>  $value[1],
                'itemNameRU' =>  $value[1],
                'itemSupplierName' => $supplier_name,
                'itemURL' => $supplier_url,
                'itemPrice' => $supplier_price,
                'itemPriceVAT' => $supplier_priceVAT,
                'platformID' => $platformID,
                'platformURL' => $platformURL,
                'platformPrice' => $platformPrice,
                'platformCustom' => $platformCustom,
                'export' => $platformExport,
                'itemLocation' => array(0 => ""),
                'PL' => $plTXT,
                'RUS' => $ruTXT,
                'ENG' => $enTXT,
                'EST' => $etTXT,
                'LV' => $lvTXT,
                'imagesJSON' => json_encode($imagesJSON)
            );

            $query = http_build_query ($request);

            // Create Http context details
            $contextData = array (
                'method' => 'POST',
                'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($query)."\r\n",
                'content'=> $query );

            $context = stream_context_create (array ( 'http' => $contextData ));

            $result =  file_get_contents (
                'http://cp.boards.ee/cp/WMS/item/add/upload.php',  // page url
                false,
                $context);

            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT MAX(id) as id FROM {*products*}"));
            $last = $q->fetch_assoc()['id'];
            if (!empty($attributes)){
                foreach ($attributes as $att){
                    $att = ftrim($att);
                    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text *//*"INSERT INTO {*product_attributes*}
                   (id_product, attribute) VALUES ('$last', '$att')"));
                }
            }
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*manufacturer*} WHERE
                                                                                   `name`='$manufacturer'"));
            if ($q->num_rows == 0){
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text *//*"INSERT INTO {*manufacturer*}
                   (`name`) VALUES ('$manufacturer')"));
                $q_m = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT MAX(id) as id FROM {*manufacturer*}"));
                $last_m = $q_m->fetch_assoc()['id'];
            } else {
                $last_m = $q->fetch_assoc()['id'];
            }
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text *//*"UPDATE {*products*} SET id_manufacturer='$last_m' WHERE id='$last'"));


            $i++;
        } else {
            $i++;
        }
    }

} else {
    echo SimpleXLSX::parseError();
}
*/


/* ------------ Find and remove(copy and update sql) image duplicates --------------------- */
/*function get_extension($file) {
    $array = explode(".", $file);
    $extension = end($array);
    return $extension ? $extension : false;
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT *, COUNT(*) FROM product_images GROUP BY image HAVING COUNT(*) > 1"));
while ($row = $q->fetch_assoc()){
    $img = $row['image'];
    $q_repl = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM product_images WHERE image='$img';"));
    while ($row_repl = $q_repl->fetch_assoc()){
        $id = $row_repl['id_item'];
        $oldfilename = $row_repl['image'];
        $file = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$oldfilename;
        $newfilename = $row_repl['id'] . rand(1, 100000000000000) . "." .get_extension($row_repl['image']);
        $newfile = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$newfilename;
        if (!copy($file, $newfile)) {
            echo "Error copying file";
        } else {
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*product_images*} SET image='$newfilename' WHERE image='$oldfilename' AND id_item='$id'"));
        }
    }
}*/

/* ------------ generate product image positions ------------ */

/*$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT id FROM {*products*}"));
while($row = $q->fetch_assoc()){
    $id = $row['id'];
    $q_img = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*product_images*} WHERE id_item='$id'"));
    $arr = array(array());
    $arrMain = array();
    while($row_img = $q_img->fetch_assoc()){
        if ($row_img['position'] == '1'){
            $arrMain = $row_img;
        } else {
            $arr[$row_img['id']] = $row_img;
        }
    }
    $arr = array_filter($arr);
    if (empty($arrMain)){
        $counter = 1;
    } else {
        $counter = 2;
        $mainID = $arrMain['id'];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*product_images*} SET position='1' WHERE id='$mainID'"));
    }
    foreach ($arr as $key => $value){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*product_images*} SET position='$counter' WHERE id='$key'"));
        $counter++;
    }
    $q_img = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*product_images_live*} WHERE id_item='$id'"));
    $arr = array(array());
    $arrMain = array();
    while($row_img = $q_img->fetch_assoc()){
        if ($row_img['position'] == '1'){
            $arrMain = $row_img;
        } else {
            $arr[$row_img['id']] = $row_img;
        }
    }
    if (empty($arrMain)){
        $counter = 1;
    } else {
        $counter = 2;
        $mainID = $arrMain['id'];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*product_images_live*} SET position='1' WHERE id='$mainID'"));
    }
    foreach ($arr as $key => $value){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*product_images_live*} SET position='$counter' WHERE id='$key'"));
        $counter++;
    }
}*/

phpinfo();

