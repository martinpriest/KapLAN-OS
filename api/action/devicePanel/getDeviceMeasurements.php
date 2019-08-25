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
include_once '../../model/deviceMeasurement.php';
$data = json_decode(file_get_contents("php://input"));
//jezeli zalogowany
if ($_SESSION['userActive'] == true) {
    //polaczenie
    $database = new Database();
    $db = $database->getConnection();
    //pobierz urzadzenia
    $deviceMeasurement = new DeviceMeasurement($db);
    $deviceMeasurement->setIdDevice($data->idDevice);
    $stmt = $deviceMeasurement->read();
    
    //wygeneruj tablice odpowiedzi
    $num = $stmt->rowCount();
    if($num > 0) {
        $deviceMeasurements_arr=array();
        $deviceMeasurements_arr["deviceMeasurements"]=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $deviceMeasurement_item = array(
                "idDeviceMeasurement" => $idDeviceMeasurement,
                "idMeasurementType" => $idMeasurementType,
                "deviceMeasurementValue" => $deviceMeasurementValue,
                "deviceMeasurementDate" => $deviceMeasurementDate
            );
            array_push($deviceMeasurements_arr["deviceMeasurements"], $deviceMeasurement_item);
        }
        http_response_code(200);
        exit(json_encode($deviceMeasurements_arr));
    } else {
        http_response_code(200);
        exit(json_encode(array("message" => "Dla urzadzenia {$data->idDevice}: Brak wynikow.")));
    }
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Nie masz uprawnien.")));
}