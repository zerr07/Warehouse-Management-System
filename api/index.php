<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/api/Routing.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/checkToken.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/api/routes/reservations.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/FB/routes.php');
Route::run('/');