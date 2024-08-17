<?php
    define("BASEURL", "https://rootcapture.com/");
    // date_default_timezone_set("USA/Arizona");
    date_default_timezone_set("MST");
    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    $ipaddress = getUserIpAddr();
    $ipInfo = file_get_contents('http://ip-api.com/json/' . $ipaddress);
    
    $ipInfo = json_decode($ipInfo);
    if(isset($ipInfo->timezone) && $ipInfo->timezone!=''){
        date_default_timezone_set($ipInfo->timezone);
    }
    
    define("DATETIME", date('Y-m-d H:i:s'));
?>