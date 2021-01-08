<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
function checkIn($data){
    try{
        $long = $data['longitude'];
        $lat = $data['latitude'];
        $mobile = $data['mobile'];
        $user = $data['user_id'];
        $ip = json_encode(array("REMOTE_ADDR"=>$_SERVER['REMOTE_ADDR'], "HTTP_X_FORWARDED_FOR"=>$_SERVER['HTTP_X_FORWARDED_FOR'], "HTTP_X_REAL_IP"=>$_SERVER['HTTP_X_REAL_IP']), JSON_THROW_ON_ERROR );
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*hour_logger*} (user_id, longitude, latitude, mobile, ip) VALUES ('$user', '$long', '$lat', '$mobile', '$ip')"));
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
                SET longitude_out='$long', latitude_out='$lat', mobile_out='$mobile', ip_out='$ip', date_check_out=CURRENT_TIMESTAMP() WHERE user_id='$user' AND id='$id'"));
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

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['checkIn']) && $data['checkIn'] == true){
    checkIn($data);
}

if (isset($data['checkOut']) && $data['checkOut'] == true){
    checkOut($data);
}