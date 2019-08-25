<?php
class DeviceGroup
{
    //POLA KLASY
    private $conn;
    private $table_name = "DeviceGroups";

    private $idDeviceGroup;
    private $idFamily;
    private $deviceGroupName;
    private $temperatureDevice;

    //KONSTRUKTOR
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    //idDeviceGroup
    public function getIdDeviceGroup(): ?int {
        return $this->idDeviceGroup;
    }
    public function setIdDeviceGroup(int $idDeviceGroup): self {
        if (is_numeric($idDeviceGroup)) {
            $this->idDeviceGroup = $idDeviceGroup;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //idFamily
    public function getIdFamily(): ?int {
        return $this->idFamily;
    }
    public function setIdFamily(int $idFamily): self {
        if (is_numeric($idFamily)) {
            $this->idFamily = $idFamily;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //deviceGroupName
    public function getDeviceGroupName(): ?string {
        return $this->deviceGroupName;
    }
    public function setDeviceGroupName(string $deviceGroupName): self {
        if(empty($deviceGroupName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadz nazwe grupy!")));
        } else if (strlen($deviceGroupName) < 3 || strlen($deviceGroupName) > 32) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidłowa długość nazwy grupy.")));
        } else {
            $this->deviceGroupName = $deviceGroupName;
            return $this;
        }
    }
    //temperatureDevice
    public function getTemperatureDevice(): ?string {
        return $this->temperatureDevice;
    }
    public function setTemperatureDevice(string $temperatureDevice): self {
        if(empty($temperatureDevice)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nie wprowadziles urzadzenia")));
        } else if (strlen($temperatureDevice) != 8 && strlen($temperatureDevice) != 0) {
            http_response_code(400);
            exit(json_encode(array("message" => "Zle dane urzadzenia")));
        } else {
            $this->temperatureDevice = $temperatureDevice;
            return $this;
        }
    }
    //stworz grupe urzadzen w bazie danych
    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET idFamily = :idFamily,
                    deviceGroupName = :deviceGroupName";
        //przygotuj zapytanie
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idFamily=htmlspecialchars(strip_tags($this->idFamily));
        $this->deviceGroupName=htmlspecialchars(strip_tags($this->deviceGroupName));
        $stmt->bindParam(':idFamily', $this->idFamily);
        $stmt->bindParam(':deviceGroupName', $this->deviceGroupName);
        //wykonad zapytanie
        if($stmt->execute()) {
            $this->idDeviceGroup = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
    //
    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idDeviceGroup = :idDeviceGroup";
        //przygotuj zapytanie
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(':idDeviceGroup', $this->idDeviceGroup);
        //wykonad zapytanie
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idFamily = $row['idFamily'];
            $this->deviceGroupName = $row['deviceGroupName'];
            $this->temperatureDevice = $row['temperatureDevice'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
    // POBIERZ WSZYSTKIE GRUPY URZADZEN NALEZACE DO RODZINY
    public function readByIdFamily() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idFamily = ?";
        $stmt = $this->conn->prepare($query);
        $this->idFamily=htmlspecialchars(strip_tags($this->idFamily));
        $stmt->bindParam(1, $this->idFamily);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
    // POBIERZ GRUPE O NAZWIE x I RODZINE y
    public function readByName() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idFamily = ? AND deviceGroupName = ?";

        $stmt = $this->conn->prepare($query);

        $this->idFamily=htmlspecialchars(strip_tags($this->idFamily));
        $this->deviceGroupName=htmlspecialchars(strip_tags($this->deviceGroupName));
        $stmt->bindParam(1, $this->idFamily);
        $stmt->bindParam(2, $this->deviceGroupName);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idDeviceGroup = $row['idDeviceGroup'];
            $this->idFamily = $row['idFamily'];
            $this->temperatureDevice = $row['temperatureDevice'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function update() {
        $query = "UPDATE {$this->table_name}
                SET
                    deviceGroupName = :deviceGroupName,
                    temperatureDevice = :temperatureDevice
                WHERE idDeviceGroup = :idDeviceGroup";
        $stmt = $this->conn->prepare($query);
        $this->deviceGroupName=htmlspecialchars(strip_tags($this->deviceGroupName));
        $this->temperatureDevice=htmlspecialchars(strip_tags($this->temperatureDevice));
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(':deviceGroupName', $this->deviceGroupName);
        $stmt->bindParam(':temperatureDevice', $this->temperatureDevice);
        $stmt->bindParam(':idDeviceGroup', $this->idDeviceGroup);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->table_name}
                WHERE idDeviceGroup = :idDeviceGroup";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam('idDeviceGroup', $this->idDeviceGroup);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function groupNameExists() {
        $query = "SELECT deviceGroupName
                FROM {$this->table_name}
                WHERE idFamily = ? AND deviceGroupName = ?
                LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->idFamily=htmlspecialchars(strip_tags($this->idFamily));
        $this->deviceGroupName=htmlspecialchars(strip_tags($this->deviceGroupName));
        $stmt->bindParam(1, $this->idFamily);
        $stmt->bindParam(2, $this->deviceGroupName);
        if($stmt->execute()) {
            $num = $stmt->rowCount();
            if($num>0) return true;
            else return false;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}