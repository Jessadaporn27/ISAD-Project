<?php
//user.php
class User {
    private $conn;
    private $table_name = "users";

    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function isUsernameExist() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=:username";
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username", $this->username);
    
        $stmt->execute();
    
        if($stmt->rowCount() > 0) {
            return true; // Username exists
        }
    
        return false; // Username does not exist
    }
    
    public function register() {
        if ($this->isUsernameExist()) {
            return false; // Username already exists
        }
    
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password";
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
    
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
    
        if($stmt->execute()) {
            return true;
        }
    
        return false;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=:username";
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username", $this->username);
    
        if ($stmt->execute()) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($this->password, $user['password'])) {
                return true;
            } else {
                echo "Invalid username or password."; // แสดงข้อความเมื่อข้อมูลไม่ถูกต้อง
            }
        }
    
        return false;
    }
    
    
}
?>
