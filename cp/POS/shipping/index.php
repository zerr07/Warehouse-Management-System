<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/shipping/getShippingStatus.php';
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/shipping/getShippingData.php';

if (isset($_GET['view'])){
    $arr = getSingleCartReservation($_GET['view']);
    $sum = 0;
    foreach ($arr['products'] as $val){
        $sum+= $val['price'];
    }
    $smarty->assign("sum", $sum);
    $smarty->assign("shipping_types", json_decode(getShippingTypes(), true));
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
        $shipments = getReservedCartsShipmentOnlyChecked(2);
    } elseif (isset($_GET['searchShippings'])){
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
    $smarty->assign("statusToggled", $_GET['statusSearch']);
    $smarty->assign("typeToggled", $_GET['typeSearch']);
    $smarty->assign("statusList", json_decode(getShippingStatuses(), true));
    $smarty->assign("typeList", json_decode(getShippingTypes(), true));
    $smarty->assign("reservedList", $shipments);
    $smarty->display('cp/POS/shipping/index.tpl');
}
