<?php
class Scene {
    private $conn;
    private $table_name = "Scenes";

    private $idScene;
    private $idSceneGroup;
    private $sceneName;
    private $sceneActive;
    private $startHour;
    private $endHour;
    private $linkCondition;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
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
            exit();
        }
    }
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
    //sceneName
    public function getSceneName(): string {
        return $this->sceneName;
    }
    public function setSceneName(string $sceneName): self {
        if(empty($sceneName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź nazwę sceny")));
        } else if(strlen($sceneName) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa sceny jest za dluga")));
        } else {
            $this->sceneName = $sceneName;
            return $this;
        }
    }
    //startHour
    public function getStartHour(): string {
        return $this->startHour;
    }
    public function setStartHour(string $startHour): self {
        if(empty($startHour)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź nazwę sceny")));
        } else if(strlen($startHour) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa sceny jest za dluga")));
        } else {
            $this->startHour = $startHour;
            return $this;
        }
    }
    //endHour
    public function getEndHour(): string {
        return $this->endHour;
    }
    public function setEndHour(string $endHour): self {
        if(empty($endHour)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź nazwę sceny")));
        } else if(strlen($endHour) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa sceny jest za dluga")));
        } else {
            $this->endHour = $endHour;
            return $this;
        }
    }
    //sceneActive
    public function getSceneActive(): ?int {
        return $this->sceneActive;
    }
    public function setSceneActive(int $sceneActive): self {
        if (is_numeric($sceneActive)) {
            $this->sceneActive = $sceneActive;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //linkCondition
    public function getLinkCondition(): string {
        return $this->linkCondition;
    }
    public function setLinkCondition(string $linkCondition): self {
        if(empty($linkCondition)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź nazwę sceny")));
        } else if(strlen($linkCondition) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa sceny jest za dluga")));
        } else {
            $this->linkCondition = $linkCondition;
            return $this;
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idSceneGroup = :idSceneGroup,
                    sceneName = :sceneName,
                    sceneActive = :sceneActive,
                    linkCondition = :linkCondition";

        $stmt = $this->conn->prepare($query);

        $this->idSceneGroup = htmlspecialchars(strip_tags($this->idSceneGroup));
        $this->sceneName = htmlspecialchars(strip_tags($this->sceneName));
        $this->sceneActive = htmlspecialchars(strip_tags($this->sceneActive));
        $this->linkCondition = htmlspecialchars(strip_tags($this->linkCondition));

        $stmt->bindParam(':idSceneGroup', $this->idSceneGroup);
        $stmt->bindParam(':sceneName', $this->sceneName);
        $stmt->bindParam(':sceneActive', $this->sceneActive);
        $stmt->bindParam(':linkCondition', $this->linkCondition);

        if($stmt->execute()) {
            $this->idScene = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idScene = :idScene";

        $stmt = $this->conn->prepare($query);

        $this->idScene = htmlspecialchars(strip_tags($this->idScene));

        $stmt->bindParam(':idScene', $this->idScene);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->sceneName = $row['sceneName'];
            $this->sceneName = $row['sceneName'];
            $this->sceneName = $row['sceneName'];
            $this->sceneName = $row['sceneName'];
            $this->sceneName = $row['sceneName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readByIdSceneGroup() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idSceneGroup = ?";
        $stmt = $this->conn->prepare($query);
        $this->idSceneGroup=htmlspecialchars(strip_tags($this->idSceneGroup));
        $stmt->bindParam(1, $this->idSceneGroup);
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
                SET
                    idSceneGroup = :idSceneGroup,
                    sceneName = :sceneName,
                    sceneActive = :sceneActive,
                    linkCondition = :linkCondition
                WHERE idScene = :idScene";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idScene = htmlspecialchars(strip_tags($this->idScene));
        $this->idSceneGroup = htmlspecialchars(strip_tags($this->idSceneGroup));
        $this->sceneName = htmlspecialchars(strip_tags($this->sceneName));
        $this->sceneActive = htmlspecialchars(strip_tags($this->sceneActive));
        $this->linkCondition = htmlspecialchars(strip_tags($this->linkCondition));

        $stmt->bindParam(':idScene', $this->idScene);
        $stmt->bindParam(':idSceneGroup', $this->idSceneGroup);
        $stmt->bindParam(':sceneName', $this->sceneName);
        $stmt->bindParam(':sceneActive', $this->sceneActive);
        $stmt->bindParam(':linkCondition', $this->linkCondition);
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
                WHERE idScene = :idScene";
        $stmt = $this->conn->prepare($query);
        $this->idScene = htmlspecialchars(strip_tags($this->idScene));
        $stmt->bindParam(':idScene', $this->idScene);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}