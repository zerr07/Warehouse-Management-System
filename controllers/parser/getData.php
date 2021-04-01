<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

include_once($_SERVER["DOCUMENT_ROOT"].'/libs/simple_html_dom.php');


function GetMatchedProducts($id): array {
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM parser_match WHERE id_product='$id'"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row['url'];
    }
    return $arr;
}
$post=json_decode(file_get_contents("php://input"), true);
if (isset($post['parse'])){
    $data = $post['parse'];
    $domain = str_ireplace('www.', '', parse_url($data['url'], PHP_URL_HOST));
    if (array_key_exists($domain, _PARSER_PROFILE)){
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/parser/profiles/"._PARSER_PROFILE[$domain]['parser'];
        call_user_func('GetParserSubmitData_' . _PARSER_PROFILE[$domain]['tag'], $data['url'], $data['lang'], $data['title'], $data['desc']);
        exit();
    }

}
if (isset($_GET['profiles'])){
    exit(json_encode(array_keys(_PARSER_PROFILE)));
}
if (isset($_GET['profiles_sku'])){
    $arr = array();
    foreach (_PARSER_PROFILE as $key => $value){
        if ($value['sku'] == true){
            $arr[$key] = $value['sku'];
        }
    }
    exit(json_encode($arr));
}
if (isset($_GET['insert']) && isset($_GET['url']) && isset($_GET['id_product'])){
    try {
        $url = $_GET['url'];
        $id = $_GET['id_product'];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO parser_match (url, id_product) VALUES ('$url', '$id')"));
        if ($GLOBALS['DBCONN']->error)
            throw new Exception("MySQL error on insert parser match");
        exit(json_encode(array("success"=>"")));
    } catch (Exception $e){
        exit(json_encode(array("error"=>$e->getMessage())));
    }
}
if (isset($_GET['getLanguages']) && isset($_GET['url'])){
    $domain = str_ireplace('www.', '', parse_url($_GET['url'], PHP_URL_HOST));
    exit(json_encode(_PARSER_PROFILE[$domain]['languages']));
}
if (isset($_GET['delete']) && isset($_GET['url']) && isset($_GET['id_product'])){
    try {
        $url = $_GET['url'];
        $id = $_GET['id_product'];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM parser_match WHERE url='$url' AND id_product='$id'"));
        if ($GLOBALS['DBCONN']->error)
            throw new Exception("MySQL error on delete parser match");
        exit(json_encode(array("success"=>"")));
    } catch (Exception $e){
        exit(json_encode(array("error"=>$e->getMessage())));
    }
}
if (isset($_GET['inserted']) && isset($_GET['id_product'])){
    exit(json_encode(array_values(GetMatchedProducts($_GET['id_product']))));
}

if (isset($_GET['images'])){
    $collection  = $GLOBALS['PARSERCONN']->images->images_matches1;
    $cursor = $collection->find(['id_product' => $_GET['images']])->toArray();
    exit(json_encode($cursor));
}
if (isset($_GET['sku']) && isset($_GET['platform'])){
    $name = 'products_data_'.explode(".", $_GET['platform'])[0];

    $collection  = $GLOBALS['PARSERCONN']->products->$name;
    $filter  = array('sku' => $_GET['sku']);
    $cursor = $collection->find($filter)->toArray();
    exit(json_encode($cursor));
}
if (isset($_GET['title']) && isset($_GET['offset']) && isset($_GET['platform'])){
    $collection  = $GLOBALS['PARSERCONN']->products->products_data;
    if ($_GET['platform']=="all"){
        $filter  = array('$text' => array('$search'=>$_GET['title']));
    } else {
        $filter  = array(
            'url' => new \MongoDB\BSON\Regex($_GET['platform']),
            '$text' => array('$search'=>$_GET['title'])
        );
    }
    $options = array(
        'limit' => 5,
        'skip' => intval($_GET['offset']),
        'score' => array(
            '$meta' => 'textScore'
        ),
        'projection'=>array(
            'score'=>array(
                '$meta'=>'textScore'
            )
        ),
        'sort' => array(
            'score' => array(
                '$meta'=>'textScore'
            )
        )
    );
    $cursor = $collection->find($filter, $options)->toArray();
    exit(json_encode($cursor));
}
if (isset($_GET['url'])){
    $domain = str_ireplace('www.', '', parse_url($_GET['url'], PHP_URL_HOST));

    if (array_key_exists($domain, _PARSER_PROFILE)){
        include_once $_SERVER['DOCUMENT_ROOT']."/controllers/parser/profiles/"._PARSER_PROFILE[$domain]['parser'];
        call_user_func('GetParserSearchData_' . _PARSER_PROFILE[$domain]['tag'], $_GET['url']);
        exit();
    }
}

