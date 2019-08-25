<?php
/**
 * Cel: Skrypt usuwania grupy urządzeń
 * 0. Pobierz dane oraz sprawdź czy użytkownik ma pozwolenie
 * 1. Pobierz wszystkie urządzenia przypisane do grupy
 * 2. Pobierz id grupy o nazwie "Niepogrupowane"
 * 3. Przepisz wszystkie pobrane wyżej urządzenia do niepogrupowanych
 * 4. Usuń grupę urządzeń
 */

session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../model/sceneGroup.php';

if ($_SESSION['userActive'] == true) {
    $data = json_decode(file_get_contents("php://input"));

    if($_SESSION['scenePermission'] == 1) {
        
        $database = new Database();
        $db = $database->getConnection();
        
        $sceneGroup = new SceneGroup($db);
        $sceneGroup->setSceneGroupName($data->sceneGroupName)
                    ->setIdFamily($_SESSION['idFamily'])
                    ->create();

        http_response_code(200);
        exit(json_encode(array("message" => "Dodano grupe scen.")));

    } else {
        http_response_code(400);
        exit(json_encode(array("message" => "Brak uprawnien")));
    }
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Nie jestes zalogowany")));    
}