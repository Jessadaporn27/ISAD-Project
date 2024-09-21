<?php
session_start();
session_unset(); // ลบข้อมูลทั้งหมดใน session
session_destroy(); // ทำลาย session
header('Location: homepage.php'); // เปลี่ยนหน้าไปยังหน้า homepage
exit();
?>
