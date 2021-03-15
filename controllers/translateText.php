<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
use \Dejurin\GoogleTranslateForFree;
$post=json_decode(file_get_contents("php://input"), true);
if (isset($post['target']) && isset($post['text'])){
    $source = 'auto';
    $target = $post['target'];
    $attempts = 5;
    $text = $post['text'];

    $tr = new GoogleTranslateForFree();
    $result = $tr->translate($source, $target, $text, $attempts);

    exit(json_encode(array("result"=>$result)));
}
