<?php
session_start();
include_once '../config/database.php';
include_once '../model/user.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$user = new User($db);
$user->setIdUser($_SESSION['idUser']);
$user->logout();
$_SESSION = array();
http_response_code(201);
exit(json_encode(array("message" => "Wylogowales sie")));