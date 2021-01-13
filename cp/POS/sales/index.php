<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/getSales.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/SMT/restoreQuantity.php';
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/pagination.php');
$searchQuery = "";

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
$count_on_page = 7;
if (isset($_GET['page'])) {
    $pages = get_sales_pages($_GET['page']-1, $searchQuery);
    $smarty->assign("current_page", $_GET['page']);
} else {
    $pages = get_sales_pages(0, $searchQuery);
    $smarty->assign("current_page", 1);
}
$smarty->assign("pageBase" , GETPageLinks("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
$smarty->assign("pages" , $pages);

$smarty->assign("SalesHistoryDatalist", getSalesHistoryDatalist());
$sales = get_sales($searchQuery, $start, $onPage);
$smarty->assign("sales", array_filter($sales['arr']));
$smarty->assign("desc", array_filter($sales['desc']));
if (isset($_GET['view'])){
    $smarty->display('cp/POS/sales/view.tpl');
} elseif (isset($_GET['tagastusFull'])){
    $id = $_GET['tagastusFull'];
    restore($id);

    header('Location: /cp/POS/sales' );
} else {
    $smarty->display('cp/POS/sales/index.tpl');
}
