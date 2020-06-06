<?php
$my_file = 'ProductList.xml';
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
if (isset($_GET['username']) && isset($_GET['password'])){
    $user = $_GET['username'];
    $pass = $_GET['password'];
}

if (isset($_POST['username']) && isset($_POST['password'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*XML_users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0){
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    if ($res['id'] != 3){
        exit();
    }
    header('Content-type: text/xml');
    $platform = $res['id_platform'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *, 
        (SELECT `name` FROM {*category_name*} WHERE id_lang='3' AND id_category={*products*}.id_category) as category_name 
        FROM {*products*} WHERE tag LIKE 'AZ%' AND
        id IN (SELECT id_item FROM {*product_platforms*} WHERE id_platform='$platform' AND export='1')"));


    function getBigshopCarrierID($carrID){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*carriers*} WHERE id='$carrID'"));
        while ($row = mysqli_fetch_assoc($q)){
            return $row['shop_id'];
        }
        return null;
    }

    function getEnabledCarriers($id){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*carrier_details*} WHERE id_product='$id'"));
        $arr = array(array());
        while($row = mysqli_fetch_assoc($q)){
            $arr[$row['id_carrier']]['status'] = $row['enabled'];
            $arr[$row['id_carrier']]['shop_id'] = getBigshopCarrierID($row['id_carrier']);
        }
        return array_filter($arr);
    }

    unlink($my_file);
    $handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file); //implicitly creates file
    fwrite($handle, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
    fwrite($handle, "<Products>\n");

    while ($row = mysqli_fetch_assoc($q)){

        if($row['tag'] != "" && $row['quantity'] != "" ) {
            $arr = read_result_single($row);

            $carriers_status = getEnabledCarriers($row['id']);

            $price = str_replace(",", ".", $arr['platforms'][$platform]['price']);
            fwrite($handle, "    <Product>\n");
            fwrite($handle, "        <Tag>" . $row['tag'] . "</Tag>\n");
            fwrite($handle, "        <Available>" . $row['quantity'] . "</Available>\n");
            fwrite($handle, "        <Price>" . $price . "</Price>\n");
            fwrite($handle, "        <Category>" . $row['id_category'] . "</Category>\n");
            if(!empty($carriers_status)){
                fwrite($handle, "        <Carriers>\n");
                foreach ($carriers_status as $key=>$val){
                    fwrite($handle, "        <Carrier>\n");
                    fwrite($handle, "        <Id>" . $val['shop_id'] . "</Id>\n");
                    fwrite($handle, "        <Status>" . $val['status'] . "</Status>\n");
                    fwrite($handle, "        </Carrier>\n");
                }
                fwrite($handle, "        </Carriers>\n");
            }
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

} else {
    exit("Username or password is incorrect");
}

