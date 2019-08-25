<?php
session_start();
//USTAW NAGLOWKI
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// INCLUDUJ POTRZEBNE PLIKI
include_once '../config/database.php';
include_once '../model/user.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->setIdUser($_SESSION['idUser']);
$user->read();

http_response_code(200);
exit(json_encode(array(
    "userLogin" => "{$user->getUserLogin()}",
    "userEmail" => "{$user->getUserEmail()}",
    "userType" => "{$user->getUserType()}"
)));
