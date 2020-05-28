
<?php

// загружаем библиотеку Smarty
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
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

//$hookInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"].'/plugins/hooks.json'), true);
//include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/loadHook.php');
if (isset($_GET['logout'])){
    session_start();
    setcookie("Authenticated", $_COOKIE['Authenticated'], time() - 3600, "/");
    setcookie("user_id", $_COOKIE['user_id'], time() - 3600, "/");
    header('Location: /');
    exit();
}