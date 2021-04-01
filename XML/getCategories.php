<?php

include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (isset($_GET['username']) && isset($_GET['password'])){
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $id = $_GET['SKU'];
}

if (isset($_POST['username']) && isset($_POST['password'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $id = $_POST['SKU'];
}
$check = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*XML_users*} 
                                                                                        WHERE username='$user'"));
$res = mysqli_fetch_assoc($check);
if (mysqli_num_rows($check) == 0){
    /* No such user */
    exit("Username or password is incorrect");
} elseif (password_verify($pass, $res['password'])) {
    /* User verified */
    header('Content-type: text/xml');
    include($_SERVER["DOCUMENT_ROOT"] . '/controllers/categories/get_categories.php');
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT *, 
        (SELECT `name` FROM {*category_name*} WHERE id_lang='3' AND id_category={*categories*}.id LIMIT 1) as category_name,
        (SELECT `name` FROM {*category_name*} WHERE id_lang='3' AND id_category={*categories*}.parent LIMIT 1) as category_parent 
        FROM {*categories*} WHERE export='1'"));

    $xml = new XMLWriter();
    $xml->openURI("test.xml");
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument();
    $xml->startElement('categories');
    while ($row = mysqli_fetch_assoc($q)){


            $xml->startElement('category');

            $xml->startElement('id');
            $xml->text($row['id']);
            $xml->endElement();

            $xml->startElement('name');
            $xml->writeCdata($row['category_name']);
            $xml->endElement();
            $xml->startElement('parent_id');
            $xml->text($row['parent']);
            $xml->endElement();
            $xml->startElement('parent_id');
            $xml->writeCdata($row['category_parent']);
            $xml->endElement();

            $xml->endElement();


    }

    $xml->endElement();

    echo $xml->flush();
} else {
    exit("Username or password is incorrect");
}
