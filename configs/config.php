<?php
global $DBCONN, $BIGCONN;
include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/DB/query.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/saveCart.php');

//include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/url_gen.php');
define("SETTINGS", json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/configs/config.json'), true));
define("_DB", SETTINGS['database']);
define("_ENGINE", SETTINGS['engine']);
$DBCONN = new MySQLi(
    _DB['dbhost'],
    _DB['dbuser'],
    _DB['dbpass'],
    _DB['dbname']);
$DBCONN->query("SET NAMES utf8");
$BIGCONN = new MySQLi(
    "159.69.219.35",
    "bigshop17_d_usr",
    "0dAUE7nDDDAxk3A4",
    "bigshop17_db");
$BIGCONN->query("SET NAMES utf8");
require_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/shards.php');

if (!isset($_COOKIE['shard'])){
    setcookie("shard", getShardName(_ENGINE['id_shard']), time() + (86400 * 30), "/"); // 86400 = 1 day
}
if (!isset($_COOKIE['id_shard'])){
    setcookie("id_shard", _ENGINE['id_shard'], time() + (86400 * 30), "/"); // 86400 = 1 day
}