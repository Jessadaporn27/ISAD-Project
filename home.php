<?php
// เริ่ม session
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airline Ticket Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>! Book Your Flight Below</h2>

        <!-- ฟอร์มการจองตั๋วเครื่องบิน -->
        <form action="book.php" method="POST" id="booking-form">
            <div class="input-container">
                <label for="departure">Departure:</label>
                <input type="text" id="departure" name="departure" placeholder="Enter departure city" required>
            </div>

            <div class="input-container">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" placeholder="Enter destination city" required>
            </div>

            <div class="input-container">
                <label for="departure-date">Departure Date:</label>
                <input type="date" id="departure-date" name="departure_date" required>
            </div>

            <div class="input-container">
                <label for="return-date">Return Date (optional):</label>
                <input type="date" id="return-date" name="return_date">
            </div>

            <div class="input-container">
                <label for="passengers">Number of Passengers:</label>
                <input type="number" id="passengers" name="passengers" min="1" value="1" required>
            </div>

            <div class="input-container">
                <label for="class">Class:</label>
                <select id="class" name="class" required>
                    <option value="economy">Economy</option>
                    <option value="business">Business</option>
                    <option value="first">First Class</option>
                </select>
            </div>

            <button type="submit" class="btn">Book Now</button>
        </form>
    </div>
</body>
</html>
