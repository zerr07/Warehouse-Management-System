<?php
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
if (isset($_POST['shard']) || isset($_GET['shard'])){
    if (isset($_POST['shard'])){
        $shard = $_POST['shard'];
    } elseif (isset($_GET['shard'])){
        $shard = $_GET['shard'];
    }
} else {
    $shard = _ENGINE['id_shard'];
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */"SELECT tag_prefix FROM {*shards*} WHERE id='$shard'"));
if ($q){
    $prefix = $q->fetch_assoc()['tag_prefix'];
}
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0){
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    header('Content-type: text/xml');
    $platform = $res['id_platform'];
    $coefficient_q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT profitMargin FROM {*platforms*} WHERE id='$platform'"));
    $coefficient = mysqli_fetch_assoc($coefficient_q)['profitMargin'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *
        FROM {*products*} WHERE tag LIKE '$prefix%' AND id_shard='$shard' AND
        id IN (SELECT id_item FROM {*product_platforms*} WHERE id_platform='$platform' AND export='1')"));


    $xml = new XMLWriter();
    $xml->openURI("test.xml");
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument();
    $xml->startElement('products');

    while ($row = mysqli_fetch_assoc($q)){
        if($row['tag'] != "" && $row['quantity'] != "" ) {
            $arr = read_result_single($row);

            $id = $arr['id'];
            $main_cat = get_main_category_export($arr['id']);
            if (is_null($main_cat)){
                continue;
            }

            $code = "";
            $queryCode = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_codes*} WHERE id_product='$id' LIMIT 1"));
            while ($rowCode = mysqli_fetch_assoc($queryCode)){
                $code = $rowCode['ean'];
            }

            $xml->startElement('product');

            if ($platform==5){
                $xml->startElement('ProductId');
                $xml->text($arr['id']);
                $xml->endElement();
            }

            $xml->startElement('SKU');
            $xml->text($arr['tag']);
            $xml->endElement();

            $xml->startElement('EAN');
            $xml->text($code);
            $xml->endElement();

            if (isset($_GET['type']) && $_GET['type'] == "full"){

                $xml->startElement('name_et');
                $xml->writeCdata($arr['name']['et']);
                $xml->endElement();

                $xml->startElement('name_ru');
                $xml->writeCdata($arr['name']['ru']);
                $xml->endElement();

                $xml->startElement('description_et');
                $xml->writeCdata($arr['descriptions']['et']);
                $xml->endElement();

                $xml->startElement('description_ru');
                $xml->writeCdata($arr['descriptions']['ru']);
                $xml->endElement();

                $xml->startElement('manufacturer');
                $xml->writeCdata($arr['manufacturer']);
                $xml->endElement();
            }
            if (isset($_POST['type']) && $_POST['type'] == "full"){

                $xml->startElement('name_et');
                $xml->writeCdata($arr['name']['et']);
                $xml->endElement();

                $xml->startElement('name_ru');
                $xml->writeCdata($arr['name']['ru']);
                $xml->endElement();

                $xml->startElement('description_et');
                $xml->writeCdata($arr['descriptions']['et']);
                $xml->endElement();

                $xml->startElement('description_ru');
                $xml->writeCdata($arr['descriptions']['ru']);
                $xml->endElement();

                $xml->startElement('manufacturer');
                $xml->writeCdata($arr['manufacturer']);
                $xml->endElement();
            }

            $xml->startElement('id_category');
            $xml->text($main_cat);
            $xml->endElement();

            if (isset($_GET['type']) && $_GET['type'] == "full"){
                $xml->startElement('category_name');
                $xml->writeCdata(get_product_category_name($main_cat));
                $xml->endElement();
                $xml->startElement('category_name_text');
                $xml->text(get_product_category_name($main_cat));
                $xml->endElement();
            }
            if (isset($_POST['type']) && $_POST['type'] == "full"){
                $xml->startElement('category_name');
                $xml->writeCdata(get_product_category_name($main_cat));
                $xml->endElement();
                $xml->startElement('category_name_text');
                $xml->text(get_product_category_name($main_cat));
                $xml->endElement();
            }
            if ($platform == 5){
                $xml->startElement('weight');
                $xml->endElement();
                $images = "";
                $xml->startElement('image_tree');
                if (isset($arr['mainImage']) && $arr['mainImage'] != ""){
                    $images = "http://".$_SERVER['SERVER_NAME']."/uploads/images/products/".$arr['mainImage'];
                }
                foreach ($arr['images'] as $img){
                    if ($img['position'] != 1){
                        if ($images != ""){
                            $images .= "|http://".$_SERVER['SERVER_NAME']."/uploads/images/products/".$img['image'];
                        } else {
                            $images .= "http://".$_SERVER['SERVER_NAME']."/uploads/images/products/".$img['image'];
                        }
                    }
                }
                $xml->text($images);
                $xml->endElement();
            } elseif ($platform==2 || $platform==1) {
                $images = "";
                $xml->startElement('image_tree');
                if (isset($arr['mainImage']) && $arr['mainImage'] != ""){
                    $images = $arr['mainImage'];
                }
                foreach ($arr['images'] as $img){
                    if ($img['position'] != 1){
                        if ($images != ""){
                            $images .= "|".$img['image'];
                        } else {
                            $images .= $img['image'];
                        }
                    }
                }
                $xml->text($images);
                $xml->endElement();
                $images = "";
                $xml->startElement('image_tree_live');
                if (isset($arr['mainImage_live']) && $arr['mainImage_live'] != ""){
                    $images = $arr['mainImage_live'];
                }
                foreach ($arr['images_live'] as $img){
                    if ($img['position'] != 1){
                        if ($images != ""){
                            $images .= "|".$img['image'];
                        } else {
                            $images .= $img['image'];
                        }
                    }
                }
                $xml->text($images);
                $xml->endElement();
            } else {
                $xml->startElement('images');

                $xml->startElement('image_url');
                $xml->text("http://".$_SERVER['SERVER_NAME']."/uploads/images/products/" . $arr['mainImage']);
                $xml->endElement();

                foreach ($arr['images'] as $img) {
                    if ($img['position'] != 1) {
                        $xml->startElement('image_url');
                        $xml->text("http://".$_SERVER['SERVER_NAME']."/uploads/images/products/" . $img['image']);
                        $xml->endElement();
                    }
                }

                $xml->endElement();
                $xml->startElement('images_live');

                $xml->startElement('image_url');
                $xml->text("http://".$_SERVER['SERVER_NAME']."/uploads/images/products/" . $arr['mainImage_live']);
                $xml->endElement();

                foreach ($arr['images_live'] as $img) {
                    if ($img['primary'] != 1) {
                        $xml->startElement('image_url');
                        $xml->text("http://".$_SERVER['SERVER_NAME']."/uploads/images/products/" . $img['image']);
                        $xml->endElement();
                    }
                }

                $xml->endElement();
            }
            $xml->startElement('price');
            /*if ($platform == 3){
                $xml->text(round($arr['platforms'][2]['price']/1.2, 2));
            } else {*/
                $xml->text($arr['platforms'][$platform]['price']);
            //}
            $xml->endElement();

            if ($platform == 3) {
                $xml->startElement('buyPrice');
                $xml->text(round($arr['platforms'][3]['price']*$coefficient,2));
                $xml->endElement();
            }

            $xml->startElement('quantity');
            $xml->text($arr['quantity']);
            $xml->endElement();

            $xml->startElement('smartpost');
            if($arr['carrier'][1]['enabled']){
                $xml->text("allowed");
            } else {
                $xml->text("prohibited");
            }
            $xml->endElement();

            $xml->startElement('courier');
            if($arr['carrier'][2]['enabled']){
                $xml->text("allowed");
            } else {
                $xml->text("prohibited");
            }
            $xml->endElement();


            $xml->endElement();

        }
    }

    $xml->endElement();

    echo $xml->flush();


} else {
    exit("Username or password is incorrect");
}

