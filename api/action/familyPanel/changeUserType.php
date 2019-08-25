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
include_once '../../model/user.php';
include_once '../../model/userPermission.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$data = json_decode(file_get_contents("php://input"));
$user = new User($db);
$user->setIdUser($data->idUser);
$user->setUserType($data->userType);
$userPermission = new UserPermission($db);
$userPermission->setIdUser($data->idUser);

//zmienamy typ usera na 1, a uprawnienia kasujemy
if($data->userType == 1) $userPermission->delete();
else if($data->userType == 2) $userPermission->create();
else {
    http_response_code(400);
    exit(json_encode(array("message" => "Niezgodne dane")));
}
$user->changeUserType();
http_response_code(200);
exit(json_encode(array("message" => "Zmieniono typ")));

// "idUser"    : idUser,
// "userType"  : userType