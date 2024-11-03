<?php
class db_connection {
    private $conn;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = "airllines"; // ชื่อฐานข้อมูล

    // เปิดการเชื่อมต่อฐานข้อมูล
    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    public function close() {
        $this->conn->close();
    }
}
?>