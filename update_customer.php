<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "ceylonLibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $sql = "UPDATE customers SET first_name='$first_name', last_name='$last_name', email='$email' WHERE Id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully. <a href='customers.php'>Back to Customers</a>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM customers WHERE Id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <h1>Update Customer</h1>
        <form action="update_customer.php?id=<?php echo $id; ?>" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $row['first_name']; ?>" required><br>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $row['last_name']; ?>" required><br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>" required><br>
            <input type="submit" value="Update">
        </form>
        <a href="customers.php">Back to Customers</a>
        <?php
    } else {
        echo "<p>No customer found with ID $id</p>";
    }
}

$conn->close();
?>
