<?php
$my_file = 'ProductList.xml';
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include $_SERVER['DOCUMENT_ROOT'] . "/controllers/products/applyRule.php";
//$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE tag LIKE 'AZ%' AND
                                    //id IN (SELECT id_item FROM {*product_platforms*} WHERE id_platform='2' AND export='1')"));
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE tag LIKE 'AZ%'"));




unlink($my_file);
$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); //implicitly creates file
fwrite($handle, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
fwrite($handle, "<Products>\n");

while ($row = mysqli_fetch_assoc($q)){

    if($row['tag'] != "" && $row['quantity'] != "" ) {
        $id = $row['id'];
        //$getPrice = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_platforms*}
                                                                           // WHERE id_platform='2' AND id_item='$id' AND export='1'"));
        $getPrice = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_platforms*} 
                                                                            WHERE id_platform='2' AND id_item='$id'"));
        $price = mysqli_fetch_assoc($getPrice);
        if ($row['override'] == 1) {
            $price = applyRule($id, 6,2);
        } else {
            $price =  $price['price'];
        }
        if ($price == "") {
            $price = "0.000000";
        }
        $price = str_replace(",", ".", $price);
        fwrite($handle, "    <Product>\n");
        fwrite($handle, "        <Tag>" . $row['tag'] . "</Tag>\n");
        fwrite($handle, "        <Available>" . $row['quantity'] . "</Available>\n");
        fwrite($handle, "        <Price>" . $price . "</Price>\n");
        fwrite($handle, "    </Product>\n");
    }
}
fwrite($handle, "</Products>\n");

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
