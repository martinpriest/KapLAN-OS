<?php
/**
 * 1. Pobierz od uzytkownika ktory pomiar do negacji
 * 2. Ustal które urządzenie
 * 3. Ustal z której grupy jest urządzenie
 * 4. Ustal czy sesyjny uzytkownik ma do tego dostep
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
    // AUTORYZACJA
    $deviceMeasurement = new DeviceMeasurement($db);
    $deviceMeasurement->setIdDeviceMeasurement($data->idDeviceMeasurement)->readById();
    
    $device = new Device($db);
    $device->setIdDevice($deviceMeasurement->getIdDevice())->read();

    $deviceGroup = new DeviceGroup($db);
    $deviceGroup->setIdDeviceGroup($device->getIdDeviceGroup())->read();

    if($_SESSION['idFamily'] == $deviceGroup->getIdFamily() && $_SESSION['devicePermission'] == 1) {
        $newDeviceMeasurement = new DeviceMeasurement($db);
        $newDeviceMeasurement->setIdMeasurementType($deviceMeasurement->getIdMeasurementType())
                            ->setIdDevice($deviceMeasurement->getIdDevice())
                            ->setDeviceMeasurementValue($data->deviceMeasurementValue)
                            ->create();
        http_response_code(200);
        exit(json_encode(array("message" => "Dodano pomiar")));
    } else {
        http_response_code(400);
        exit(json_encode(array("message" => "Brak uprawnien")));
    }
}