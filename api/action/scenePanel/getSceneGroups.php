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
include_once '../../model/sceneGroup.php';
//jezeli zalogowany
if ($_SESSION['userActive'] == true && $_SESSION['scenePermission'] == 1) {
    //polaczenie
    $database = new Database();
    $db = $database->getConnection();
    //pobierz grupy urzadzen
    $sceneGroup = new SceneGroup($db);
    $sceneGroup->setIdFamily($_SESSION['idFamily']);
    $stmt = $sceneGroup->readByIdFamily();
    
    //wygeneruj tablice odpowiedzi
    $num = $stmt->rowCount();
    if($num > 0) {
        $sceneGroups_arr=array();
        $sceneGroups_arr["sceneGroups"]=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $sceneGroup_item = array(
                "idSceneGroup" => $idSceneGroup,
                "sceneGroupName" => $sceneGroupName
            );
            array_push($sceneGroups_arr["sceneGroups"], $sceneGroup_item);
        }
        http_response_code(200);
        exit(json_encode($sceneGroups_arr));
    }
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Nie masz uprawnien.")));
}