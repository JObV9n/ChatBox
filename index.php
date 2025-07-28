<?php
// Function to get the client IP address
function getIP()
{

    $ip = $_SERVER['REMOTE_ADDR'];
    // if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    //     $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    // } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //     $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
    //     $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    // } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    //     $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    // } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
    //     $ipAddress = $_SERVER['HTTP_FORWARDED'];
    // } elseif (isset($_SERVER['REMOTE_ADDR'])) {
    //     $ipAddress = $_SERVER['REMOTE_ADDR'];
    // } else {
    //     $ipAddress = 'UNKNOWN';
    // }
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        if (isset($ip)) {
            return $ip;
        } else {
            echo "REMOTE ADDRESS is not reachable";
            // print_r($_SERVER['REMOTE_ADDR']);
            print_r($_SERVER['REMOTE_HOST']);
            print_r($_SERVER['HTTP_X_FORWARDED_FOR']);
        }
    }
}
print(getIP());
