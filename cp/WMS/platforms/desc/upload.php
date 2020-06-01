<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
$id = $_POST['id'];

$get_lang = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*languages*}"));
$lang = array();
while ($row = mysqli_fetch_assoc($get_lang)){
    $lang[$row['id']] = $row['lang'];
}

$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*platform_descriptions*} WHERE id_platform='$id'"));

foreach ($lang as $key=>$value){
    if ($value=='ru'){
        $desc = htmlentities("\xEF\xBB\xBF".$_POST['RUS'], ENT_QUOTES);
    } elseif ($value=='et'){
        $desc = htmlentities("\xEF\xBB\xBF".$_POST['EST'], ENT_QUOTES);
    } elseif ($value=='en'){
        $desc = htmlentities("\xEF\xBB\xBF".$_POST['ENG'], ENT_QUOTES);
    } elseif ($value=='pl'){
        $desc = htmlentities("\xEF\xBB\xBF".$_POST['PL'], ENT_QUOTES);
    } elseif ($value=='lv'){
        $desc = htmlentities("\xEF\xBB\xBF".$_POST['LV'], ENT_QUOTES);
    }

    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*platform_descriptions*} (id_platform, id_lang, `desc`) VALUES ('$id','$key','$desc')"));

}
header("Location: /cp/WMS/platforms");