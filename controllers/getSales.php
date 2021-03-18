<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}

function get_sales_n($minified=false){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sales*} ORDER BY saleDate DESC"));
    return read_sale_result_multiple($q, $minified);
}

function get_single_sale($id, $minified=false){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sales*} WHERE id='$id'"));
    return read_sale_result_single_query($q, $minified);
}

function read_sale_result_multiple($q, $minified=false){
    if ($q) {
        $arr = array();
        if ($minified){
            while ($row = $q->fetch_assoc()) {
                $arr[$row['id']] = read_sale_result_single_row_minified($row);
            }
        } else {
            while ($row = $q->fetch_assoc()) {
                $arr[$row['id']] = read_sale_result_single_row($row);
            }
        }
        return $arr;
    } else {
        return null;
    }
}

function read_sale_result_single_row($row){
    $id = $row['id'];
    $arr = $row;
    $arr['sum'] = number_format($row['cartSum'], 2, ".", "");
    $arr['card'] = number_format($row['card'],2, ".", "");
    $arr['cash'] = number_format($row['cash'],2, ".", "");
    $arr['tagastusFull'] = "";
    $arr['products'] = array();
    $qItems = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sold_items*} WHERE id_sale='$id'"));
    while ($rowItems = $qItems->fetch_assoc()){
        $id_prod = $rowItems['id'];
        $arr['products'][$id_prod] = $rowItems;
        $arr['products'][$id_prod]['price'] = number_format($rowItems['price'],2, ".", "");
        $arr['products'][$id_prod]['basePrice'] = number_format($rowItems['basePrice'], 2, ".", "");
        $arr['products'][$id_prod]['status'] = $rowItems['statusSet'];
        unset($arr['products'][$id_prod]['statusSet']);
    }
    return $arr;
}

function read_sale_result_single_row_minified($row){
    unset($row['saleDate']);
    unset($row['modeSet']);
    unset($row['tellimuseNr']);
    unset($row['shipment_id']);
    $arr = $row;
    $arr['sum'] = number_format($row['cartSum'], 2, ".", "");
    $arr['card'] = number_format($row['card'],2, ".", "");
    $arr['cash'] = number_format($row['cash'],2, ".", "");
    return $arr;
}

function read_sale_result_single_query($q, $minified=false){
    if ($q) {
        if ($minified){
            return read_sale_result_single_row_minified($q->fetch_assoc());
        } else {
            return read_sale_result_single_row($q->fetch_assoc());
        }
    } else {
        return null;
    }
}
function get_sales($searchQuery, $start, $onPage){
    $arr = array(array());
    $desc = array(array());
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
                if ($qItemDesc->num_rows == 0){
                    $desc[$rowItems['id']]['name'] = "Deleted ".$rowItems['name'];
                    $desc[$rowItems['id']]['tag'] = $rowItems['tag'];
                    $desc[$rowItems['id']]['id'] = "Deleted";
                } else {
                    while ($rowItemDesc = mysqli_fetch_assoc($qItemDesc)){
                        $desc[$rowItems['id']]['name'] = get_name($itemID)['et'];
                        $desc[$rowItems['id']]['tag'] = $rowItemDesc['tag'];
                        $desc[$rowItems['id']]['id'] = $rowItemDesc['id'];
                    }
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
    return array("arr"=>$arr, "desc"=>$desc);
}


function getSalesHistoryDatalist(){
    $arr = array(array());
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, arveNr, ostja FROM {*sales*}"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row['arveNr']. " | ".$row['ostja'];
    }
    return array_filter($arr);
}
