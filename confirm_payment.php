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
    $price = $_POST['price'];
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id']; // รับ user_id จาก session

    // เชื่อมต่อฐานข้อมูล
    $db = new db_connection();
    $conn = $db->connect();

    // ตรวจสอบจำนวนที่นั่งที่ยังว่าง
    $seat_check_sql = "SELECT seats, booked_seats FROM airlines WHERE id = ?";
    $seat_stmt = $conn->prepare($seat_check_sql);
    $seat_stmt->bind_param("i", $airline_id);
    $seat_stmt->execute();
    $seat_result = $seat_stmt->get_result();

    if ($seat_result->num_rows > 0) {
        $seat_row = $seat_result->fetch_assoc();
        $available_seats = $seat_row['seats'] - $seat_row['booked_seats'];

        if ($available_seats > 0) {
            // อนุญาตให้จอง
            $sql = "INSERT INTO bookings (user_id, first_name, last_name, phone_number, departure_city, arrival_city, departure_time, departure_date, airline_id, seat_class, price, payment_method) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("isssssssssds", $user_id, $first_name, $last_name, $phone_number, $departure_city, $arrival_city, $departure_time, $departure_date, $airline_id, $seat_class, $price, $payment_method);
                
                if ($stmt->execute()) {
                    // อัปเดตจำนวนที่นั่งที่ถูกจองแล้ว
                    $update_seat_sql = "UPDATE airlines SET booked_seats = booked_seats + 1 WHERE id = ?";
                    $update_seat_stmt = $conn->prepare($update_seat_sql);
                    $update_seat_stmt->bind_param("i", $airline_id);
                    $update_seat_stmt->execute();
                    echo "Booking confirmed successfully!";
                    echo "<a href='homepage.php' style='display: inline-block; background-color: #009879; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Back to Homepage</a>";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            // ที่นั่งเต็มแล้ว
            echo "Sorry, this flight is fully booked. Please choose another flight.";
        }
    } else {
        echo "Error fetching seat information.";
    }

    $conn->close();
}
?>
