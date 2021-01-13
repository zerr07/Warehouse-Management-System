<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/pagination.php');

if (isset($_GET['view'])){
    $arr = getSingleCartReservation($_GET['view']);
    if ($arr['id_type'] == 2){
        header("Location: /cp/POS/shipping/index.php?view=".$arr['id']);
    }
    $sum = 0;
    foreach ($arr['products'] as $val){
        $sum+= $val['price'];
    }
    $smarty->assign("sum", $sum);
    $smarty->assign("reservation", $arr);
    $smarty->display('cp/POS/reserve/view.tpl');
} elseif (isset($_GET['cancelFull'])){
    cancelReservationFull($_GET['cancelFull']);
    header("Location: /cp/POS/reserve/");
} elseif (isset($_GET['cancelFullShip'])){
    cancelReservationFull($_GET['cancelFullShip']);
    header("Location: /cp/POS/shipping/");
} elseif (isset($_GET['cancel']) && isset($_GET['prodCancel'])){
    cancelReservationProduct($_GET['cancel'], $_GET['prodCancel']);
    header("Location: /cp/POS/reserve/");
} elseif (isset($_GET['cancelShip']) && isset($_GET['prodCancelShip'])){
    cancelReservationProduct($_GET['cancelShip'], $_GET['prodCancelShip']);
    header("Location: /cp/POS/shipping/");
}else {
    if (isset($_GET['page'])) {
        $pages = get_reservations_pages($_GET['page'], 1);
        $arr = array_filter(getReservedCarts_range($_GET['page']-1, 1));
        $smarty->assign("current_page", $_GET['page']);
    } else {
        $pages = get_reservations_pages(1, 1);
        $arr = array_filter(getReservedCarts_range(0, 1));
        $smarty->assign("current_page", 1);
    }
    $smarty->assign("pageBase" , GETPageLinks("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
    $smarty->assign("pages" , $pages);
    $smarty->assign("reservedList", $arr);
    $smarty->display('cp/POS/reserve/index.tpl');
}
