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
include_once '../../config/database.php';
include_once '../../model/deviceGroup.php';
//jezeli zalogowany
if ($_SESSION['userActive'] == true) {
    //polaczenie
    $database = new Database();
    $db = $database->getConnection();
    //pobierz grupy urzadzen
    $deviceGroup = new DeviceGroup($db);
    $deviceGroup->setIdFamily($_SESSION['idFamily']);
    $stmt = $deviceGroup->readByIdFamily();
    
    //wygeneruj tablice odpowiedzi
    $num = $stmt->rowCount();
    if($num > 0) {
        $deviceGroups_arr=array();
        $deviceGroups_arr["deviceGroups"]=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $deviceGroups_item = array(
                "idDeviceGroup" => $idDeviceGroup,
                "deviceGroupName" => $deviceGroupName
            );
            array_push($deviceGroups_arr["deviceGroups"], $deviceGroups_item);
        }
        http_response_code(200);
        echo json_encode($deviceGroups_arr);
    }
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Nie masz uprawnien.")));
}