<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
$bigshop = new MySQLi(
    "159.69.219.35",
    "bigshop17_d_usr",
    "0dAUE7nDDDAxk3A4",
    "bigshop17_db");
$bigshop->query("SET NAMES utf8");
/*$q = $bigshop->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*ps_product*}"));
while ($row = mysqli_fetch_assoc($q)){
    $ref = $row['reference'];
    $idCat = $row['id_category_default'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*products*} SET id_category='$idCat' WHERE tag='$ref'"));
}*/
/*$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*products*}"));
while ($row = mysqli_fetch_assoc($q)){
    $id = $row['id'];
    $name = $row['name'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"INSERT INTO {*product_name*}
    (id_product, id_lang, `name`) VALUES ('$id', '3', '$name')"));
}*/

/*function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*category_name*} WHERE id_lang='1'"));
while ($row = mysqli_fetch_assoc($q)){
    $name = $row['name'];
    $urlRU = str_replace($toReplace, "", $name);
    $urlRU = translit(htmlentities(str_replace(" ", "-", $urlRU), ENT_QUOTES, 'UTF-8'));
    $urlRU = preg_replace("/[^a-zA-Z]/", "", $urlRU);

    $id = $row['id_category'];
    $bigshop->query(prefixQuery(/** @lang text */ /*"UPDATE {*ps_category_lang*} SET name='$name', link_rewrite='$urlRU' WHERE id_lang='3' AND id_category='$id'"));
}

/*$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*categories*}"));
while ($row = mysqli_fetch_assoc($q)){
    $id = $row['id'];
    $enabled = $row['enabled'];
    $bigshop->query(prefixQuery(/** @lang text */ /*"UPDATE {*ps_category*} SET active='$enabled' WHERE  id_category='$id'"));
}*/
/*    $q = $bigshop->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*ps_category*}"));
while ($row = mysqli_fetch_assoc($q)){
    $id = $row['id_category'];
    echo $id."<br>";
    $ru = getName('3', $id);

    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"UPDATE {*category_name*} SET `name`='$ru'
                                WHERE id_lang='1' AND id_category='$id'"));

}

function getName($id_lang, $id_category){
    global $bigshop;
    $q = $bigshop->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*ps_category_lang*}
                                                            WHERE id_category='$id_category' AND id_lang='$id_lang'"));
    while ($row = mysqli_fetch_assoc($q)){
        return $row['name'];
    }
}*/


/*$xml = simplexml_load_file("key-znjocjkcjwmvpylfwrijvuvquzbmyg.xml");
foreach ($xml->cat as $category) {
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"INSERT INTO {*TreeMobilux*} (
    id_category, `name`, parent_id) VALUES ('$category->id',
    '$category->name','$category->parent_id')"));
    echo $category->name;
}

/*$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT *,
(SELECT COUNT(export) FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1)as count1
                                                                        FROM {*products*} WHERE tag!='' HAVING count1=0"));
while ($row = mysqli_fetch_assoc($q)){
    $itemID = $row['id'];
    $tag = $row['tag'];
    $getID = $bigshop->query(prefixQuery(/** @lang text */ /*"SELECT id_product FROM {*ps_product*} WHERE reference='$tag'"));
    while ($rowID = mysqli_fetch_assoc($getID)){
        $id = $rowID['id_product'];
        $getName = $bigshop->query(prefixQuery(/** @lang text */ /*"SELECT `name` FROM {*ps_product_lang*} WHERE id_product='$id' AND id_lang='3'"));
        while ($rowName = mysqli_fetch_assoc($getName)){
            $name = $rowName['name'];
            $check = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT * FROM {*product_name*} WHERE id_product='$itemID' AND id_lang='1'"));
            if (mysqli_num_rows($check) == 0){
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"INSERT INTO {*product_name*} (id_product, id_lang, `name`) VALUES ('$itemID', '1', '$name')"));
                echo $tag.' | '.$rowName['name'].'<br>';
            }


        }
    }
}*/
                /*$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT *,(SELECT COUNT(export)
                FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND export=1) as count1, 
                (SELECT price FROM {*product_platforms*} WHERE products.id = {*product_platforms*}.id_item AND id_platform='1') 
                as SHOPPRICE FROM {*products*} HAVING count1=5"));

                while ($row = mysqli_fetch_assoc($q)){
                    $price = $row['SHOPPRICE'];
                    $id = $row['id'];
                    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"INSERT INTO {*product_platforms*}
                    (id_item, id_platform, `price`, `export`) VALUES ('$id', '10', '$price', '1')"));
                }*/

