<?php
session_start();
//ustawienie sesji na testa

//USTAW NAGLOWKI
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// INCLUDUJ POTRZEBNE PLIKI
include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/userPermission.php';
include_once '../model/family.php';
include_once '../model/deviceGroup.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$data = json_decode(file_get_contents("php://input"));
$user = new User($db);
$user->setUserLogin($data->userLogin)
    ->setUserEmail($data->userEmail)
    ->setUserPassword($data->userPassword);

// WALIDACJA DANYCH
if ($user->loginExists()) {
    http_response_code(400);
    exit(json_encode(array("message" => "Podany login jest uzywany."))); 
} else if ($user->emailExists()) {
    http_response_code(400);
    exit(json_encode(array("message" => "Podany email jest uzywany.")));
}

if (isset($_SESSION['idFamily']))
{
    $user->setIdFamily($_SESSION['idFamily']);
    $user->setUserType($data->userType);
} else {
    $user->setUserType(1);
    $family = new Family($db);
    $family->setFamilyName($data->familyName);
    if($family->create()) {
        $user->setIdFamily($family->getIdFamily());
        echo json_encode(array("message" => "Utworzono nowa rodzine"));

        $deviceGroup = new DeviceGroup($db);
        $deviceGroup->setIdFamily($family->getIdFamily())
                    ->setDeviceGroupName("Niepogrupowane")
                    ->create();

    } else {
        http_response_code(503);
        exit(json_encode(array("message" => "Blad bazy danych")));
    }
}

if($user->create()) {
    if($user->getUserType() == 2) {
        $userPermission = new UserPermission($db);
        $userPermission->setIdUser($user->getIdUser());
        $userPermission->create();
    }
    //Tworzenie pierwszej grupy

    // WYSYLANIE MAILI https://devcorner.pl/wysylac-maile-serwera-lokalnego-xampp/

    $emailHeaders  = "MIME-Version: 1.0\r\n";
    $emailHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $emailHeaders .= "From: KapLAN OS \n";
    $emailTitle = "Witaj {$user->getUserLogin()} w KapLAN OS.";
    $verificationLink = "inzynierka.test/api/action/activateUser.php?code={$user->getIdUser()}";
    $emailBody = "Link do aktywacji: {$verificationLink} </br> <a href='{$verificationLink}' target='_blank' style='padding:1em; font-weight:bold; background-color:blue; color:#fff;'>Activate account</a>";
    if(mail($user->getUserEmail(), $emailTitle, $emailBody, $emailHeaders)) {
        http_response_code(200);
        exit(json_encode(array("message" => "Utworzono nowego uzytkownika")));
    } else exit(json_encode(array("message" => "Podczas wysylania maila nastapil blad")));
} else {
    http_response_code(503);
    exit(json_encode(array("message" => "Blad bazy danych")));   
}