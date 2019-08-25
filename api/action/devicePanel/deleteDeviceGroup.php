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
include_once '../../model/deviceGroup.php';
include_once '../../model/device.php';

if ($_SESSION['userActive'] == true) {
    $data = json_decode(file_get_contents("php://input"));
    
    $database = new Database();
    $db = $database->getConnection();
    
    $deviceGroup = new DeviceGroup($db);
    $deviceGroup->setIdDeviceGroup($data->idDeviceGroup);
    $deviceGroup->read();

    if($_SESSION['idFamily'] == $deviceGroup->getIdFamily() && $_SESSION['deviceGroupPermission'] == 1) {
        $devices = new Device($db);
        $devices->setIdDeviceGroup($data->idDeviceGroup);
        
        $stmt = $devices->readByIdDeviceGroup();
        $num = $stmt->rowCount();
        if($num > 0) {
            
            $unorderedDeviceGroup = new DeviceGroup($db);
            $unorderedDeviceGroup->setIdFamily($_SESSION['idFamily'])
                                    ->setDeviceGroupName("Niepogrupowane")
                                    ->readByName();
            
            $idUnorderedDeviceGroup = $unorderedDeviceGroup->getIdDeviceGroup();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $device = new Device($db);
                $device->setIdDevice($idDevice)->read();
                $device->setIdDeviceGroup($idUnorderedDeviceGroup)->update();
            }
        }

        $deviceGroup->delete();
        http_response_code(200);
        exit(json_encode(array("message" => "Usunieto grupe.")));

    } else {
        http_response_code(400);
        exit(json_encode(array("message" => "Brak uprawnien")));
    }
}