<?php
class Airline {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    public function searchAirlines($from, $to) {
        $sql = "SELECT a.id, a.airline_name, a.departure_city, a.arrival_city, 
                a.departure_time, a.seats, a.eco_seats, a.business_seats, 
                a.eco_price, a.business_price, 
                (SELECT COUNT(*) FROM bookings WHERE airline_id = a.id) AS booked_seats
                FROM airlines a 
                WHERE a.departure_city = ? AND a.arrival_city = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $from, $to);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllFlights() {
        $query = "SELECT * FROM airlines"; // สมมติว่าตารางชื่อ airlines
        $result = $this->conn->query($query);
        return $result;
    }

    public function addFlight($airline_name, $departure_city, $arrival_city, $departure_time, $departure_date, $seats, $eco_seats, $business_seats, $eco_price, $business_price) {
        $query = "INSERT INTO airlines (airline_name, departure_city, arrival_city, departure_time, departure_date, seats, eco_seats, business_seats, eco_price, business_price) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssiiid", $airline_name, $departure_city, $arrival_city, $departure_time, $departure_date, $seats, $eco_seats, $business_seats, $eco_price, $business_price);
        return $stmt->execute();
    }

    public function deleteAirline($id) {
        // ลบข้อมูลการจองที่เกี่ยวข้องกับ airline_id ก่อน
        $sql = "DELETE FROM bookings WHERE airline_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {  // เปลี่ยนจาก $this->db เป็น $this->conn
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "Bookings related to airline ID $id deleted successfully.<br>";
            } else {
                echo "Error deleting bookings: " . $stmt->error . "<br>";
            }
            $stmt->close();
        }

        // ลบข้อมูลสายการบิน
        $sql = "DELETE FROM airlines WHERE id = ?";
        if ($stmt = $this->conn->prepare($sql)) {  // เปลี่ยนจาก $this->db เป็น $this->conn
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "Airline ID $id deleted successfully.<br>";
            } else {
                echo "Error deleting airline: " . $stmt->error . "<br>";
            }
            $stmt->close();
        }
        
        return "Delete operation attempted.";
    }
}

?>