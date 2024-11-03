<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $departure_city = $_POST['departure_city'];
    $arrival_city = $_POST['arrival_city'];
    $departure_time = $_POST['departure_time'];
    $departure_date = $_POST['departure_date'];
    $airline_id = $_POST['airline_id'];
    $seat_class = $_POST['seat_class'];
    $payment_method = $_POST['payment_method'];

    // รับ user_id จาก session
    $user_id = $_SESSION['user_id'];

    // เชื่อมต่อฐานข้อมูล
    $db = new db_connection();
    $conn = $db->connect();

    // ดึงราคาที่นั่งตาม airline_id และ seat_class
    $sql = "SELECT eco_price, business_price FROM airlines WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $airline_id);
        $stmt->execute();
        $stmt->bind_result($eco_price, $business_price);
        $stmt->fetch();
        $stmt->close();
        
        // ตั้งราคาให้ตรงกับที่นั่งที่เลือก
        $price = 0;
        if (strtolower($seat_class) == 'eco') {
            $price = $eco_price;
        } elseif (strtolower($seat_class) == 'business') {
            $price = $business_price;
        }

        // หากเลือก PromptPay
        if ($payment_method == "PromptPay") {
            echo "<h2>ชำระเงินด้วย PromptPay</h2>";
            echo "<p>ราคาทั้งหมด: ฿" . number_format($price, 2) . "</p>";
            echo "<img src='path_to_promptpay_qr_code.png' alt='PromptPay QR Code' style='width:500px;height:500px;'><br>";

            // ปุ่มยืนยันการชำระเงิน
            echo "<form action='confirm_payment.php' method='POST'>";
            echo "<input type='hidden' name='first_name' value='" . htmlspecialchars($first_name) . "'>";
            echo "<input type='hidden' name='last_name' value='" . htmlspecialchars($last_name) . "'>";
            echo "<input type='hidden' name='phone_number' value='" . htmlspecialchars($phone_number) . "'>";
            echo "<input type='hidden' name='departure_city' value='" . htmlspecialchars($departure_city) . "'>";
            echo "<input type='hidden' name='arrival_city' value='" . htmlspecialchars($arrival_city) . "'>";
            echo "<input type='hidden' name='departure_time' value='" . htmlspecialchars($departure_time) . "'>";
            echo "<input type='hidden' name='departure_date' value='" . htmlspecialchars($departure_date) . "'>";
            echo "<input type='hidden' name='airline_id' value='" . htmlspecialchars($airline_id) . "'>";
            echo "<input type='hidden' name='seat_class' value='" . htmlspecialchars($seat_class) . "'>";
            echo "<input type='hidden' name='price' value='" . htmlspecialchars($price) . "'>"; // ส่งราคาผ่านฟอร์ม
            echo "<input type='hidden' name='payment_method' value='" . htmlspecialchars($payment_method) . "'>";
            echo "<button type='submit' name='confirm_payment'>ชำระเงินแล้ว</button>";
            echo "</form>";

        } elseif ($payment_method == "Credit Card") {
            echo "<h2>ชำระเงินด้วยบัตรเครดิต</h2>";
            echo "<p>ราคาทั้งหมด: ฿" . number_format($price, 2) . "</p>";
            echo "<form action='confirm_payment.php' method='POST'>";
            echo "<label for='card_number'>หมายเลขบัตรเครดิต:</label><br>";
            echo "<input type='text' id='card_number' name='card_number' required><br>";
            echo "<label for='expiry_date'>วันหมดอายุ (MM/YY):</label><br>";
            echo "<input type='text' id='expiry_date' name='expiry_date' required><br>";
            echo "<label for='cvv'>CVV:</label><br>";
            echo "<input type='text' id='cvv' name='cvv' required><br>";

            // ส่งข้อมูลการจองที่กรอกไว้ก่อนหน้า
            echo "<input type='hidden' name='first_name' value='" . htmlspecialchars($first_name) . "'>";
            echo "<input type='hidden' name='last_name' value='" . htmlspecialchars($last_name) . "'>";
            echo "<input type='hidden' name='phone_number' value='" . htmlspecialchars($phone_number) . "'>";
            echo "<input type='hidden' name='departure_city' value='" . htmlspecialchars($departure_city) . "'>";
            echo "<input type='hidden' name='arrival_city' value='" . htmlspecialchars($arrival_city) . "'>";
            echo "<input type='hidden' name='departure_time' value='" . htmlspecialchars($departure_time) . "'>";
            echo "<input type='hidden' name='departure_date' value='" . htmlspecialchars($departure_date) . "'>";
            echo "<input type='hidden' name='airline_id' value='" . htmlspecialchars($airline_id) . "'>";
            echo "<input type='hidden' name='seat_class' value='" . htmlspecialchars($seat_class) . "'>";
            echo "<input type='hidden' name='price' value='" . htmlspecialchars($price) . "'>"; // ส่งราคาผ่านฟอร์ม
            echo "<input type='hidden' name='payment_method' value='" . htmlspecialchars($payment_method) . "'>";
            echo "<button type='submit' name='confirm_payment'>ยืนยันการชำระเงิน</button>";
            echo "</form>";
        } else {
            echo "Invalid payment method.";
        }

    } else {
        echo "Error: Could not prepare statement.";
    }
    
    $conn->close();
}
?>
<link rel="stylesheet" href="payment_process.css">
