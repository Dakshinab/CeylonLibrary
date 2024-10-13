<?php

session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ceylonlibrary';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE (email = ? OR id = ?) AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $input_username, $input_username, $input_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $admin = $result->fetch_assoc();

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];

        header("Location: admin-details.php?status=success");
        exit();
    } else {
        header("Location: admin-login.html?status=failed");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
