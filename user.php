<?php
// user.php
class User {
    private $conn;
    private $table_name = "users";

    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function isUsernameExist() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=?";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bind_param("s", $this->username); // ใช้ bind_param กับ mysqli สำหรับ "s" (string)

        $stmt->execute();
        $stmt->store_result(); // เก็บผลลัพธ์เพื่อดูจำนวนแถว

        if ($stmt->num_rows > 0) {
            return true; // Username exists
        }

        return false; // Username does not exist
    }

    public function register() {
        if ($this->isUsernameExist()) {
            return false; // Username already exists
        }

        $query = "INSERT INTO " . $this->table_name . " (username, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bind_param("ss", $this->username, $this->password); // ใช้ bind_param สำหรับทั้ง username และ password

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getUserId() {
        // สร้างคำสั่ง SQL เพื่อดึง user_id ของผู้ใช้ที่ล็อกอิน
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        // ผูกค่าพารามิเตอร์กับคำสั่ง SQL
        $stmt->bind_param("s", $this->username);
        
        // รันคำสั่ง SQL
        $stmt->execute();
    
        // ดึงข้อมูล id จากฐานข้อมูล
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // ตรวจสอบว่ามีผลลัพธ์หรือไม่
        if ($row) {
            return $row['id'];
        }
    
        return null; // หากไม่พบข้อมูล
    }
    
    
    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=?";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bind_param("s", $this->username); // ผูกค่า username

        if ($stmt->execute()) {
            $result = $stmt->get_result(); // ดึงผลลัพธ์ที่ได้มา
            $user = $result->fetch_assoc(); // ดึงข้อมูลของผู้ใช้

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
