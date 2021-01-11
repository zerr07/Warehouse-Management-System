<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/HourLogger/HourLoggerController.php';
if (isset($_GET['between'])){
    $dates = explode(" - ", $_GET['between']);
    $smarty->assign("date1", $dates[0]);
    $smarty->assign("date2", $dates[1]);
    $pastSessions = json_decode(get_past_sessions($dates[0],$dates[1]), true);

} else {
    $d1 = date('m/d/Y', strtotime('-30 days'));
    $d2 = date("m/d/Y");
    $smarty->assign("date1", $d1);
    $smarty->assign("date2", $d2);
    $pastSessions = json_decode(get_past_sessions($d1,$d2), true);

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