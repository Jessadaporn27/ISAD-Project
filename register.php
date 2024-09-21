<?php
//register.php
include_once 'database.php';
include_once 'user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->register()) {
        echo "User registered successfully!";
        header('Location: http://localhost/webCloudStrike/homepage.php');
        exit();
    } else {
        echo "Failed to register user.";
    }
} else {
    echo "Please fill all fields.";
}
?>
