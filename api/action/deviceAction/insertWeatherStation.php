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

$temperatureMeasurement = new DeviceMeasurement($db);
$temperatureMeasurement->setIdMeasurementType(1)
                ->setDeviceMeasurementValue($data->temperature)
                ->setIdDevice($data->idDevice)
                ->create();

$luxMeasurement = new DeviceMeasurement($db);
$luxMeasurement->setIdMeasurementType(2)
                ->setDeviceMeasurementValue($data->lux)
                ->setIdDevice($data->idDevice)
                ->create();

$humidityMeasurement = new DeviceMeasurement($db);
$humidityMeasurement->setIdMeasurementType(3)
                ->setDeviceMeasurementValue($data->humidity)
                ->setIdDevice($data->idDevice)
                ->create();

$preassureMeasurement = new DeviceMeasurement($db);
$preassureMeasurement->setIdMeasurementType(4)
                ->setDeviceMeasurementValue($data->preassure)
                ->setIdDevice($data->idDevice)
                ->create();

http_response_code(200);
exit(json_encode(array("message" => "Dodano pomiary")));