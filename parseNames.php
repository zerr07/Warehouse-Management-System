<?php
$file = file_get_contents($_SERVER['DOCUMENT_ROOT']."/translations/products/29.json");
$arr = json_decode($file);
echo '<pre>'; print_r ($arr); echo '</pre>';

