<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/reserve/reserve.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/getShippingData.php");

echo '<pre>'; print_r (getData_full("5860")); echo '</pre>';