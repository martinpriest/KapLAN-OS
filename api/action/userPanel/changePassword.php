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
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$data = json_decode(file_get_contents("php://input"));
$user = new User($db);
$user->setIdUser($_SESSION['idUser']);
$user->read();

if(password_verify($data->actualPassword, $user->getUserPassword()) == false) {
    http_response_code(400);
    exit(json_encode(array("message" => "Wpisales zle aktualne haslo")));
} else {
    $user->setUserPassword($data->newUserPassword);
    $user->changePassword();
    http_response_code(200);
    exit(json_encode(array("message" => "Zmieniłeś hasło")));
}
