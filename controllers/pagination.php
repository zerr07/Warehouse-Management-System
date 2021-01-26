<?php

include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
function get_product_pages($currentPage){
    $query = get_product_range(1, "Search", $_COOKIE['id_shard']);
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

function get_reservations_pages($currentPage, $type){
    $result = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT COUNT(*) as count FROM {*reserved*} WHERE id_type='$type'"));
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
    $link = "?";
    if (isset($parts['query'])){
        parse_str($parts['query'], $query);
        foreach ($query as $key => $value){
            if ($key != "page") {
                if(is_array($value)){
                    foreach ($value as $k => $v){
                        $link .=  $key . "[$k]=" . $v . "&";
                    }
                } else {
                    $link .=  $key . "=" . $value . "&";
                }

            }
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