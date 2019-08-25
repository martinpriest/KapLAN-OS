<?php
class MeasurementType {
    private $conn;
    private $table_name = "MeasurementType";

    private $idMeasuremenType;
    private $measurementTypeName;

    //KONSTRUKTOR
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
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
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //measurementTypeName
    public function getMeasurementTypeName(): ?string {
        return $this->measurementTypeName;
    }

    public function setMeasurementTypeName(string $measurementTypeName): self {
        if (empty($measurementTypeName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź typ urzadzenia!")));
        } else if (is_numeric($measurementTypeName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Typ urzadzenia nie może być liczbą!")));
        } else if (strlen($measurementTypeName) > 32) {
            http_response_code(400);
            exit(json_encode(array("message" => "Typ urzadzenia musi miec mniej niz 32 znaków!")));
        } else {
            $this->measurementTypeName = $measurementTypeName;
            return $this;
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET measurementTypeName = :measurementTypeName";
        $stmt = $this->conn->prepare($query);
        $this->measurementTypeName=htmlspecialchars(strip_tags($this->measurementTypeName));
        $stmt->bindParam(':measurementTypeName', $this->measurementTypeName);
        if($stmt->execute()) {
            $this->idMeasurementType = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        //zapytanie
        $query = "SELECT * FROM {$this->table_name}
                WHERE idMeasurementType = :idMeasurementType";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $stmt->bindParam('idMeasurementType', $this->idMeasurementType);
        //wykonanie zapytania
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->measurementTypeName = $row['measurementTypeName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function update() {
        //zapytanie
        $query = "UPDATE {$this->table_name}
                SET measurementTypeName = :measurementTypeName
                WHERE idMeasurementType = :idMeasurementType";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $stmt->bindParam('idMeasurementType', $this->idMeasurementType);
        //wykonanie zapytania
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->measurementTypeName = $row['measurementTypeName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->table_name}
                WHERE idMeasurementType = :idMeasurementType";
        $stmt = $this->conn->prepare($query);
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $stmt->bindParam('idMeasurementType', $this->idMeasurementType);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}