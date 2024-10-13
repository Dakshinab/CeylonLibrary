<?php
$servername = "localhost";
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "ceylonlibrary";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$book_name = $data['book_name'];
$author = $data['author'];

$sql = "INSERT INTO sinhala (book_name, author) VALUES ('$book_name', '$author')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Book added successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $conn->error]);
}

$conn->close();
?>
