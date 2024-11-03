<?php
session_start();
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล
include 'airline.php'; // รวมคลาส Airline ที่สร้างขึ้น
if (!isset($_SESSION['username'])) {
    header('Location: login_employee.php');
    exit();}

if (isset($_POST['logout'])) {
    // ลบ session ทั้งหมดและเปลี่ยนเส้นทางไปยังหน้า login
    session_unset();
    session_destroy();
    header('Location: login_employee.php');
    exit();
}
class FlightManager {
    private $db;
    private $airline;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->airline = new Airline($this->db); // เพิ่มการสร้างอ็อบเจกต์ Airline
    }

    public function addFlight($data) {
        return $this->airline->addFlight(
            $data['airline_name'],
            $data['departure_city'],
            $data['arrival_city'],
            $data['departure_time'],
            $data['departure_date'],   // เพิ่มวันที่ออกเดินทาง
            $data['seats'],
            $data['eco_seats'],
            $data['business_seats'],
            $data['eco_price'],        // เพิ่มราคาของที่นั่ง Economy
            $data['business_price']    // เพิ่มราคาของที่นั่ง Business
        );
    }

    public function deleteAirline($id) {
        return $this->airline->deleteAirline($id);
    }

    public function getAllFlights() {
        return $this->airline->getAllFlights();
    }
}



// สร้าง instance ของคลาส Database และเชื่อมต่อฐานข้อมูล
$dbConnection = new db_connection();
$conn = $dbConnection->connect();

// สร้าง instance ของ FlightManager
$flightManager = new FlightManager($conn);

// ตรวจสอบการเพิ่มเที่ยวบิน
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['logout'])) {
    $message = $flightManager->addFlight($_POST);
    echo $message;
}

// ตรวจสอบการลบสายการบิน
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $message = $flightManager->deleteAirline($delete_id);
    echo $message;
}

// ดึงข้อมูลเที่ยวบินทั้งหมด
$flights = $flightManager->getAllFlights();

// ปิดการเชื่อมต่อฐานข้อมูล
$dbConnection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Airlines</title>
    <link rel="stylesheet" href="manageairline.css"> 
</head>
<body>

<h2>Manage Airlines</h2>

<!-- ฟอร์ม Logout -->
<form action="Airline_staff.php" method="POST">
    <input type="submit" name="logout" value="Logout">
</form>

<!-- Form เพิ่มสายการบิน -->
<form action="Airline_staff.php" method="POST">
    <label for="airline_name">Airline Name:</label>
    <input type="text" id="airline_name" name="airline_name" required>

    <label for="departure_city">Departure City:</label>
    <input type="text" id="departure_city" name="departure_city" required>

    <label for="arrival_city">Arrival City:</label>
    <input type="text" id="arrival_city" name="arrival_city" required>

    <label for="departure_time">Departure Time:</label>
    <input type="time" id="departure_time" name="departure_time" required>
    
    <label for="departure_date">Departure Date:</label>
    <input type="date" id="departure_date" name="departure_date" required>

    <label for="seats">Total of Seats:</label>
    <input type="number" id="seats" name="seats" min="1" required><br>

    <label for="eco_seats">Economy Seats:</label>
    <input type="number" id="eco_seats" name="eco_seats" min="1" required><br>

    <label for="business_seats">Business Seats:</label>
    <input type="number" id="business_seats" name="business_seats" min="1" required><br>

    <label for="eco_price">Price per Economy Seat:</label>
    <input type="number" id="eco_price" name="eco_price" required>

    <label for="business_price">Price per Business Seat:</label>
    <input type="number" id="business_price" name="business_price" required>

    <input type="submit" value="Add Airline">
</form>

<!-- แสดงรายการสายการบิน -->
<table>
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
        <th>Action</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($flights)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['airline_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['departure_city']) . "</td>";
        echo "<td>" . htmlspecialchars($row['arrival_city']) . "</td>";
        echo "<td>" . htmlspecialchars($row['departure_time']) . "</td>";
        echo "<td>" . htmlspecialchars($row['departure_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row["seats"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["eco_seats"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["eco_price"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["business_seats"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["business_price"]) . "</td>";
        echo "<td><a class='delete-btn' href='Airline_staff.php?delete_id=" . $row['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
