<?php
function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}

function get_EN_URL($string){
    $urlEN = str_replace(array("'", "\""), "", $string);
    $urlEN = translit(htmlentities(str_replace(" ", "-", $urlEN), ENT_QUOTES, 'UTF-8'));
    return preg_replace("/[^a-zA-Z]/", "", $urlEN);
}
function get_ET_URL($string){
    $urlET = str_replace(array("'", "\""), "", $string);
    $urlET = translit(htmlentities(str_replace(" ", "-", $urlET), ENT_QUOTES, 'UTF-8'));
    return preg_replace("/[^a-zA-Z]/", "", $urlET);
}
function get_RU_URL($string){
    $urlRU = str_replace(array("'", "\""), "", $string);
    $urlRU = translit(htmlentities(str_replace(" ", "-", $urlRU), ENT_QUOTES, 'UTF-8'));
    return preg_replace("/[^a-zA-Z]/", "", $urlRU);
}