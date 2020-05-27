<?php
$url = "http://cp.azdev.eu/cp/WMS/?searchTagID=&searchName=auto&search=Search&page=2";
$parts = parse_url($url);
parse_str($parts['query'], $query);
$link = "";
$i = 0;
foreach ($query as $key => $value) {
    if ($key != "page") {
        if ($i == 0) {
            $link .= "?" . $key . "=" . $value . "&";
        } else {
            $link .=  $key . "=" . $value."&";
        }

    }
    $i++;
}