<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "ceylonlibrary";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sinhala";
$result = $conn->query($sql);
$books = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

echo json_encode($books);
$conn->close();
?>
