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
include_once '../config/database.php';
include_once '../model/family.php';
include_once '../model/user.php';
include_once '../model/userPermission.php';
//jezeli zalogowany
if ($_SESSION['userActive'] == true) {
    //polaczenie
    $database = new Database();
    $db = $database->getConnection();
    //pobierz dane o rodzinie
    $family = new Family($db);
    $family->setIdFamily($_SESSION['idFamily']);
    $family->read();
    //pobierz wszystkich czlonkow rodziny 
    $user = new User($db);
    $user->setIdFamily($_SESSION['idFamily']);
    $stmt = $user->readByIdFamily();
    
    //wygeneruj tablice odpowiedzi
    $num = $stmt->rowCount();
    if($num > 0) {
        $family_arr=array();
        $family_arr["familyName"] = $family->getFamilyName();
        $family_arr["familyMembers"]=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            //odczytaj uprawnienia uzytkownika
            if($userType == 1) {
                $deviceGroupPermission = 1;
                $devicePermission = 1;
                $scenePermission = 1;
            } else {
                $userPermission = new UserPermission($db);
                $userPermission->setIdUser($idUser);
                $userPermission->read();
                $deviceGroupPermission = $userPermission->getDeviceGroupPermission();
                $devicePermission = $userPermission->getDevicePermission();
                $scenePermission = $userPermission->getScenePermission();
            }
            $user_item = array(
                "idUser" => $idUser,
                "userLogin" => $userLogin,
                "userType" => $userType,
                "deviceGroupPermission" => $deviceGroupPermission,
                "devicePermission" => $devicePermission,
                "scenePermission" => $scenePermission
            );
            array_push($family_arr["familyMembers"], $user_item);
        }
        http_response_code(200);
        echo json_encode($family_arr);
    }
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Nie masz uprawnien.")));
}