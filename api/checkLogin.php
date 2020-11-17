<?php
function getUser(){
    if (isset($_GET['username'])) {
        return $_GET['username'];
    } else if (isset($_POST['username'])){
        return $_POST['username'];
    }
    return null;
}
function getPass(){
    if (isset($_GET['password'])) {
        return $_GET['password'];
    } else if (isset($_POST['password'])){
        return $_POST['password'];
    }
    return null;
}
