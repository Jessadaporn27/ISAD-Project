<?php
// เรียกใช้คลาสที่สร้างขึ้น
include 'db_connection.php';
include 'flight_search.php';

// สร้าง instance ของคลาส Database และเชื่อมต่อฐานข้อมูล
$db = new db_connection();
$conn = $db->connect();

// สร้าง instance ของคลาส FlightSearch และดึงข้อมูล
$flightSearch = new FlightSearch($conn);
$departureCities = $flightSearch->getDepartureCities();
$arrivalCities = $flightSearch->getArrivalCities();

// ปิดการเชื่อมต่อฐานข้อมูลเมื่อเสร็จสิ้นการดึงข้อมูล
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking Homepage</title>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
    <header>
        <h1>Welcome to Flight Booking</h1>
        <nav>
            <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="history.php">History</a></li>
                <li><a href="Flight Schedules.php">Flight Schedules</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li>
                    <?php
                    session_start(); 
                    if (isset($_SESSION['username'])) {
                        echo "User ID: " . htmlspecialchars($_SESSION['username']);
                        echo ' <a href="logout.php" class="btn">Logout</a>';
                    } else {
                        echo '<a href="login.php" class="btn">Login</a>';
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="search-container">
            <div class="search-options">
            <form method="POST" action="search_flights.php">
            <div>
                <label for="from">From</label>
                <select id="from" name="from">
                    <?php
                    foreach ($departureCities as $city) {
                        echo "<option value=\"$city\">$city</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="to">To</label>
                <select id="to" name="to">
                    <?php
                    foreach ($arrivalCities as $city) {
                        echo "<option value=\"$city\">$city</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="seat_class">Choose Seat Class</label>
                <select id="seat_class" name="seat_class">
                    <option value="eco">Economy</option>
                    <option value="business">Business</option>
                </select>
            </div>

            <button type="submit">Search</button>
        </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Flight Booking. All rights reserved.</p>
    </footer>
</body>
</html>