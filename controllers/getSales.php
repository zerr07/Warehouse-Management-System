<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$searchQuery = "";
$arr = array(array());
$desc = array(array());
if (isset($_POST['searchArve'])){
    $arve = $_POST['searchArve'];
    $searchQuery = "WHERE arveNr LIKE '%$arve%'";
}
if (isset($_GET['mode'])){
    $mode = $_GET['mode'];
    if ($mode != 'All'){
        $searchQuery = "WHERE modeSet='$mode'";
    }
    $smarty->assign("modeSearch", $mode);
}
if (isset($_GET['view'])){
    $view = $_GET['view'];
    $searchQuery = "WHERE id='$view'";
}

$onPage = _ENGINE['onPage'];
if (isset($_GET['page'])){
    $start = ($_GET['page']-1)*$onPage;
} else {
    $start = 0*$onPage;
}
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sales*} $searchQuery ORDER BY saleDate DESC LIMIT $start, $onPage"));
for ($i = 0;$row = mysqli_fetch_assoc($q); $i++){
    $arr[$i]['arveNr'] = $row['arveNr'];
    $arr[$i]['date'] = date_format(date_create($row['saleDate']), "d.m.Y H:i:s");
    $arr[$i]['sum'] = number_format($row['cartSum'], 2, ".", "");
    $arr[$i]['card'] = number_format($row['card'],2, ".", "");
    $arr[$i]['cash'] = number_format($row['cash'],2, ".", "");
    $arr[$i]['id'] = $row['id'];
    $arr[$i]['ostja'] = $row['ostja'];
    $arr[$i]['tellimuseNr'] = $row['tellimuseNr'];
    $arr[$i]['mode'] = $row['modeSet'];
    $arr[$i]['tagastusFull'] = "";
    $arr[$i]['shipment_id'] = $row['shipment_id'];
    $id = $row['id'];
    $countTagastus = 0;
    $countItems = 0;
    $qItems = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sold_items*} WHERE id_sale='$id'"));
    while ($rowItems = mysqli_fetch_assoc($qItems)){
        $arr[$i]['tagastusFull'] .= "tagastusFull[]=".$rowItems['id']."&";
        $desc[$rowItems['id']]['saleID'] = $rowItems['id'];
        $desc[$rowItems['id']]['price'] = number_format($rowItems['price'],2, ".", "");
        $desc[$rowItems['id']]['quantity'] = $rowItems['quantity'];
        $desc[$rowItems['id']]['basePrice'] = number_format($rowItems['basePrice'], 2, ".", "");
        $desc[$rowItems['id']]['status'] = $rowItems['statusSet'];
        if ($rowItems['statusSet'] == "Müük"){
            $countTagastus++;
        }
        $countItems++;
        $itemID = $rowItems['id_item'];
        if (is_numeric($itemID)){
            $qItemDesc = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE id='$itemID'"));

            while ($rowItemDesc = mysqli_fetch_assoc($qItemDesc)){
                $desc[$rowItems['id']]['name'] = get_name($itemID)['et'];
                $desc[$rowItems['id']]['tag'] = $rowItemDesc['tag'];
                $desc[$rowItems['id']]['id'] = $rowItemDesc['id'];
            }
        } else {
            $desc[$rowItems['id']]['name'] = $itemID;
            $desc[$rowItems['id']]['tag'] = "Buffertoode";
            $desc[$rowItems['id']]['id'] = "";
        }
    }

    if ($countTagastus == $countItems){
        $arr[$i]['status'] = "Müük";
    } elseif ($countTagastus < $countItems && $countTagastus != 0){
        $arr[$i]['status'] = "Müük/Tagastus";
    } elseif ($countTagastus == 0){
        $arr[$i]['status'] = "Tagastus";
    }
}
$count_on_page = 7;
include ($_SERVER["DOCUMENT_ROOT"].'/controllers/pagination.php');
if (isset($_GET['page'])) {
    $pages = get_sales_pages($_GET['page']-1, $searchQuery);
    $smarty->assign("current_page", $_GET['page']);
} else {
    $pages = get_sales_pages(0, $searchQuery);
    $smarty->assign("current_page", 1);
}
function getSalesHistoryDatalist(){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, arveNr, ostja FROM {*sales*}"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row['arveNr']. " | ".$row['ostja'];
    }
    return array_filter($arr);
}


$smarty->assign("pageBase" , GETPageLinks("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
$smarty->assign("pages" , $pages);

$smarty->assign("SalesHistoryDatalist", getSalesHistoryDatalist());
$smarty->assign("sales", array_filter($arr));
$smarty->assign("desc", array_filter($desc));