<?php

Route::add("/api/editReservation.php", function () {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/api/editReservation.php";
});
Route::add("/api/performSale.php", function () {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/api/performSale.php";
});
Route::add("/api/remove_reservation.php", function () {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/api/remove_reservation.php";
});
Route::add("/api/reservationConfirm.php", function () {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/api/reservationConfirm.php";
});
Route::add("/api/reserve.php", function () {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/api/reserve.php";
});
