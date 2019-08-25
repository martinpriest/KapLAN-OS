<?php

class Family {
    private $conn;
    private $table_name = "Families";

    private $idFamily;
    private $familyName;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
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
    //familyName
    public function getFamilyName(): string {
        return $this->familyName;
    }
    public function setFamilyname(string $familyName): self {
        if(empty($familyName)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź nazwę rodziny.")));
        } else if(strlen($familyName) > 32) {
            http_response_code(400);
            exit(json_encode(array("message" => "Nazwa rodzina musi mieć mniej niż 32 znaki")));
        } else {
            $this->familyName = $familyName;
            return $this;
        }
    }

    // METODY DOSTEPU DO BAZY DANYCH
    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET familyName = :familyName";
        $stmt = $this->conn->prepare($query);
        $this->familyName=htmlspecialchars(strip_tags($this->familyName));
        $stmt->bindParam(':familyName', $this->familyName);
        if($stmt->execute()) {
            $this->idFamily = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        //zapytanie
        $query = "SELECT * FROM {$this->table_name}
                WHERE idFamily = :idFamily";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idFamily = htmlspecialchars(strip_tags($this->idFamily));
        $stmt->bindParam(':idFamily', $this->idFamily);
        //wykonanie zapytania
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->familyName = $row['familyName'];
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function update() {
        //zapytanie
        $query = "UPDATE {$this->table_name}
                SET familyName = :familyName
                WHERE idFamily = :idFamily";
        //przygotowanie zapytania
        $stmt = $this->conn->prepare($query);
        //sanityzacja
        $this->idFamily = htmlspecialchars(strip_tags($this->idFamily));
        $this->familyName = htmlspecialchars(strip_tags($this->familyName));
        $stmt->bindParam(':idFamily', $this->idFamily);
        $stmt->bindParam(':familyName', $this->familyName);
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
                WHERE idFamily = :idFamily";
        $stmt = $this->conn->prepare($query);
        $this->idFamily = htmlspecialchars(strip_tags($this->idFamily));
        $stmt->bindParam(':idFamily', $this->idFamily);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}