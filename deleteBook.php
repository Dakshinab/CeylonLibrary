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

$sql = "DELETE FROM sinhala WHERE book_id='$book_id'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Book deleted successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $conn->error]);
}

$conn->close();
?>
