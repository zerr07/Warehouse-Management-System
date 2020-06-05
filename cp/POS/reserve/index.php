<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';

if (isset($_GET['view'])){
    $smarty->assign("reservation", getSingleCartReservation($_GET['view']));
    $smarty->display('cp/POS/reserve/view.tpl');
} elseif (isset($_GET['cancelFull'])){
    cancelReservationFull($_GET['cancelFull']);
    header("Location: /cp/POS/reserve/");
} elseif (isset($_GET['cancel']) && isset($_GET['prodCancel'])){
    cancelReservationProduct($_GET['cancel'], $_GET['prodCancel']);
    header("Location: /cp/POS/reserve/");
} else {
    $smarty->assign("reservedList", getReservedCarts());
    $smarty->display('cp/POS/reserve/index.tpl');
}
