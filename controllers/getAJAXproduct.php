<?php
header('Content-Type: text/plain');
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
if (isset($_GET['barcode'])){
    $prod = get_product_by_tag($_GET['barcode']);
    if (is_null($prod)){
        $prod = get_product_by_ean($_GET['barcode']);
    }
    echo json_encode($prod);
} elseif(isset($_GET['id'])){
    echo json_encode(get_product($_GET['id']));
}
