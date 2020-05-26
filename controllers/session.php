<?php
if (session_status() != PHP_SESSION_ACTIVE) {	    //Checks if session started or not
    ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
    ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
    session_start();
}

function get_shard_url(){
    $url = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    $shard = explode("/", $url['path']);
    return $shard['2'];
}

if  (isset($_COOKIE['Authenticated']) && $_COOKIE['Authenticated'] != ""){

    if (!isset($_COOKIE['shard'])){
        setcookie("shard", getShardName(_ENGINE['id_shard']), time() + (86400 * 30), "/"); // 86400 = 1 day

    }

    $shard = str_replace("s-", "", get_shard_url());
    if (!in_array("$shard", getShards())){
        if (isset($_SERVER['HTTPS'])) {
            $url = parse_url("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        } else {
            $url = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }
        $path = explode("/", $url['path']);
        array_splice($path, 2, 0, "s-".getShardName(_ENGINE['id_shard']));
        $url['path'] = implode("/", $path);
        setcookie("shard", getShardName(_ENGINE['id_shard']), time() + (86400 * 30), "/"); // 86400 = 1 day
        header("Location: ".build_url($url));
    } else {
        setcookie("shard", $shard, time() + (86400 * 30), "/"); // 86400 = 1 day
    }

    $smarty->assign("user", $_COOKIE['Authenticated']);
    $smarty->assign("userID", $_COOKIE['user_id']);
    getCart();
    $smarty->assign("cart", $_SESSION['cart']);
    $smarty->assign("cartTotal", $_SESSION['cartTotal']);

}

function build_url($components) {
    $url = $components['scheme'] . '://';
    if (!empty($components['username']) && !empty($components['password'])) {
        $url .= $components['username'] . ':' . $components['password'] . '@';
    }
    $url .= $components['host'];
    if (!empty($components['port']) &&
        (($components['scheme'] === 'http' && $components['port'] !== 80) ||
            ($components['scheme'] === 'https' && $components['port'] !== 443))
    ) {
        $url .= ':' . $components['port'];
    }
    if (!empty($components['path'])) {
        $url .= $components['path'];
    }
    if (!empty($components['fragment'])) {
        $url .= '#' . $components['fragment'];
    }
    if (!empty($components['query'])) {
        $url .= '?' . $components['query'];
    }
    return $url;
}
