<?php
// user.php
class employee {
    private $conn;
    private $table_name = "employee";

    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
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

            if ($user && ($this->password)) {
                return true;
            } else {
                echo "Invalid username or password."; // แสดงข้อความเมื่อข้อมูลไม่ถูกต้อง
            }
        }

        return false;
    }
}
?>


