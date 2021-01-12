<?php
Route::add("/api/FB/getOutputProductsJson.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/FB/getOutputProductsJson.php";
});

Route::add("/api/FB/getProductsJson.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/FB/getProductsJson.php";
});

Route::add("/api/FB/outputProducts.php", function (){
    include_once $_SERVER['DOCUMENT_ROOT']."/api/FB/outputProducts.php";
});

/*
 * This is temporary solution. Full RESTapi will be implemented in the rebuild project.
 */