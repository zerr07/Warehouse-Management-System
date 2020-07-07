<?php
function orderMode($mode, $ostja){
    if($mode != 'Bigshop'){
        return $mode;
    }
    if ($ostja != ""){
        return $ostja;
    } else {
        return "Eraisik";
    }
}