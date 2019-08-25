<?php
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return inet_pton($ip);
}

class UserLoginHistory
{
    private $table_name = "userLoginHistories";
    private $conn;

    private $idUserLoginHistory;
    private $idUser;
    private $userLoginDate;
    private $userLoginIpAddress;

    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

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

    public function create()
    {
        $query = "INSERT INTO {$this->table_name}
                SET
                    idUser = :idUser,
                    userLoginIpAddress = :userLoginIpAddress";
        $stmt = $this->conn->prepare($query);
        $this->userLoginIpAddress = getUserIpAddr();
        $stmt->bindParam(':idUser', $this->idUser);
        $stmt->bindParam(':userLoginIpAddress', $this->userLoginIpAddress);
        if($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}