<?php
class DeviceMeasurement {
    //polaczenie i nazwa tabeli
    private $conn;
    private $table_name = "DeviceMeasurements";
    //skladowe
    private $idDeviceMeasurement;
    private $idDevice;
    private $idMeasurementType;
    private $deviceMeasurementValue;

    //KONSTRUKTOR
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    //idDeviceMeasurement
    public function getIdDeviceMeasurement(): ?int {
        return $this->idDeviceMeasurement;
    }
    public function setIdDeviceMeasurement(int $idDeviceMeasurement): self {
        if (is_numeric($idDeviceMeasurement)) {
            $this->idDeviceMeasurement = $idDeviceMeasurement;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych1")));
        }
    }
    //idDevice
    public function getIdDevice(): ?string {
        return $this->idDevice;
    }
    public function setIdDevice(string $idDevice): self {
        if (is_string($idDevice) && !empty($idDevice) && strlen($idDevice) == 8) {
            $this->idDevice = $idDevice;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych2")));
        }
    }
    //idMeasurementType
    public function getIdMeasurementType(): ?int {
        return $this->idMeasurementType;
    }
    public function setIdMeasurementType(int $idMeasurementType): self {
        if (is_numeric($idMeasurementType)) {
            $this->idMeasurementType = $idMeasurementType;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych3")));
        }
    }
    //deviceMeasurementValue
    public function getDeviceMeasurementValue(): ?float {
        return $this->deviceMeasurementValue;
    }
    public function setDeviceMeasurementValue(float $deviceMeasurementValue): self {
        if (is_numeric($deviceMeasurementValue)) {
            $this->deviceMeasurementValue = $deviceMeasurementValue;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych4")));
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idDevice = :idDevice,
                    idMeasurementType = :idMeasurementType,
                    deviceMeasurementValue = :deviceMeasurementValue";
        $stmt = $this->conn->prepare($query);
        $this->idDevice = htmlspecialchars(strip_tags($this->idDevice));
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $this->deviceMeasurementValue = htmlspecialchars(strip_tags($this->deviceMeasurementValue));
        
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':idMeasurementType', $this->idMeasurementType);
        $stmt->bindParam(':deviceMeasurementValue', $this->deviceMeasurementValue);

        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
    // WYBIERZ WSZYSTKIE OSTATNIE POMIARY URZADZNIE, Z KAZDEGO TYPU PO JEDNYM
    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idDeviceMeasurement IN (
                    SELECT MAX(idDeviceMeasurement)
                    FROM {$this->table_name}
                    WHERE idDevice = :idDevice
                    GROUP BY idMeasurementType)";
        $stmt = $this->conn->prepare($query);
        $this->idDevice=htmlspecialchars(strip_tags($this->idDevice));
        $stmt->bindParam(':idDevice', $this->idDevice);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readById() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idDeviceMeasurement = :idDeviceMeasurement";

        $stmt = $this->conn->prepare($query);

        $this->idDeviceMeasurement=htmlspecialchars(strip_tags($this->idDeviceMeasurement));
        $stmt->bindParam(':idDeviceMeasurement', $this->idDeviceMeasurement);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idDevice = $row['idDevice'];
            $this->idMeasurementType = $row['idMeasurementType'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}