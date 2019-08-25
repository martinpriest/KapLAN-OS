<?php
class SceneCondition {
    private $conn;
    private $table_name = "SceneConditions";

    private $idSceneCondition;
    private $idScene;
    private $idDevice;
    private $idMeasurementType;
    private $conditionSign;
    private $measurementValue;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    //idSceneCondition
    public function getIdSceneCondition(): int {
        return $this->idSceneCondition;
    }
    public function setIdSceneCondition(int $idSceneCondition): self {
        if (is_numeric($idSceneCondition)) {
            $this->idSceneCondition = $idSceneCondition;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //idScene
    public function getIdScene(): int {
        return $this->idScene;
    }
    public function setIdScene(int $idScene): self {
        if (is_numeric($idScene)) {
            $this->idScene = $idScene;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //idDevice
    public function getIdDevice(): ?string {
        return $this->idDeviceType;
    }
    public function setIdDevice(string $idDevice): self {
        if (is_string($idDevice) && !empty($idDevice) && strlen($idDevice == 8)) {
            $this->idDevice = $idDevice;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
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
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //conditionSign
    public function getConditionSign(): ?int {
        return $this->conditionSign;
    }
    public function setConditionSign(int $conditionSign): self {
        if (is_numeric($conditionSign)) {
            $this->conditionSign = $conditionSign;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //measurementValue
    public function getMeasurementValue(): ?float {
        return $this->measurementValue;
    }
    public function setMeasurementValue(float $measurementValue): self {
        if (is_numeric($measurementValue)) {
            $this->measurementValue = $measurementValue;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idScene = :idScene,
                    idDevice = :idDevice,
                    idMeasurementType = :idMeasurementType,
                    conditionSign = :conditionSign,
                    measurementValue = :measurementValue";
        $stmt = $this->conn->prepare($query);

        $this->idScene = htmlspecialchars(strip_tags($this->idScene));
        $this->idDevice = htmlspecialchars(strip_tags($this->idDevice));
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $this->conditionSign = htmlspecialchars(strip_tags($this->conditionSign));
        $this->measurementValue = htmlspecialchars(strip_tags($this->measurementValue));

        $stmt->bindParam(':idScene', $this->idScene);
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':idMeasurementType', $this->idMeasurementType);
        $stmt->bindParam(':conditionSign', $this->conditionSign);
        $stmt->bindParam(':measurementValue', $this->measurementValue);

        if($stmt->execute()) {
            $this->idSceneCondition = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idSceneCondition = :idSceneCondition";
        $stmt = $this->conn->prepare($query);

        $this->idSceneCondition = htmlspecialchars(strip_tags($this->idSceneCondition));
            
        $stmt->bindParam(':idSceneCondition', $this->idSceneCondition);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idScene = $row['idScene'];
            $this->idDevice = $row['idDevice'];
            $this->idMeasurementType = $row['idMeasurementType'];
            $this->conditionSign = $row['conditionSign'];
            $this->measurementValue = $row['measurementValue'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readByIdScene() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idScene = ?";
        $stmt = $this->conn->prepare($query);
        $this->idScene=htmlspecialchars(strip_tags($this->idScene));
        $stmt->bindParam(1, $this->idScene);
        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function update() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idScene = :idScene,
                    idDevice = :idDevice,
                    idMeasurementType = :idMeasurementType,
                    conditionSign = :conditionSign,
                    measurementValue = :measurementValue
                WHERE idSceneCondition = :idSceneCondition";
        $stmt = $this->conn->prepare($query);

        $this->idSceneCondition = htmlspecialchars(strip_tags($this->idSceneCondition));
        $this->idScene = htmlspecialchars(strip_tags($this->idScene));
        $this->idDevice = htmlspecialchars(strip_tags($this->idDevice));
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $this->conditionSign = htmlspecialchars(strip_tags($this->conditionSign));
        $this->measurementValue = htmlspecialchars(strip_tags($this->measurementValue));

        $stmt->bindParam(':idSceneCondition', $this->idSceneCondition);
        $stmt->bindParam(':idScene', $this->idScene);
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':idMeasurementType', $this->idMeasurementType);
        $stmt->bindParam(':conditionSign', $this->conditionSign);
        $stmt->bindParam(':measurementValue', $this->measurementValue);

        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->table_name}
                WHERE idSceneCondition = :idSceneCondition";
        $stmt = $this->conn->prepare($query);
        $this->idSceneCondition = htmlspecialchars(strip_tags($this->idSceneCondition));
        $stmt->bindParam(':idSceneCondition', $this->idSceneCondition);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}