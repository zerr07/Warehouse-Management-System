<?php
$my_file = 'ProductList.xml';
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE tag LIKE 'AZ%' AND
                                id IN (SELECT id_item FROM {*product_platforms*} WHERE id_platform='6' AND export='1')"));

include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_platforms.php');
$platform_desc = get_platform_desc_decoded(6);
function tag_process($str, $tag){
    $string = str_replace("&lt;-TAG-&gt;",$tag , $str);
    $string = str_replace("<-TAG->",$tag , $string);
    return $string;
}

unlink($my_file);
$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); //implicitly creates file
date_default_timezone_set('Europe/Tallinn');
$startDate = date("Y-m-d\TH:i:s", strtotime("+1 hours"));

$xw = xmlwriter_open_memory();
xmlwriter_set_indent($xw, 1);
$res = xmlwriter_set_indent_string($xw, ' ');
xmlwriter_start_document($xw, '1.0', 'UTF-8');
xmlwriter_start_element($xw, 'items');
while ($row = mysqli_fetch_assoc($q)){
    if($row['tag'] != "" && $row['quantity'] != "" ) {
        $arr = read_result_single($row);
        $exportName = "";
        $name = explode(" ", $arr['name']['et']);
        foreach ($name as $word){
            if (strlen($exportName." ".$word) <= 60){
                $exportName .= " ".$word;
            } else {
                break;
            }
        }
        if (strlen($exportName) < 5){
            continue;
        }
        if ($arr['quantity'] <= 0){
            continue;
        }
        if (strlen(strip_tags($arr['descriptions']['et'])) < 20){
            continue;
        }
        $index = $arr['id_category'];
        $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*category_platform*} WHERE id_platform='6' AND id_category='$index' LIMIT 2"));
        if(mysqli_num_rows($query) == 0) {
            continue;
        }

        xmlwriter_start_element($xw, 'item');

        xmlwriter_start_element($xw, 'item_id');
        xmlwriter_text($xw, $arr['id']);
        xmlwriter_end_element($xw); // item_id

        xmlwriter_start_element($xw, 'item_type');
        xmlwriter_text($xw, 'S');
        xmlwriter_end_element($xw); // item_type

        xmlwriter_start_element($xw, 'item_name');
        xmlwriter_write_cdata($xw, $exportName);
        xmlwriter_end_element($xw); // item_name

        $tempET = tag_process($platform_desc['et'], $arr['tag']);


        xmlwriter_start_element($xw, 'item_description');
        xmlwriter_write_cdata($xw, $arr['descriptions']['et'].$tempET);
        xmlwriter_end_element($xw); // item_description

        xmlwriter_start_element($xw, 'sku');
        xmlwriter_text($xw, $arr['tag']);
        xmlwriter_end_element($xw); // sku

        xmlwriter_start_element($xw, 'images');

        xmlwriter_start_element($xw, 'image_url');
        xmlwriter_text($xw, "http://cp.azdev.eu/uploads/images/products/".$arr['mainImage']);
        xmlwriter_end_element($xw); // image_url

        foreach ($arr['images'] as $img){
            if ($img['primary'] != 1){
                xmlwriter_start_element($xw, 'image_url');
                xmlwriter_text($xw, "http://cp.azdev.eu/uploads/images/products/".$img['image']);
                xmlwriter_end_element($xw); // image_url
            }
        }

        xmlwriter_end_element($xw); // images



            xmlwriter_start_element($xw, 'categories');
            while($rowCat = mysqli_fetch_assoc($query)){
                xmlwriter_start_element($xw, 'category_id');
                xmlwriter_text($xw, $rowCat['id_category_platform']);
                xmlwriter_end_element($xw); // category_id
            }
            xmlwriter_end_element($xw); // categories

        xmlwriter_start_element($xw, 'date_start');
        xmlwriter_text($xw, $startDate);
        xmlwriter_end_element($xw); // date_start

        xmlwriter_start_element($xw, 'quantity');
        xmlwriter_text($xw, $arr['quantity']);
        xmlwriter_end_element($xw); // quantity

        xmlwriter_start_element($xw, 'condition');
        xmlwriter_text($xw, "new");
        xmlwriter_end_element($xw); // condition

        xmlwriter_start_element($xw, 'price');

        xmlwriter_start_element($xw, 'start_price');
        xmlwriter_text($xw, $arr['platforms'][6]['price']);
        xmlwriter_end_element($xw); // start_price

        xmlwriter_end_element($xw); // price

        xmlwriter_start_element($xw, 'location');

        xmlwriter_start_element($xw, 'location_country');
        xmlwriter_text($xw, "Eesti");
        xmlwriter_end_element($xw); // location_country

        xmlwriter_start_element($xw, 'location_county');
        xmlwriter_text($xw, "Harjumaa");
        xmlwriter_end_element($xw); // location_county

        xmlwriter_start_element($xw, 'location_city');
        xmlwriter_text($xw, "Tallinn");
        xmlwriter_end_element($xw); // location_city

        xmlwriter_end_element($xw); // location

        xmlwriter_start_element($xw, 'payment');
        xmlwriter_start_element($xw, 'payment_vmoney');
        xmlwriter_end_element($xw); // payment_vmoney
        xmlwriter_start_element($xw, 'payment_bank');
        xmlwriter_end_element($xw); // payment_bank
        xmlwriter_end_element($xw); // payment

        xmlwriter_start_element($xw, 'shipment');

        xmlwriter_start_element($xw, 'shipment_term');
        xmlwriter_text($xw, "7");
        xmlwriter_end_element($xw); // shipment_term

        xmlwriter_start_element($xw, 'shipment_price_courier');
        xmlwriter_text($xw, $arr['carrier'][2]['price']);
        xmlwriter_end_element($xw); // shipment_price_courier
        if($arr['carrier'][1]['enabled']){
            xmlwriter_start_element($xw, 'ship_courier_smartpost');
            xmlwriter_end_element($xw); // ship_courier_smartpost
            xmlwriter_start_element($xw, 'smartpost_size_id');
            xmlwriter_text($xw, "1");
            xmlwriter_end_element($xw); // smartpost_size_id
        }
        /*if($arr['carrier'][2]['enabled']){
            xmlwriter_start_element($xw, 'ship_courier_pickup');
            xmlwriter_end_element($xw); // ship_courier_pickup
            xmlwriter_start_element($xw, 'pickup_description');
            xmlwriter_text($xw, "Kauba saab kätte aadressil Narva mnt 150, Tallinn, 1 korrus
            Maja paremalt poolt sissepääs sildiga BigShop.ee
            Tööaeg: E-R 12.00-17.00
            Tel. 58834435
            Paluks jargi tulla mitte hiljem kui 3 tööpäeva jooksul");
            xmlwriter_end_element($xw); // pickup_description
        }*/
        if($arr['carrier'][1]['enabled']){
            xmlwriter_start_element($xw, 'ship_courier_agreement');
            xmlwriter_end_element($xw); // ship_courier_agreement
            xmlwriter_start_element($xw, 'agreement_description');
            xmlwriter_text($xw, "Mitme eseme ostmisel, saadame ühes pakkis.");
            xmlwriter_end_element($xw); // agreement_description
        }

        xmlwriter_end_element($xw); // shipment

        xmlwriter_end_element($xw); // item

    }
}
xmlwriter_end_element($xw); // items
xmlwriter_end_document($xw);
fwrite($handle, xmlwriter_output_memory($xw));

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
