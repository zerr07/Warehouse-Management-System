<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<p>Registration</p>
<form action="#" method="post">
    <input type="text" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="submit" name="submit" value="Submit">
</form>
<p>Registration XML</p>
<form action="#" method="post">
    <input type="text" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="submit" name="submitXML" value="Submit">
</form>
</body>
</html>

<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $check = mysqli_query($GLOBALS['DBCONN'], prefixQuery("SELECT username FROM {*users*} WHERE username='$email'"));
    if (mysqli_num_rows($check) >= 1){
        echo "Email is already taken";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 10]);
        mysqli_query($GLOBALS['DBCONN'], prefixQuery("INSERT INTO {*users*} (username, `password`) VALUES ('$email', '$password')"));
        echo "Successfully registered";
        ?><a href="/">Home page</a><?php
    }
}

if (isset($_POST['submitXML'])){
    $email = $_POST['email'];
    $check = mysqli_query($GLOBALS['DBCONN'], prefixQuery("SELECT username FROM {*XML_users*} WHERE username='$email'"));
    if (mysqli_num_rows($check) >= 1){
        echo "Email is already taken";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 10]);
        mysqli_query($GLOBALS['DBCONN'], prefixQuery("INSERT INTO {*XML_users*} (username, `password`) VALUES ('$email', '$password')"));
        echo "Successfully registered";
        ?><a href="/">Home page</a><?php
    }
}