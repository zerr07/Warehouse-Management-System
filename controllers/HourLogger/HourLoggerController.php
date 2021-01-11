<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
function checkIn($data){
    try{
        $long = $data['longitude'];
        $lat = $data['latitude'];
        $mobile = $data['mobile'];
        $user = $data['user_id'];
        $ip = json_encode(array("REMOTE_ADDR"=>$_SERVER['REMOTE_ADDR'], "HTTP_X_FORWARDED_FOR"=>$_SERVER['HTTP_X_FORWARDED_FOR'], "HTTP_X_REAL_IP"=>$_SERVER['HTTP_X_REAL_IP']), JSON_THROW_ON_ERROR );
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*hour_logger*} (user_id, longitude, latitude, mobile, ip, date_check_in) VALUES ('$user', '$long', '$lat', '$mobile', '$ip', DATE_ADD(now() , INTERVAL 1 HOUR))"));
        if ($q){
            exit (json_encode(array("success"=>"checkin")));
        } else {
            exit(json_encode(array("error"=>"SQL insert error")));
        }
    } catch (JsonException $e){
        exit(json_encode(array("error"=>"JSON in error")));
    }

}
function checkOut($data){

    try{
        $long = $data['longitude'];
        $lat = $data['latitude'];
        $mobile = $data['mobile'];
        $user = $data['user_id'];
        $ip = json_encode(array("REMOTE_ADDR"=>$_SERVER['REMOTE_ADDR'], "HTTP_X_FORWARDED_FOR"=>$_SERVER['HTTP_X_FORWARDED_FOR'], "HTTP_X_REAL_IP"=>$_SERVER['HTTP_X_REAL_IP']), JSON_THROW_ON_ERROR );

        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM {*hour_logger*} WHERE date_check_out IS NULL AND user_id='$user'"));
        if ($q){
            if ($q->num_rows > 1){
                exit(json_encode(array("error"=>"There are more that one active session for this user. Contact administrator immediately!")));
            } else {
                $id = $q->fetch_assoc()['id'];
                $q1 = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*hour_logger*} 
                SET longitude_out='$long', latitude_out='$lat', mobile_out='$mobile', ip_out='$ip', date_check_out=DATE_ADD(now() , INTERVAL 1 HOUR) WHERE user_id='$user' AND id='$id'"));
                if ($q1){
                    exit(json_encode(array("success"=>"checkout")));
                } else {
                    exit(json_encode(array("error"=>"SQL update error")));
                }
            }
        } else {
            exit("SQL select error");
        }

    } catch (JsonException $e){
        exit(json_encode(array("error"=>"JSON out error")));
    }
}

function get_active_session(){
    $id = $_COOKIE['user_id'];
    if (!array_key_exists('user_id', $_COOKIE)){
        return json_encode(array("error"=>"No user id found. Contact administrator"));
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*hour_logger*} WHERE date_check_out IS NULL AND user_id='$id'"));
    if ($q){
        if ($q->num_rows > 1){
            return json_encode(array("error"=>"There are more that one active session for this user. Contact administrator immediately!"));
        } else {
            return json_encode($q->fetch_assoc());
        }
    } else {
        return json_encode(array("error"=>"SQL error"));
    }
}



$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['checkIn']) && $data['checkIn'] == true){
    checkIn($data);
}
if (isset($_GET['getHourLogger'])){
    exit(get_active_session());
}
if (isset($data['checkOut']) && $data['checkOut'] == true){
    checkOut($data);
}