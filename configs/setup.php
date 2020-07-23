
<?php

// загружаем библиотеку Smarty
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

require($_SERVER["DOCUMENT_ROOT"].'/Smarty/Smarty.class.php');
// Файл setup.php - это хорошее место для
// подключения библиотечных файлов вашего приложения,
// вы можете сделать это прямо здесь. Пример:
// require('guestbook/guestbook.lib.php');

class Smarty_startup extends Smarty {

   function __construct()
   {

        // Конструктор класса.
        // Он автоматически вызывается при создании нового экземпляра.

        parent::__construct();

        $this->setTemplateDir($_SERVER["DOCUMENT_ROOT"].'/templates/'._ENGINE['template']);
        $this->setCompileDir($_SERVER["DOCUMENT_ROOT"].'/templates_c');
        $this->setCacheDir($_SERVER["DOCUMENT_ROOT"].'/cache');
        $this->setConfigDir($_SERVER["DOCUMENT_ROOT"].'/configs');

        $this->caching = FALSE;
   }
}
global $smarty;
$smarty = new Smarty_startup();
/*$translations = json_decode( file_get_contents($_SERVER["DOCUMENT_ROOT"].'/translations/system/basic.json'),
    true);
$smarty->assign("basic", $translations);*/

$smarty->assign("engine", _ENGINE);
$smarty->assign("system", _SYSTEM);


//$hookInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/plugins/hooks.json'), true);
//include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/loadHook.php');
if (isset($_GET['logout'])){
    session_start();
    setcookie("Authenticated", $_COOKIE['Authenticated'], time() - 3600, "/");
    setcookie("user_id", $_COOKIE['user_id'], time() - 3600, "/");
    header('Location: /');
    exit();
}
$id_shard = $_COOKIE['id_shard'];
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */"SELECT tag_prefix FROM {*shards*} WHERE id='$id_shard'"));
if ($q){
    $smarty->assign("shard_prefix", $q->fetch_assoc()['tag_prefix']);
}
include_once($_SERVER['DOCUMENT_ROOT'] . "/controllers/products/get_location_types.php");
$smarty->assign("location_types", get_location_types());

if (!isset($_COOKIE['default_location_type']) && isset($_COOKIE['Authenticated'])){
    $user = $_COOKIE['Authenticated'];
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*users*} 
                                                                                        WHERE username='$user'"));
    $res = mysqli_fetch_assoc($q);
    setcookie("default_location_type", $res['default_location_type'], time() + (86400 * 30), "/");
    header("Refresh:0");
}