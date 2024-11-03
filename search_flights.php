<?php
// เริ่ม session
session_start();

// รวมไฟล์การเชื่อมต่อฐานข้อมูลและคลาส Airline
include 'db_connection.php';
include 'Airline.php';

// สร้าง instance ของคลาส Database และเชื่อมต่อฐานข้อมูล
$db = new db_connection();
$conn = $db->connect();

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// สร้าง instance ของคลาส Airline
$airline = new Airline($conn);

// ตรวจสอบว่ามีการส่งแบบฟอร์ม POST เข้ามาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $seat_class = $_POST['seat_class'];

    // ตรวจสอบค่าที่ส่งเข้ามา
    if (empty($from) || empty($to) || empty($seat_class)) {
        echo "<p>Please fill in all fields.</p>";
    } else {
        $airlines = $airline->searchAirlines($from, $to);

        // แสดงผลการค้นหาในรูปแบบตาราง
        if ($airlines) {
            if (count($airlines) > 0) {
                echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
                echo "<tr>";
                echo "<th>Airline</th>";
                echo "<th>Departure City</th>";
                echo "<th>Arrival City</th>";
                echo "<th>Departure Time</th>";
                echo "<th>Total Seats</th>";
                echo "<th>Economy Seats</th>";
                echo "<th>Business Seats</th>";
                echo "<th>Economy Price</th>";
                echo "<th>Business Price</th>";
                echo "<th>Action</th>"; // สำหรับปุ่มจอง
                echo "</tr>";

                foreach ($airlines as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['airline_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['departure_city']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['arrival_city']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['departure_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['seats']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['eco_seats']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['business_seats']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['eco_price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['business_price']) . "</td>";

                    // ตรวจสอบจำนวนที่นั่งที่ว่างก่อนการจอง
                    if ($row['booked_seats'] < $row['seats']) { // ถ้ายังมีที่นั่งว่าง
                        echo "<td><form method='POST' action='booking.php'>";
                        echo "<input type='hidden' name='flight_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<input type='hidden' name='seat_class' value='" . htmlspecialchars($seat_class) . "'>";
                        echo "<input type='hidden' name='airline_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<input type='hidden' name='first_name' value='Your First Name'>"; // เปลี่ยนให้รับค่าจริง
                        echo "<input type='hidden' name='last_name' value='Your Last Name'>"; // เปลี่ยนให้รับค่าจริง
                        echo "<input type='hidden' name='phone_number' value='Your Phone Number'>"; // เปลี่ยนให้รับค่าจริง
                        echo "<input type='hidden' name='departure_city' value='" . htmlspecialchars($row['departure_city']) . "'>";
                        echo "<input type='hidden' name='arrival_city' value='" . htmlspecialchars($row['arrival_city']) . "'>";
                        echo "<input type='hidden' name='departure_date' value='Your Departure Date'>"; // เปลี่ยนให้รับค่าจริง
                        echo "<input type='hidden' name='departure_time' value='" . htmlspecialchars($row['departure_time']) . "'>";
                        echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['eco_price']) . "'>"; // สมมติว่าใช้ราคา Economy
                        echo "<input type='submit' value='Book Now'>";
                        echo "</form></td>";
                    } else {
                        echo "<td>Fully Booked</td>"; // แสดงข้อความเมื่อไม่มีที่นั่งว่าง
                    }
                    
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No airlines found.</p>"; // แสดงข้อความเมื่อไม่พบสายการบิน
            }
        } else {
            echo "<p>Error in searching airlines.</p>"; // แสดงข้อความเมื่อเกิดข้อผิดพลาด
        }
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$db->close();
?>
<link rel="stylesheet" href="search_flight.css">
