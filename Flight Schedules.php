<?php
// รวมไฟล์การเชื่อมต่อฐานข้อมูล
include 'db_connection.php';

class FlightSchedules {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getFlightSchedules() {
        // สร้างคำสั่ง SQL เพื่อดึงข้อมูลสายการบิน
        $sql = "SELECT airline_name, departure_city, arrival_city, departure_time, departure_date, seats, eco_seats, business_seats, eco_price, business_price FROM airlines";
        return $this->conn->query($sql);
    }
}

// สร้าง instance ของคลาส Database และเชื่อมต่อฐานข้อมูล
$db = new db_connection();
$conn = $db->connect();

// สร้าง instance ของคลาส Airline
$airline = new FlightSchedules($conn);
$result = $airline->getFlightSchedules();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airlines Homepage</title>
    <link rel="stylesheet" href="flight_schedules.css">

</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
    <header>
    <nav>
        <h1>Flight Schedules</h1>
        <!-- ปุ่มกลับไปที่หน้าแรก -->
        <a href="homepage.php" style="display: inline-block; background-color: #009879; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Back to Homepage</a>
    </nav>
    </header>
    </div>
<main>
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <tr>
            <th>Airline Name</th>
            <th>Departure City</th>
            <th>Arrival City</th>
            <th>Departure Time</th>
            <th>Departure Date</th>
            <th>Seats</th>
            <th>Eco Seats</th>
            <th>Business Seats</th>
            <th>Eco Price</th>
            <th>Business Price</th>
        </tr>

        <?php
        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($result->num_rows > 0) {
            // แสดงผลข้อมูลจากฐานข้อมูลเป็นแถว ๆ
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['airline_name']) . "</td>
                    <td>" . htmlspecialchars($row['departure_city']) . "</td>
                    <td>" . htmlspecialchars($row['arrival_city']) . "</td>
                    <td>" . htmlspecialchars($row['departure_time']) . "</td>
                    <td>" . htmlspecialchars($row['departure_date']) . "</td>  <!-- แสดงวันที่ -->
                    <td>" . htmlspecialchars($row['seats']) . "</td>
                    <td>" . htmlspecialchars($row['eco_seats']) . "</td>
                    <td>" . htmlspecialchars($row['eco_price']) . "</td>
                    <td>" . htmlspecialchars($row['business_seats']) . "</td>
                    <td>" . htmlspecialchars($row['business_price']) . "</td>
                </tr>";
            }
        
        } else {
            echo "<tr><td colspan='5'>No airlines available</td></tr>";
        }
        ?>
    </table>

</main>
</html>
</body>
<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
