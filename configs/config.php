<?php

global $DBCONN, $BIGCONN, $ENGINE, $DRUNCONN;
include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/DB/query.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/saveCart.php');

//include_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/url_gen.php');
if (!defined('SETTINGS')){
    define("SETTINGS", json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/configs/config.json'), true));
}
if (!defined('_DB')){
    define("_DB", SETTINGS['database']);
}
if (!defined('_DB_EXPORT')){
    define("_DB_EXPORT", SETTINGS['database_export']);
}
if (!defined('_SYSTEM')){
    define("_SYSTEM", json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/configs/sys.json'), true)['system']);
}
if (!defined('_ENGINE')){
    define("_ENGINE", SETTINGS['engine']);
    $ENGINE = SETTINGS['engine'];
}

$DBCONN = new MySQLi(
    _DB['dbhost'],
    _DB['dbuser'],
    _DB['dbpass'],
    _DB['dbname']);
$DBCONN->query("SET NAMES utf8");
$BIGCONN = new MySQLi(
    _DB_EXPORT['dbhost'],
    _DB_EXPORT['dbuser'],
    _DB_EXPORT['dbpass'],
    _DB_EXPORT['dbname']);
$BIGCONN->query("SET NAMES utf8");

require_once ($_SERVER["DOCUMENT_ROOT"].'/controllers/shards.php');

if (!isset($_COOKIE['shard'])){
    setcookie("shard", getShardName(_ENGINE['id_shard']), time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Refresh:0");
} else {
    setcookie("shard", $_COOKIE['shard'], time() + (86400 * 30), "/"); // 86400 = 1 day
}

if (!isset($_COOKIE['id_shard'])){
    setcookie("id_shard", _ENGINE['id_shard'], time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Refresh:0");
} else {
    setcookie("id_shard", $_COOKIE['id_shard'], time() + (86400 * 30), "/"); // 86400 = 1 day
}
