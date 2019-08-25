<?php
session_start();
//USTAW NAGLOWKI
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// INCLUDUJ POTRZEBNE PLIKI
include_once '../../config/database.php';
include_once '../../model/userPermission.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$data = json_decode(file_get_contents("php://input"));
$userPermission = new UserPermission($db);
$userPermission->setIdUser($data->idUser);
$userPermission->read();

if($userPermission->getScenePermission() == 1) $userPermission->setScenePermission(0);
else $userPermission->setScenePermission(1);

$userPermission->update();

http_response_code(200);
exit(json_encode(array("message" => "Zmieniono")));