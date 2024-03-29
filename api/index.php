<?php
header("Access-Control-Allow-Origin: *");

include_once($_SERVER['DOCUMENT_ROOT'].'/api/Routing.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/checkToken.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/SyncSupplier.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/reservations.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/shipments.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/sales.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/other.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/FB/routes.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/products/stockLocations.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/products/product.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/products/translations.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/products/images.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/products/ean.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/products/locations.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/locations.php');


Route::run('/');