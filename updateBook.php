<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "ceylonlibrary";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$book_id = $data['book_id'];
$book_name = $data['book_name'];
$author = $data['author'];

$sql = "UPDATE sinhala SET book_name='$book_name', author='$author' WHERE book_id='$book_id'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Book updated successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $conn->error]);
}

$conn->close();
?>
