<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylonLibrary";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE (email = ? OR Id = ?) AND password = ?");
    $stmt->bind_param("sis", $login, $login, $pass);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: category.html?status=success");
    } else {
        header("Location: home.html?status=failed");
    }

    $stmt->close();
}

$conn->close();
?>
