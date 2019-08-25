<?php
class SceneGroup {
    private $conn;
    private $table_name = "SceneGroups";

    private $idSceneGroup;
    private $idFamily;
    private $sceneGroupName;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    //idSceneGroup
    public function getIdSceneGroup(): int {
        return $this->idSceneGroup;
    }
    public function setIdSceneGroup(int $idSceneGroup): self {
        if (is_numeric($idSceneGroup)) {
            $this->idSceneGroup = $idSceneGroup;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //idFamily
    public function getIdFamily(): int {
        return $this->idFamily;
    }
    public function setIdFamily(int $idFamily): self {
        if (is_numeric($idFamily)) {
            $this->idFamily = $idFamily;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //sceneGroupName
    public function getSceneGroupName(): string {
        return $this->sceneGroupName;
    }
    public function setSceneGroupName(string $sceneGroupName): self {
        if(empty($sceneGroupName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź nazwę grupy scen")));
        } else if(strlen($sceneGroupName) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa grupy scen jest za dluga")));
        } else {
            $this->sceneGroupName = $sceneGroupName;
            return $this;
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idFamily = :idFamily,
                    sceneGroupName = :sceneGroupName";
        $stmt = $this->conn->prepare($query);

        $this->idFamily = htmlspecialchars(strip_tags($this->idFamily));
        $this->sceneGroupName = htmlspecialchars(strip_tags($this->sceneGroupName));
            
        $stmt->bindParam(':idFamily', $this->idFamily);
        $stmt->bindParam(':sceneGroupName', $this->sceneGroupName);

        if($stmt->execute()) {
            $this->idSceneGroup = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idSceneGroup = :idSceneGroup";
        $stmt = $this->conn->prepare($query);

        $this->idSceneGroup = htmlspecialchars(strip_tags($this->idSceneGroup));
            
        $stmt->bindParam(':idSceneGroup', $this->idSceneGroup);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idFamily = $row['idFamily'];
            $this->sceneGroupName = $row['sceneGroupName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readByIdFamily() {
        $query = "SELECT *
                FROM {$this->table_name}
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

    public function update() {
        //zapytanie
        $query = "UPDATE {$this->table_name}
                SET sceneGroupName = :sceneGroupName
                WHERE idSceneGroup = :idSceneGroup";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idSceneGroup = htmlspecialchars(strip_tags($this->idSceneGroup));
        $this->sceneGroupName = htmlspecialchars(strip_tags($this->sceneGroupName));
        $stmt->bindParam(':idSceneGroup', $this->idSceneGroup);
        $stmt->bindParam(':sceneGroupName', $this->sceneGroupName);
        //wykonanie zapytania
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->table_name}
                WHERE idSceneGroup = :idSceneGroup";
        $stmt = $this->conn->prepare($query);
        $this->idSceneGroup = htmlspecialchars(strip_tags($this->idSceneGroup));
        $stmt->bindParam(':idSceneGroup', $this->idSceneGroup);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}