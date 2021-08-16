<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/shipping/getShippingStatus.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/shipping/getShippingData.php';
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/pagination.php';

if (isset($_GET['view'])){
    $arr = getSingleCartReservation($_GET['view']);
    $sum = 0;
    foreach ($arr['products'] as $val){
        $sum+= $val['price'];
    }
    $smarty->assign("sum", $sum);
    $smarty->assign("shipping_types", json_decode(getShippingTypes(), true));
    $arr['shipping_type'] = getShippingType($arr['id'], "STANDART_ID");
    $smarty->assign("reservation", $arr);

    $smarty->display('cp/POS/shipping/view.tpl');
} elseif (isset($_GET['cancelFull'])){
    cancelReservationFull($_GET['cancelFull']);
    header("Location: /cp/POS/shipping/");
} elseif (isset($_GET['cancel']) && isset($_GET['prodCancel'])){
    cancelReservationProduct($_GET['cancel'], $_GET['prodCancel']);
    header("Location: /cp/POS/shipping/");
} else {
    if (isset($_GET['onlyCheckedOut'])) {
        $smarty->assign("onlyCheckedOut", "true");
        if (isset($_GET['page'])) {
            $pages = get_reservations_pages($_GET['page'], 2);
            $shipments = getReservedCartsShipmentOnlyChecked($_GET['page']-1, 2);
            $smarty->assign("current_page", $_GET['page']);

        } else {
            $pages = get_reservations_pages(1, 2);
            $shipments = getReservedCartsShipmentOnlyChecked(1, 2);
            $smarty->assign("current_page", 1);
        }
        $smarty->assign("pageBase" , GETPageLinks("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
        $smarty->assign("pages" , $pages);
    } elseif (isset($_GET['searchIDorBarcode'])){
        $smarty->assign("searchIDorBarcode", $_GET['searchIDorBarcode']);
        $shipments = getReservedCartsSearch(2);
     } else {
        $shipments = getReservedCarts(2);
    }
    foreach ($shipments as $key => $value){
        $shipments[$key]['status'] = getShippingStatus($key, "STANDART");
    }
    foreach ($shipments as $key => $value){
        $shipments[$key]['type'] = getShippingType($key, "STANDART");
    }
    if (isset($_GET['statusSearch'])){
        $smarty->assign("statusToggled", $_GET['statusSearch']);
    }
    if (isset($_GET['typeSearch'])){
        $smarty->assign("typeToggled", $_GET['typeSearch']);
    }
    $smarty->assign("statusList", json_decode(getShippingStatuses(), true));
    $smarty->assign("typeList", json_decode(getShippingTypes(), true));
    $smarty->assign("reservedList", $shipments);
    $smarty->display('cp/POS/shipping/index.tpl');
}
