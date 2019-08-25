<?php
/**
 * 1. Pobierz urzadzenia relayowe
 * 2. Ustaw relaye tych urzadzen na wartosc wprowadzona przez uzytkownika
 */

session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../model/deviceGroup.php';
include_once '../../model/device.php';
include_once '../../model/deviceMeasurement.php';

if ($_SESSION['userActive'] == true) {
    $data = json_decode(file_get_contents("php://input"));
    
    $database = new Database();
    $db = $database->getConnection();
    
    $deviceGroup = new DeviceGroup($db);
    $deviceGroup->setIdDeviceGroup($data->idDeviceGroup);
    $deviceGroup->read();

    if($_SESSION['idFamily'] == $deviceGroup->getIdFamily() && $_SESSION['deviceGroupPermission'] == 1) {
        $devices = new Device($db);
        $devices->setIdDeviceGroup($data->idDeviceGroup);
        
        $stmt = $devices->getAllLightDevices(); //NAPISAC TA FUNKCJE W MODELU
        $num = $stmt->rowCount();
        if($num > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $deviceMeasurement = new DeviceMeasurement($db);
                $deviceMeasurement->setIdDevice($idDevice);
                $deviceMeasurement->setDeviceMeasurementValue($data->measurementValue);
                $deviceMeasurement->setIdMeasurementType(11)->create();
            }
            http_response_code(200);
            exit(json_encode(array("message" => "Wykonano akcje")));
        } else {
            http_response_code(200);
            exit(json_encode(array("message" => "Brak wyników.")));
        }
    } else {
        http_response_code(400);
        exit(json_encode(array("message" => "Brak uprawnien")));
    }
}