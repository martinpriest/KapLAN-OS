<?php
class DeviceType {
    //polaczenie i nazwa tabeli
    private $conn;
    private $table_name = "DeviceTypes";
    //skladowe
    private $idDeviceType;
    private $deviceTypeName;

    //KONSTRUKTOR
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
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
    //deviceTypeName
    public function getDeviceTypeName(): ?string {
        return $this->deviceTypeName;
    }

    public function setDeviceTypeName(string $deviceTypeName): self {
        if (empty($deviceTypeName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź typ urzadzenia!")));
        } else if (is_numeric($deviceTypeName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Typ urzadzenia nie może być liczbą!")));
        } else if (strlen($deviceTypeName) > 32) {
            http_response_code(400);
            exit(json_encode(array("message" => "Typ urzadzenia musi miec mniej niz 32 znaków!")));
        } else {
            $this->deviceTypeName = $deviceTypeName;
            return $this;
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET deviceTypeName = :deviceTypeName";
        $stmt = $this->conn->prepare($query);
        $this->deviceTypeName=htmlspecialchars(strip_tags($this->deviceTypeName));
        $stmt->bindParam(':deviceTypeName', $this->deviceTypeName);
        if($stmt->execute()) {
            $this->idDeviceType = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        //zapytanie
        $query = "SELECT * FROM {$this->table_name}
                WHERE idDeviceType = :idDeviceType";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idDeviceType = htmlspecialchars(strip_tags($this->idDeviceType));
        $stmt->bindParam('idDeviceType', $this->idDeviceType);
        //wykonanie zapytania
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->deviceTypeName = $row['deviceTypeName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function update() {
        //zapytanie
        $query = "UPDATE {$this->table_name}
                SET deviceTypeName = :deviceTypeName
                WHERE idDeviceType = :idDeviceType";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idDeviceType = htmlspecialchars(strip_tags($this->idDeviceType));
        $stmt->bindParam('idDeviceType', $this->idDeviceType);
        //wykonanie zapytania
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->deviceTypeName = $row['deviceTypeName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->table_name}
                WHERE idDeviceType = :idDeviceType";
        $stmt = $this->conn->prepare($query);
        $this->idDeviceType = htmlspecialchars(strip_tags($this->idDeviceType));
        $stmt->bindParam('idDeviceType', $this->idDeviceType);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}