<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../model/device.php';

if ($_SESSION['userActive'] == true) {
    $data = json_decode(file_get_contents("php://input"));
    
    $database = new Database();
    $db = $database->getConnection();
    
    $device = new Device($db);
    $device->setIdDevice($data->idDevice);
    $device->read();
    //dodac autoryzacje po rodzinie dla grupy
    if($_SESSION['devicePermission'] == 1) {
        $device_arr=array(
            "idDeviceGroup"   => $device->getIdDeviceGroup(),
            "deviceName" => $device->getDeviceName());

        http_response_code(200);
        exit(json_encode($device_arr));
    } else {
        http_response_code(400);
        exit(json_encode(array("message" => "Brak uprawnien")));
    }
}