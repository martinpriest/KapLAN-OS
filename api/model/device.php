<?php
class Device {
    //polaczenie i nazwa tabeli
    private $conn;
    private $table_name = "Devices";
    //skladowe
    private $idDevice;
    private $deviceName;
    private $idDeviceType;
    private $idDeviceGroup;
    private $displayOrder;
    private $deviceActive;

    //KONSTRUKTOR
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    //idDevice
    public function getIdDevice(): ?string {
        return $this->idDeviceType;
    }
    public function setIdDevice(string $idDevice): self {
        if (is_string($idDevice) && !empty($idDevice) && strlen($idDevice) == 8) {
            $this->idDevice = $idDevice;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Model: Devices, Nieprawidlowe ID urzadzenia.")));
        }
    }
    //deviceName
    public function getDeviceName(): ?string {
        return $this->deviceName;
    }

    public function setDeviceName(string $deviceName): self {
        if (empty($deviceName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź login!")));
        } else if (is_numeric($deviceName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Login nie może być liczbą!")));
        } else if (strlen($deviceName) < 6 || strlen($deviceName) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa urządzenia musi mieć od 6 do 32 znaków!")));
        } else {
            $this->deviceName = $deviceName;
            return $this;
        }
    }
    //idDeviceType
    public function getIdDeviceType(): ?int {
        return $this->idDeviceType;
    }
    public function setIdDeviceType(int $idDeviceType): self {
        if (is_numeric($idDeviceType)) {
            $this->idDeviceType = $idDeviceType;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
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
    //displayOrder
    public function getDisplayOrder(): ?int {
        return $this->displayOrder;
    }
    public function setDisplayOrder(int $displayOrder): self {
        if (is_numeric($displayOrder)) {
            $this->displayOrder = $displayOrder;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //deviceActive
    public function getDeviceActive(): ?int {
        return $this->deviceActive;
    }
    public function setDeviceActive(int $deviceActive): self {
        if (is_numeric($deviceActive)) {
            $this->deviceActive = $deviceActive;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idDevice = :idDevice,
                    deviceName = :deviceName,
                    idDeviceType = :idDeviceType,
                    idDeviceGroup = :idDeviceGroup,
                    displayOrder = 0,
                    deviceActive = 1";
        $stmt = $this->conn->prepare($query);
        $this->idDevice=htmlspecialchars(strip_tags($this->idDevice));
        $this->deviceName=htmlspecialchars(strip_tags($this->deviceName));
        $this->idDeviceType=htmlspecialchars(strip_tags($this->idDeviceType));
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':deviceName', $this->deviceName);
        $stmt->bindParam(':idDeviceType', $this->idDeviceType);
        $stmt->bindParam(':idDeviceGroup', $this->idDeviceGroup);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idDevice = :idDevice";
        $stmt = $this->conn->prepare($query);
        $this->idDevice=htmlspecialchars(strip_tags($this->idDevice));
        $stmt->bindParam(':idDevice', $this->idDevice);
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->deviceName = $row['deviceName'];
            $this->idDeviceType = $row['idDeviceType'];
            $this->idDeviceGroup = $row['idDeviceGroup'];
            $this->displayOrder = $row['displayOrder'];
            $this->deviceActive = $row['deviceActive'];
            return $this;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readByIdDeviceGroup() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idDeviceGroup = ?";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(1, $this->idDeviceGroup);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readByTemperatureType() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idDeviceGroup = ?
                AND (idDeviceType = 4 OR idDeviceType = 6)";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(1, $this->idDeviceGroup);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function getAllRelayDevices() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idDeviceGroup = ?
                AND (idDeviceType = 1 OR idDeviceType = 2 OR idDeviceType = 3)";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(1, $this->idDeviceGroup);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function getAllBlindDevices() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idDeviceGroup = ?
                AND idDeviceType = 9";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(1, $this->idDeviceGroup);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function getAllLightDevices() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idDeviceGroup = ?
                AND idDeviceType = 8";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $stmt->bindParam(1, $this->idDeviceGroup);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
    
    public function update() {
        $query = "UPDATE {$this->table_name}
                SET
                    deviceName = :deviceName,
                    idDeviceGroup = :idDeviceGroup,
                    displayOrder = :displayOrder,
                    deviceActive = :deviceActive
                WHERE idDevice = :idDevice";
        $stmt = $this->conn->prepare($query);
        $this->idDevice=htmlspecialchars(strip_tags($this->idDevice));
        $this->deviceName=htmlspecialchars(strip_tags($this->deviceName));
        $this->idDeviceGroup=htmlspecialchars(strip_tags($this->idDeviceGroup));
        $this->displayOrder=htmlspecialchars(strip_tags($this->displayOrder));
        $this->deviceActive=htmlspecialchars(strip_tags($this->deviceActive));
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':deviceName', $this->deviceName);
        $stmt->bindParam(':idDeviceGroup', $this->idDeviceGroup);
        $stmt->bindParam(':displayOrder', $this->displayOrder);
        $stmt->bindParam(':deviceActive', $this->deviceActive);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->table_name}
                WHERE idDevice = :idDevice";
        $stmt = $this->conn->prepare($query);
        $this->idDevice = htmlspecialchars(strip_tags($this->idDevice));
        $stmt->bindParam(':idDevice', $this->idDevice);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}