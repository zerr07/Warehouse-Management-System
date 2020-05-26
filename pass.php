<?php
$fn = fopen("back.csv","r");
//Start of database connection settings
$dbaddr = 'localhost';
$dbuser = 'bigshop17_d_usr';
$dbpass = '0dAUE7nDDDAxk3A4';
$dbname = 'bigshop17_db';
//End of database connection settings
$conn = mysqli_connect($dbaddr,$dbuser,$dbpass,$dbname);
mysqli_query($conn, 'SET NAMES utf8');
while(! feof($fn))  {
    $result = fgets($fn);
    $result = str_replace("\"", "", $result);
    $result = explode(",", $result);
    $pass = $result[2];
    $email = $result[1];
    mysqli_query($conn, "update ps_customer set passwd='$pass' where email='$email'");
    echo "update ps_customer set passwd='$pass' where email='$email'"."<br>";
}

fclose($fn);