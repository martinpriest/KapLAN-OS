<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../model/deviceGroup.php';

if ($_SESSION['userActive'] == true) {
    $data = json_decode(file_get_contents("php://input"));
    
    $database = new Database();
    $db = $database->getConnection();
    
    $deviceGroup = new DeviceGroup($db);
    $deviceGroup->setIdDeviceGroup($data->idDeviceGroup);
    $deviceGroup->read();

    if($_SESSION['idFamily'] == $deviceGroup->getIdFamily() && $_SESSION['deviceGroupPermission'] == 1) {
        $deviceGroup_arr=array(
            "deviceGroupName"   => $deviceGroup->getDeviceGroupName(),
            "temperatureDevice" => $deviceGroup->getTemperatureDevice());

        http_response_code(200);
        exit(json_encode($deviceGroup_arr));
    } else {
        http_response_code(400);
        exit(json_encode(array("message" => "Brak uprawnien")));
    }
}