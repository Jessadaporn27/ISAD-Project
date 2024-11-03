<?php
session_start(); // เริ่มต้น session ที่จุดเริ่มต้นของไฟล์

include_once 'db_connection.php';
include_once 'employee.php';

$db_connection = new db_connection();
$db = $db_connection->connect();

$employee = new employee($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee->username = $_POST['username'];
    $employee->password = $_POST['password'];

    // พยายามล็อกอิน
    if ($employee->login()) {
        $_SESSION['username'] = $employee->username;
        $_SESSION['user_id'] = $employee->getUserId();
        header('Location: Airline_staff.php');
        exit();
    } else {
        // แสดงข้อความความผิดพลาดในฟอร์มล็อกอิน
        echo "<p style='color: red;'>Login failed. Please check your username and password.</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2 id="form-title">Login</h2>
            <form id="auth-form" method="POST">
                <div class="input-container">
                    <input type="text" id="username" name="username" placeholder="Type your username" required>
                    <span class="icon">&#128100;</span>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="Type your password" required>
                    <span class="icon">&#128274;</span>
                </div>
                <button type="submit" class="btn" id="submit-btn">Login</button>
            </form>
            
        </div>
    </div>

</body>
</html>
