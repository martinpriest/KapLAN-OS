<?php
//USTAW NAGLOWKI
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// INCLUDUJ POTRZEBNE PLIKI
include_once '../../config/database.php';
include_once '../../model/deviceMeasurement.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$data = json_decode(file_get_contents("php://input"));

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
            "idMeasurementType" => $idMeasurementType,
            "deviceMeasurementValue" => $deviceMeasurementValue
        );
        array_push($deviceMeasurements_arr["deviceMeasurements"], $deviceMeasurement_item);
    }
    http_response_code(200);
    exit(json_encode($deviceMeasurements_arr));
} else {
    http_response_code(200);
    exit(json_encode(array("message" => "Dla urzadzenia {$data->idDevice}: Brak wynikow.")));
}