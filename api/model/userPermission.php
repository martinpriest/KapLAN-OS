<?php

class UserPermission {
    private $conn;
    private $table_name = "UserPermissions";

    private $idUser;
    private $deviceGroupPermission;
    private $devicePermission;
    private $scenePermission;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS

    //idUser
    public function getIdUser(): int {
        return $this->idUser;
    }
    public function setIdUser(int $idUser): self {
        if (is_numeric($idUser)) {
            $this->idUser = $idUser;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //deviceGroupPermission
    public function getDeviceGroupPermission(): int {
        return $this->deviceGroupPermission;
    }
    public function setDeviceGroupPermission(int $deviceGroupPermission): self {
        if (is_numeric($deviceGroupPermission)) {
            $this->deviceGroupPermission = $deviceGroupPermission;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //devicePermission
    public function getDevicePermission(): int {
        return $this->devicePermission;
    }
    public function setDevicePermission(int $devicePermission): self {
        if (is_numeric($devicePermission)) {
            $this->devicePermission = $devicePermission;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //scenePermission
    public function getScenePermission(): int {
        return $this->scenePermission;
    }
    public function setScenePermission(int $scenePermission): self {
        if (is_numeric($scenePermission)) {
            $this->scenePermission = $scenePermission;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }

    //METODY DOSTEPU DO ABZY DANYCH
    public function create()
    {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idUser = :idUser,
                    deviceGroupPermission = 1,
                    devicePermission = 1,
                    scenePermission = 1";
        $stmt = $this->conn->prepare($query);
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(':idUser', $this->idUser);
        if($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    function read()
    {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare($query);
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(':idUser', $this->idUser);
        if($stmt->execute())
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->deviceGroupPermission = $row['deviceGroupPermission'];
            $this->devicePermission = $row['devicePermission'];
            $this->scenePermission = $row['scenePermission'];
            return true;
        }
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    function update()
    {
        $query = "UPDATE {$this->table_name}
                SET
                    deviceGroupPermission = :deviceGroupPermission,
                    devicePermission = :devicePermission,
                    scenePermission = :scenePermission
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare($query);
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $this->deviceGroupPermission=htmlspecialchars(strip_tags($this->deviceGroupPermission));
        $this->devicePermission=htmlspecialchars(strip_tags($this->devicePermission));
        $this->scenePermission=htmlspecialchars(strip_tags($this->scenePermission));
        $stmt->bindParam(':idUser', $this->idUser);
        $stmt->bindParam(':deviceGroupPermission', $this->deviceGroupPermission);
        $stmt->bindParam(':devicePermission', $this->devicePermission);
        $stmt->bindParam(':scenePermission', $this->scenePermission);
        if($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    function delete()
    {
        $query = "DELETE FROM {$this->table_name}
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare($query);
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(':idUser', $this->idUser);
        if($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}