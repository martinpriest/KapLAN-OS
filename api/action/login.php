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
include_once '../model/userLoginHistory.php';
include_once '../model/userPermission.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$data = json_decode(file_get_contents("php://input"));

$user = new User($db);
$user->setUserLogin($data->userLogin);

if($user->loginExists() == false) {
    http_response_code(400);
    exit(json_encode(array("message" => "Taki uzytkownik nie istnieje.")));
    
} else {
    $user->read();
    if($user->getUserActivated() == 0) {
        http_response_code(400);
        exit(json_encode(array("message" => "Aktywuj swoje konto")));
    } else if(password_verify($data->userPassword, $user->getUserPassword()) == false) {
        http_response_code(400);
        exit(json_encode(array("message" => "Wpisales zle haslo")));
    } else {
        $user->login();
        $_SESSION['userActive'] = true;
        $_SESSION['idUser'] = $user->getIdUser();
        $_SESSION['idFamily'] = $user->getIdFamily();
        $_SESSION['userType'] = $user->getUserType();
        
        $userLoginHistory = new UserLoginHistory($db);
        $userLoginHistory->setIdUser($_SESSION['idUser']);
        $userLoginHistory->create();

        if($_SESSION['userType'] == 1) {
            $_SESSION['deviceGroupPermission'] = 1;
            $_SESSION['devicePermission'] = 1;
            $_SESSION['scenePermission'] = 1;
        } else {
            $userPermission = new UserPermission($db);
            $userPermission->setIdUser($_SESSION['idUser']);
            $userPermission->read();
            $_SESSION['deviceGroupPermission'] = $userPermission->getDeviceGroupPermission();
            $_SESSION['devicePermission'] = $userPermission->getDevicePermission();
            $_SESSION['scenePermission'] = $userPermission->getScenePermission();
        }

        http_response_code(200);
        exit(json_encode(array("message" => "Zalogowales sie")));
    }
}