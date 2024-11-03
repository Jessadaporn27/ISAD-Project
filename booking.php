<?php
session_start();
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล

class Flight {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getFlightById($flightId) {
        $sql = "SELECT * FROM airlines WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $flightId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

class Booking {
    private $flight;

    public function __construct($flight) {
        $this->flight = $flight;
    }

    public function displayBookingForm($flightId) {
        $flight = $this->flight->getFlightById($flightId);
        $flight_id = $_POST['flight_id'];
        $seat_class = $_POST['seat_class'];
        if ($flight) {
            echo "<h1>Booking for " . htmlspecialchars($flight['airline_name']) . "</h1>";
            echo "<p>Departure City: " . htmlspecialchars($flight['departure_city']) . "</p>";
            echo "<p>Arrival City: " . htmlspecialchars($flight['arrival_city']) . "</p>";
            echo "<p>Departure Time: " . htmlspecialchars($flight['departure_time']) . "</p>";
            echo "<p>Departure Date: " . htmlspecialchars($flight['departure_date']) . "</p>";
    
            // ฟอร์มสำหรับกรอกข้อมูลผู้โดยสาร
            echo "<form method='POST' action='confirm_booking.php'>";
            // ในฟอร์มที่คุณแสดงให้ผู้ใช้
            echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($flight['id']) . "'>";
            echo "<input type='hidden' name='airline_id' value='" . htmlspecialchars($flight['id']) . "'>"; // เพิ่ม hidden field
            echo "<input type='hidden' name='departure_city' value='" . htmlspecialchars($flight['departure_city']) . "'>";
            echo "<input type='hidden' name='arrival_city' value='" . htmlspecialchars($flight['arrival_city']) . "'>";
            echo "<input type='hidden' name='departure_time' value='" . htmlspecialchars($flight['departure_time']) . "'>";
            echo "<input type='hidden' name='departure_date' value='" . htmlspecialchars($flight['departure_date']) . "'>";
            echo "<input type='hidden' name='seat_class' value='" . htmlspecialchars($seat_class) . "'>"; // ส่งข้อมูลประเภทที่นั่งไปยัง confirm_booking.php
            echo "<label for='first_name'>First Name:</label>";
            echo "<input type='text' id='first_name' name='first_name' required><br>";
            echo "<label for='last_name'>Last Name:</label>";
            echo "<input type='text' id ='last_name' name='last_name' required><br>";
            echo "<label for='phone_number'>Phone Number:</label>";
            echo "<input type='text' id='phone_number' name='phone_number' required><br>";
            
            echo "<input type='hidden' name='flight_id' value='" . htmlspecialchars($flight_id) . "'>";
            echo "<button type='submit'>Confirm Booking</button>";
            echo "</form>";
        } else {
            echo "Flight not found.";
        }
    }
    
}

// สร้าง instance ของคลาส Database และเชื่อมต่อฐานข้อมูล
$dbConnection = new db_connection();
$conn = $dbConnection->connect();

// สร้าง instance ของคลาส Flight
$flight = new Flight($conn);

// ตรวจสอบว่ามีการส่ง flight_id มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flight_id'])) {
    $flight_id = $_POST['flight_id'];
    $seat_class = $_POST['seat_class'];
    // สร้าง instance ของคลาส Booking และแสดงฟอร์มการจอง
    $booking = new Booking($flight);
    $booking->displayBookingForm($flight_id);
} else {
    echo "Invalid request.";
}

$conn->close();
?>
<link rel="stylesheet" href="booking.css">
