<?php
session_start();
// unset($_SESSION['idFamily']);
//USTAW NAGLOWKI
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// INCLUDUJ POTRZEBNE PLIKI
include_once '../../config/database.php';
include_once '../../model/device.php';
$data = json_decode(file_get_contents("php://input"));
//jezeli zalogowany
if ($_SESSION['userActive'] == true) {
    //polaczenie
    $database = new Database();
    $db = $database->getConnection();
    //pobierz urzadzenia
    $device = new Device($db);
    $device->setIdDeviceGroup($data->idDeviceGroup);
    $stmt = $device->readByIdDeviceGroup();
    
    //wygeneruj tablice odpowiedzi
    $num = $stmt->rowCount();
    if($num > 0) {
        $devices_arr=array();
        $devices_arr["devices"]=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $device_item = array(
                "idDevice" => $idDevice,
                "deviceName" => $deviceName,
                "idDeviceType" => $idDeviceType,
                "idDeviceGroup" => $idDeviceGroup,
                "displayOrder" => $displayOrder,
                "deviceActive" => $deviceActive
            );
            array_push($devices_arr["devices"], $device_item);
        }
        http_response_code(200);
        exit(json_encode($devices_arr));
    } else {
        http_response_code(200);
        exit(json_encode(array("message" => "Brak urzadzen w grupie")));
    }
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Nie masz uprawnien.")));
}