/*if (($handle = fopen('okidoki_cat.csv', 'r')) !== FALSE) { // Check the resource is valid
    while (($data = fgetcsv($handle, 1000, "\n")) !== FALSE) { // Check opening the file is OK!
        foreach ($data as $items) {
            $item = explode(",", $items);
            $id = $item[1];
            $parent = $item[0];
            $name = $item[2];
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"INSERT INTO {*TreeOki*} (id_category, `name`, parent) VALUES ('$id', '$name', '$parent')"));
            echo $item[0] . " | ".$item[1] . " | ".$item[2] ."<br>";
        }


    }
    fclose($handle);
}
*/
/*header('Content-type: text/xml');
$xml_request =
    "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
    <CatalogRequest>
    <Date/>
    <CatNumber>1.0</CatNumber>
    <Route>
    <From><ClientID>10748322</ClientID></From>
    <To><ClientID>0</ClientID></To>
    </Route>
    <Filters>
    <Filter FilterID=\"StockLevel\" Value=\"OnStock\" />
    <Filter FilterID=\"Price\" Value=\"WOVAT\"/>
    </Filters>
    </CatalogRequest>";

$request_url = "http://b2b.also.ee/DirectXML.svc/0/scripts/XML_Interface.dll?USERNAME=xmlUser10748322&PASSWORD=AZ62F4p@1s&XML=".urlencode($xml_request);

$xml_response = file_get_contents($request_url);


$request_url_cat = "http://b2b.also.ee/DirectXML.svc/GetGrouping/0/10748322";
echo file_get_contents($request_url_cat);
/*echo $xml_response;*/
$platforms = array(array());

$getPlatforms = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*platforms*}"));
while ($rowPlatforms = mysqli_fetch_assoc($getPlatforms)){
    $platforms[$rowPlatforms['id']] = $rowPlatforms;
}

$file = fopen('Novaya_tablitsa.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
    foreach ($line as $key => $value){

        if (in_array($key,array(0, 5, 10))){
            $q =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE tag='$value'"));
            while ($row = mysqli_fetch_assoc($q)){
                $id = $row['id'];
                if ($key == 0){
                    $getPrice =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*} WHERE id_item='$id' LIMIT 1"));
                    while ($rowPrice = mysqli_fetch_assoc($getPrice)) {
                        $line[2] = $rowPrice['priceVAT'];
                    }
                    $getSellPrice =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_platforms*} WHERE id_item='$id' AND id_platform='6' LIMIT 1"));
                    $rowSell = mysqli_fetch_assoc($getSellPrice)['price'];
                    $line[1] = str_replace("?", "", $line[1]);
                    $line[3] = (($rowSell/$platforms[6]['margin'])*$platforms[6]['profitMargin'])-$row['actPrice'];
                }
                if ($key == 5){
                    $getPrice =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*} WHERE id_item='$id' LIMIT 1"));
                    while ($rowPrice = mysqli_fetch_assoc($getPrice)) {
                        $line[7] = $rowPrice['priceVAT'];
                    }
                    $getSellPrice =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_platforms*} WHERE id_item='$id' AND id_platform='2' LIMIT 1"));
                    $rowSell = mysqli_fetch_assoc($getSellPrice)['price'];
                    $line[6] = str_replace("?", "", $line[6]);
                    $line[8] = (($rowSell/$platforms[2]['margin'])/1.2)-$row['actPrice'];
                }
                if ($key == 10){
                    $getPrice =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*} WHERE id_item='$id' LIMIT 1"));
                    while ($rowPrice = mysqli_fetch_assoc($getPrice)) {
                        $line[12] = $rowPrice['priceVAT'];
                    }
                    $getSellPrice =  $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_platforms*} WHERE id_item='$id' AND id_platform='5' LIMIT 1"));
                    $rowSell = mysqli_fetch_assoc($getSellPrice)['price'];
                    $line[13] = (($rowSell/$platforms[5]['margin'])*$platforms[5]['profitMargin'])-$row['actPrice'];
                }
                // ((sell price / platform margin) / 1.2) - act price  FROM BIGSHOP AND SHOP KEYS 1 AND 2
                // ((sell price / platform margin) * platform profit margin) - act price
            }

        }
        if ($key == 13){
            $file_out = fopen("output_profit.txt","a");
            fwrite($file_out,$line[0].",");
            fwrite($file_out,$line[1].",");
            fwrite($file_out,$line[2].",");
            fwrite($file_out,$line[3].",");
            fwrite($file_out,$line[4].",");
            fwrite($file_out,$line[5].",");
            fwrite($file_out,$line[6].",");
            fwrite($file_out,$line[7].",");
            fwrite($file_out,$line[8].",");
            fwrite($file_out,$line[9].",");
            fwrite($file_out,$line[10].",");
            fwrite($file_out,$line[11].",");
            fwrite($file_out,$line[12].",");
            fwrite($file_out,$line[13].PHP_EOL);
            fclose($file_out);
        }
    }
}
fclose($file);