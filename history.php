<?php
session_start();
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้ว
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit("You need to log in first.");
}

// รับ user_id จาก session
$user_id = $_SESSION['user_id'];

// สร้าง instance ของคลาส Database และเชื่อมต่อฐานข้อมูล
$dbConnection = new db_connection();
$conn = $dbConnection->connect();

// คำสั่ง SQL สำหรับดึงข้อมูลประวัติการจองของผู้ใช้
$sql = "SELECT b.id, b.booking_date, a.airline_name, a.departure_city, b.seat_class, b.price,
        a.arrival_city, a.departure_time, a.departure_date 
        FROM bookings b
        JOIN airlines a ON b.airline_id = a.id 
        WHERE b.user_id = ?";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // ผูก user_id กับคำสั่ง SQL
$stmt->execute();
$result = $stmt->get_result();

// แสดงผลข้อมูลประวัติการจอง
if ($result->num_rows > 0) {
    echo "<h2>Your Booking History</h2>";
    echo "<table>
            <tr>
                <th>Airline Name</th>
                <th>Departure City</th>
                <th>Arrival City</th>
                <th>Departure Date</th>
                <th>Departure Time</th>
                <th>Booking Date</th>
                <th>Seat Class</th>
                <th>Price</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['airline_name']) . "</td>
            <td>" . htmlspecialchars($row['departure_city']) . "</td>
            <td>" . htmlspecialchars($row['arrival_city']) . "</td>
            <td>" . htmlspecialchars($row['departure_date']) . "</td>
            <td>" . htmlspecialchars($row['departure_time']) . "</td>
            <td>" . htmlspecialchars($row['booking_date']) . "</td>
            <td>" . htmlspecialchars($row['seat_class']) . "</td>
            <td>" . htmlspecialchars($row['price']) . "</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No booking history found.</p>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
?>
<link rel="stylesheet" href="history.css">