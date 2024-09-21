<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// รับข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $departure = htmlspecialchars($_POST['departure']);
    $destination = htmlspecialchars($_POST['destination']);
    $departure_date = htmlspecialchars($_POST['departure_date']);
    $return_date = isset($_POST['return_date']) ? htmlspecialchars($_POST['return_date']) : 'N/A';
    $passengers = htmlspecialchars($_POST['passengers']);
    $class = htmlspecialchars($_POST['class']);

    // แสดงรายละเอียดการจอง (ในกรณีนี้เก็บเป็นตัวอย่าง)
    echo "<h2>Booking Confirmation</h2>";
    echo "<p>Thank you, {$_SESSION['username']}! Your flight has been booked:</p>";
    echo "<ul>";
    echo "<li><strong>Departure:</strong> $departure</li>";
    echo "<li><strong>Destination:</strong> $destination</li>";
    echo "<li><strong>Departure Date:</strong> $departure_date</li>";
    echo "<li><strong>Return Date:</strong> $return_date</li>";
    echo "<li><strong>Number of Passengers:</strong> $passengers</li>";
    echo "<li><strong>Class:</strong> $class</li>";
    echo "</ul>";
}
?>
