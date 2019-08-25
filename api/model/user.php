<?php
class User
{
    //POLA KLASY
    private $conn;
    private $table_name = "Users";

    private $idUser;
    private $userLogin;
    private $userPassword;
    private $userEmail;
    private $userActive;
    private $idFamily;
    private $userType;
    private $userActivated;

    //KONSTRUKTOR
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    //GETTERS AND SETTERS
    public function getIdUser(): ?int {
        return $this->idUser;
    }
    public function setIdUser(int $idUser): self {
        if (is_numeric($idUser)) {
            $this->idUser = $idUser;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }
    //userLogin
    public function getUserLogin(): ?string {
        return $this->userLogin;
    }

    public function setUserLogin(string $userLogin): self {
        if (empty($userLogin)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź login!")));
        } else if (is_numeric($userLogin)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Login nie może być liczbą!")));
        } else if (strlen($userLogin) < 6 || strlen($userLogin) > 32) {
            http_response_code(400);
            exit(json_encode(array("message" => "Login musi mieć od 6 do 32 znaków!")));
        } else {
            $this->userLogin = $userLogin;
            return $this;
        }
    }
    //userPassword
    public function getUserPassword(): ?string {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): self {
        if(empty($userPassword)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadz haslo!")));
        } else if (strlen($userPassword) < 8 || strlen($userPassword) > 32) {
            http_response_code(400);
            exit(json_encode(array("message" => "Hasło musi składać się z 8 do 32 znaków.")));
        } else {
            $this->userPassword = $userPassword;
            return $this;
        }
    }
    //userEmail
    public function getUserEmail(): ?string {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self {
        if(empty($userEmail)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź email!")));
        } else if (strlen($userEmail) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Email jest za długi")));
        } else if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadziles niepoprawny adres email.")));
        } else {
            $this->userEmail = $userEmail;
            return $this;
        }
    }
    //userActive, setter niepotrzebny
    public function getUserActive(): ?int {
        return $this->userActive;
    }
    public function setUserActive(int $userActive): self {
        if (is_numeric($userActive)) {
            $this->userActive = $userActive;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //idFamily
    public function getIdFamily(): ?int {
        return $this->idFamily;
    }

    public function setIdFamily(int $idFamily): self {
        if (is_numeric($idFamily)) {
            $this->idFamily = $idFamily;
            return $this;
        } else {
            http_response_code(400);
            exit("Nieprawidlowe ID");
        }
    }
    //userType
    public function getUserType(): ?int {
        return $this->userType;
    }

    public function setUserType(int $userType): self {
        if (is_numeric($userType)) {
            $this->userType = $userType;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //userActivated
    public function getUserActivated(): ?int {
        return $this->userActivated;
    }

    public function setUserActivated(int $userActivated): self {
        if (is_numeric($userActivated)) {
            $this->userActivated = $userActivated;
            return $this;
        } else {
            http_response_code(400);
            exit();
        }
    }
    //ZAPYTANIA DO BAZY DANYCH
    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET
                    userLogin = :userLogin,
                    userPassword = :userPassword,
                    userEmail = :userEmail,
                    userActive = 0,
                    idFamily = :idFamily,
                    userType = :userType,
                    userActivated = 0";
        //przygotuj zapytanie
        $stmt = $this->conn->prepare($query);
        //sanityzacja zmiennych obiektu
        $this->userLogin=htmlspecialchars(strip_tags($this->userLogin));
        $this->userPassword=htmlspecialchars(strip_tags($this->userPassword));
        $this->userEmail=htmlspecialchars(strip_tags($this->userEmail));
        $this->idFamily=htmlspecialchars(strip_tags($this->idFamily));
        $this->userType=htmlspecialchars(strip_tags($this->userType));
        //wstawianie zmiennych obiketu do zapytania
        $password_hash = password_hash($this->userPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':userLogin', $this->userLogin);
        $stmt->bindParam(':userPassword', $password_hash);
        $stmt->bindParam(':userEmail', $this->userEmail);
        $stmt->bindParam(':idFamily', $this->idFamily);
        $stmt->bindParam(':userType', $this->userType);
        //jesli zapytanie sie wykona poprawnie zwroc true
        if($stmt->execute()) {
            $this->idUser = $this->conn->lastInsertId();
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function read() {
        $query = "SELECT *
                FROM {$this->table_name}
                WHERE idUser = ?";
        $stmt = $this->conn->prepare($query);
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(1, $this->idUser);
        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->userLogin = $row['userLogin'];
            $this->userPassword = $row['userPassword'];
            $this->userEmail = $row['userEmail'];
            $this->idFamily = $row['idFamily'];
            $this->userActive = $row['userActive'];
            $this->userType = $row['userType'];
            $this->userActivated = $row['userActivated'];
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
        $query = "UPDATE {$this->table_name}
                SET
                    userLogin = :userLogin,
                    userEmail = :userEmail,
                    userType = :userType,
                    userActive = :userActive,
                    userActivated = :userActivated
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare($query);

        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $this->userLogin=htmlspecialchars(strip_tags($this->userLogin));
        $this->userEmail=htmlspecialchars(strip_tags($this->userEmail));
        $this->userType=htmlspecialchars(strip_tags($this->userType));
        $this->userActive=htmlspecialchars(strip_tags($this->userActive));
        $this->userActivated=htmlspecialchars(strip_tags($this->userActivated));

        $stmt->bindParam(':idUser', $this->idUser);
        $stmt->bindParam(':userLogin', $this->userLogin);
        $stmt->bindParam(':userEmail', $this->userEmail);
        $stmt->bindParam(':userType', $this->userType);
        $stmt->bindParam(':userActive', $this->userActive);
        $stmt->bindParam(':userActivated', $this->userActivated);

        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function delete() {
        $query = "DELETE FROM {$this->$table_name}
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare($query);
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam('idUser', $this->idUser);
        if ($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
    
    public function emailExists() {
        $query = "SELECT userEmail
                FROM {$this->table_name}
                WHERE userEmail = ?
                LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->userEmail=htmlspecialchars(strip_tags($this->userEmail));
        $stmt->bindParam(1, $this->userEmail);
        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            if($num>0) return true;
            else return false;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        } 
    }

    public function loginExists() {
        $query = "SELECT idUser, userLogin
                FROM {$this->table_name}
                WHERE userLogin = ?
                LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->userLogin=htmlspecialchars(strip_tags($this->userLogin));
        $stmt->bindParam(1, $this->userLogin);
        if($stmt->execute()) {
            $num = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($num>0) {
                $this->idUser = $row['idUser'];
                return true;
            }
            else return false;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }    
    }

    public function activate() {
        $query = "UPDATE {$this->table_name}
                SET userActivated = 1
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare( $query );
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(':idUser', $this->idUser);
        if($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function login() {
        $query = "UPDATE {$this->table_name}
                SET userActive = 1
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare( $query );
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(':idUser', $this->idUser);
        if($stmt->execute()) return true;
        else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function logout() {
        $query = "UPDATE {$this->table_name}
                SET userActive = 0
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare( $query );
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $stmt->bindParam(':idUser', $this->idUser);
        if($stmt->execute()) {
            $this->userActive = 0;
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function changeUserType() {
        $query = "UPDATE {$this->table_name}
                SET userType = :userType
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare( $query );
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $this->userType=htmlspecialchars(strip_tags($this->userType));
        $stmt->bindParam(':idUser', $this->idUser);
        $stmt->bindParam(':userType', $this->userType);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function changePassword() {
        $query = "UPDATE {$this->table_name}
                SET userPassword = :userPassword
                WHERE idUser = :idUser";
        $stmt = $this->conn->prepare( $query );

        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $this->userPassword=htmlspecialchars(strip_tags($this->userPassword));

        $password_hash = password_hash($this->userPassword, PASSWORD_BCRYPT);

        $stmt->bindParam(':idUser', $this->idUser);
        $stmt->bindParam(':userPassword', $password_hash);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}