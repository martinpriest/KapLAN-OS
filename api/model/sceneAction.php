<?php
class SceneAction {
    private $conn;
    private $table_name = "SceneActions";

    private $idSceneAction;
    private $idScene;
    private $idDevice;
    private $idMeasurementType;
    private $measurementValue;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    //idSceneAction
    public function getIdSceneAction(): int {
        return $this->idSceneAction;
    }
    public function setIdSceneAction(int $idSceneAction): self {
        if (is_numeric($idSceneAction)) {
            $this->idSceneAction = $idSceneAction;
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
                    measurementValue = :measurementValue";
        $stmt = $this->conn->prepare($query);

        $this->idScene = htmlspecialchars(strip_tags($this->idScene));
        $this->idDevice = htmlspecialchars(strip_tags($this->idDevice));
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $this->measurementValue = htmlspecialchars(strip_tags($this->measurementValue));

        $stmt->bindParam(':idScene', $this->idScene);
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':idMeasurementType', $this->idMeasurementType);
        $stmt->bindParam(':measurementValue', $this->measurementValue);

        if($stmt->execute()) {
            $this->idSceneAction = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idSceneAction = :idSceneAction";
        $stmt = $this->conn->prepare($query);

        $this->idSceneAction = htmlspecialchars(strip_tags($this->idSceneAction));
            
        $stmt->bindParam(':idSceneAction', $this->idSceneAction);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idScene = $row['idScene'];
            $this->idDevice = $row['idDevice'];
            $this->idMeasurementType = $row['idMeasurementType'];
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
                    measurementValue = :measurementValue
                WHERE idSceneAction = :idSceneAction";
        $stmt = $this->conn->prepare($query);

        $this->idSceneAction = htmlspecialchars(strip_tags($this->idSceneAction));
        $this->idScene = htmlspecialchars(strip_tags($this->idScene));
        $this->idDevice = htmlspecialchars(strip_tags($this->idDevice));
        $this->idMeasurementType = htmlspecialchars(strip_tags($this->idMeasurementType));
        $this->measurementValue = htmlspecialchars(strip_tags($this->measurementValue));

        $stmt->bindParam(':idSceneAction', $this->idSceneAction);
        $stmt->bindParam(':idScene', $this->idScene);
        $stmt->bindParam(':idDevice', $this->idDevice);
        $stmt->bindParam(':idMeasurementType', $this->idMeasurementType);
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
                WHERE idSceneAction = :idSceneAction";
        $stmt = $this->conn->prepare($query);
        $this->idSceneAction = htmlspecialchars(strip_tags($this->idSceneAction));
        $stmt->bindParam(':idSceneAction', $this->idSceneAction);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}