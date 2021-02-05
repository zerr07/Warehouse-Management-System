<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/users.php';

include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/HourLogger/HourLoggerController.php';
if ($_COOKIE['user_id'] == '11'){
    $smarty->assign("users", getUsers());
}
if (isset($_GET['between'])){
    $dates = explode(" - ", $_GET['between']);
    $smarty->assign("date1", $dates[0]);
    $smarty->assign("date2", $dates[1]);
    if (isset($_GET['user_id'])){
        if ($_COOKIE['user_id'] == '11'){

            $pastSessions = json_decode(get_past_sessions($dates[0],$dates[1], $_GET['user_id']), true);
            $smarty->assign("HourLoggerUserID", $_GET['user_id']);
        } else {
            if ($_GET['user_id'] != $_COOKIE['user_id']){
                exit("You are not allowed here ;.....;");
            } else {
                $pastSessions = json_decode(get_past_sessions($dates[0],$dates[1], $_COOKIE['user_id']), true);
            }

        }
    } else {
        $smarty->assign("HourLoggerUserID", $_COOKIE['user_id']);
    }
} else {
    $d1 = date('m/01/Y');
    $d2 = date("m/t/Y");
    $smarty->assign("date1", $d1);
    $smarty->assign("date2", $d2);
    if (isset($_GET['user_id'])){
        if ($_COOKIE['user_id'] == '11'){
            $pastSessions = json_decode(get_past_sessions($d1,$d2, $_GET['user_id']), true);
            $smarty->assign("HourLoggerUserID", $_GET['user_id']);
        } else {
            if ($_GET['user_id'] != $_COOKIE['user_id']){
                exit("You are not allowed here ;.....;");
            } else {
                $pastSessions = json_decode(get_past_sessions($d1,$d2, $_COOKIE['user_id']), true);
            }
        }
    } else {
        $pastSessions = json_decode(get_past_sessions($d1,$d2, $_COOKIE['user_id']), true);
        $smarty->assign("HourLoggerUserID", $_COOKIE['user_id']);
    }

}

if (!is_null($pastSessions)){
    if (!array_key_exists('error', $pastSessions)){
        $total = 0;
        foreach ($pastSessions as $key => $value){
            $time1 = strtotime($value['date_check_in']);
            $time2 = strtotime($value['date_check_out']);
            $total += $time2-$time1;
        }
        $hours = floor($total/60/60);
        $minutes = floor(($total-($hours*60*60))/60);
        $seconds = floor(($total- ($hours*60*60))-($minutes*60));
        $smarty->assign("TotalForPeriod", array("hours"=>sprintf('%02d', $hours), "minutes"=>sprintf('%02d', $minutes), "seconds"=>sprintf('%02d', $seconds)));

        $smarty->assign("HourLoggerSessions", $pastSessions);
    } else {
        exit($pastSessions['error']);
    }
}

$smarty->display('cp/WMS/HourLogger.tpl');

?>