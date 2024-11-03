<?php
// เริ่มต้น session
// เริ่มต้น session
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit("You need to log in first.");
}

// ตรวจสอบว่า user_id ถูกตั้งค่าใน session หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "User ID not found in session.";
    exit();
}

$user_id = $_SESSION['user_id']; // รับ user_id จาก session

// รวมไฟล์การเชื่อมต่อฐานข้อมูล
include 'db_connection.php';

// ตรวจสอบว่าได้รับข้อมูลจากแบบฟอร์มแล้ว
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $departure_city = $_POST['departure_city'];
    $arrival_city = $_POST['arrival_city'];
    $departure_time = $_POST['departure_time'];
    $departure_date = $_POST['departure_date'];
    $airline_id = $_POST['airline_id']; // รับ airline_id
    $seat_class = $_POST['seat_class'];

    // ตรวจสอบว่าชื่อ นามสกุล และเบอร์โทรไม่ว่าง
    if (!empty($first_name) && !empty($last_name) && !empty($phone_number)) {
        echo "<h2>Choose Payment Method</h2>";
        echo "<form action='payment_process.php' method='POST'>";
        echo "<input type='hidden' name='first_name' value='" . htmlspecialchars($first_name) . "'>";
        echo "<input type='hidden' name='last_name' value='" . htmlspecialchars($last_name) . "'>";
        echo "<input type='hidden' name='phone_number' value='" . htmlspecialchars($phone_number) . "'>";
        echo "<input type='hidden' name='departure_city' value='" . htmlspecialchars($departure_city) . "'>";
        echo "<input type='hidden' name='arrival_city' value='" . htmlspecialchars($arrival_city) . "'>";
        echo "<input type='hidden' name='departure_time' value='" . htmlspecialchars($departure_time) . "'>";
        echo "<input type='hidden' name='departure_date' value='" . htmlspecialchars($departure_date) . "'>";
        echo "<input type='hidden' name='airline_id' value='" . htmlspecialchars($airline_id) . "'>";
        echo "<input type='hidden' name='seat_class' value='" . htmlspecialchars($seat_class) . "'>";

        echo "<label for='payment_method'>Select Payment Method:</label><br>";
        echo "<input type='radio' id='promptpay' name='payment_method' value='PromptPay' required>";
        echo "<label for='promptpay'>PromptPay</label><br>";
        echo "<input type='radio' id='credit_card' name='payment_method' value='Credit Card' required>";
        echo "<label for='credit_card'>Credit Card</label><br>";

        echo "<button type='submit'>Proceed to Payment</button>";
        echo "</form>";
    } else {
        echo "Please fill out all required fields.";
    }
} else {
    echo "Invalid request.";
}

?>
<link rel="stylesheet" href="confirm_booking.css">
