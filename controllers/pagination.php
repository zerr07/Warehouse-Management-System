<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/configs/config.php');

function get_product_pages($currentPage){
    $query = get_product_range(1, "Search");
    $result = mysqli_query($GLOBALS['DBCONN'],prefixQuery($query));
    $count = $result->fetch_assoc()['count'];
    $count_pages = ceil($count/_ENGINE['onPage']);
    $pages = array(array());
    $first = $currentPage-5;
    $last = $currentPage+5;
    $pages['lastPage'] = $count_pages;
    for ($i = 0; $first<=$last; $first++){
        if ($first > 0 && $first <= $count_pages){
            $pages['pages'][$i] = $first;
            $i++;
        }
    }
    return $pages;
}

function GETPageLinks($url){
    $parts = parse_url($url);
    parse_str($parts['query'], $query);
    $link = "?";
    foreach ($query as $key => $value){
        if ($key != "page") {
            $link .=  $key . "=" . $value . "&";
        }
    }
    return $link;
}

function get_sales_pages($currentPage, $searchQuery){
    $q = mysqli_query($GLOBALS['DBCONN'],prefixQuery(/** @lang text */ "SELECT count(*) as count FROM {*sales*} $searchQuery ORDER BY saleDate DESC"));

    $count = $q->fetch_assoc()['count'];
    $count_pages = ceil($count/_ENGINE['onPage']);
    $pages = array(array());
    $first = $currentPage-5;
    $last = $currentPage+5;
    $pages['lastPage'] = $count_pages;
    for ($i = 0; $first<=$last; $first++){
        if ($first > 0 && $first <= $count_pages){
            $pages['pages'][$i] = $first;
            $i++;
        }
    }
    return $pages;
}