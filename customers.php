<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Customers | CeylonLibrary</title>
    <style>
    
        .customer-data table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .customer-data th, .customer-data td {
            border: 1px solid rgba(0, 0, 0, 0.2);
            padding: 12px;
            text-align: left;
            color: #fff; 
        }

        .customer-data th {
            background-color: rgba(255, 255, 255, 0.5); 
            font-weight: bold; 
        }

        .customer-data td {
            background-color: rgba(255, 255, 255, 0.2); 
        }

        .customer-data tr:nth-child(even) td {
            background-color: rgba(255, 255, 255, 0.15); 
        }

        .customer-data tr:hover td {
            background-color: rgba(255, 255, 255, 0.4); 
        }

        .button-group {
            display: flex;
            gap: 5px;
        }

        .button-group form {
            display: inline;
        }

        .btn {
            background-color: rgba(255, 255, 255, 0.3); 
            border: none;
            color: #fff; 
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 0 2px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s, opacity 0.3s;
        }

        .btn:hover {
            background-color: rgba(255, 255, 255, 0.7); 
            opacity: 0.7; 
        }

        .form-container {
            margin: 20px 0;
        }

        .form-container input {
            display: block;
            margin: 10px 0;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        #view-form {
            color: #fff; 
            background-color: rgba(0, 0, 0, 0.7); 
            padding: 20px;
            border-radius: 8px;
        }

        #update-form {
            color: #fff;
            background-color: rgba(0, 0, 0, 0.7); 
            padding: 20px;
            border-radius: 8px;
        }

        #update-form h3 {
            color: #fff; 
        }
    </style>
</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p>CeylonLibrary</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="home.html" class="link">Home</a></li>
                <li><a href="category.html" class="link">Category</a></li>
                <li><a href="services.html" class="link">Services</a></li>
                <li><a href="about.html" class="link">About Us</a></li>
                <li><a href="customers.php" class="link active">Customers</a></li>
                <li><a href="admin.html" class="link">Admin</a></li>
            </ul>
        </div>
    </nav>

    <div class="customer-data">
        <?php
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $dbname = "ceylonLibrary";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_id'])) {
                $id = $_POST['delete_id'];
                $deleteSql = "DELETE FROM customers WHERE Id=?";
                $stmt = $conn->prepare($deleteSql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
        }

        if (isset($_POST['update_id'])) {
            $id = $_POST['update_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $updateSql = "UPDATE customers SET first_name=?, last_name=?, email=?, password=? WHERE Id=?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("ssssi", $first_name, $last_name, $email, $password, $id);
            $stmt->execute();
            $stmt->close();
        }

        $sql = "SELECT Id, first_name, last_name, email FROM customers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Actions</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Id"] . "</td>
                        <td>" . $row["first_name"] . "</td>
                        <td>" . $row["last_name"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td class='button-group'>
                            <a href='#' class='btn' onclick='viewCustomer(" . $row["Id"] . ")'>View</a>
                            <a href='#' class='btn' onclick='editCustomer(" . $row["Id"] . ")'>Update</a>
                            <form action='customers.php' method='post' style='display:inline;'>
                                <input type='hidden' name='delete_id' value='" . $row["Id"] . "'>
                                <input type='submit' class='btn' value='Delete'>
                            </form>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found</p>";
        }
        $conn->close();
        ?>

        <div class="form-container" id="form-container">
            <!-- View Form -->
            <div id="view-form" style="display:none;">
                <h3>View Customer</h3>
                <p id="view-info"></p>
            </div>

            <!-- Update Form -->
            <div id="update-form" style="display:none;">
                <h3>Update Customer</h3>
                <form action="customers.php" method="post">
                    <input type="hidden" name="update_id" id="update-id">
                    <input type="text" name="first_name" id="update-first-name" placeholder="First Name">
                    <input type="text" name="last_name" id="update-last-name" placeholder="Last Name">
                    <input type="email" name="email" id="update-email" placeholder="Email">
                    <input type="password" name="password" id="update-password" placeholder="Password">
                    <input type="submit" class="btn" value="Update">
                </form>
            </div>
        </div>
    </div>

    <script>
        function viewCustomer(id) {

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'view_customer.php?id=' + id, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var data = JSON.parse(this.responseText);
                    document.getElementById('view-info').innerHTML =
                        "<strong>ID:</strong> " + data.Id + "<br>" +
                        "<strong>First Name:</strong> " + data.first_name + "<br>" +
                        "<strong>Last Name:</strong> " + data.last_name + "<br>" +
                        "<strong>Email:</strong> " + data.email;
                    document.getElementById('view-form').style.display = 'block';
                    document.getElementById('update-form').style.display = 'none';
                }
            };
            xhr.send();
        }

        function editCustomer(id) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'view_customer.php?id=' + id, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var data = JSON.parse(this.responseText);
                    document.getElementById('update-id').value = data.Id;
                    document.getElementById('update-first-name').value = data.first_name;
                    document.getElementById('update-last-name').value = data.last_name;
                    document.getElementById('update-email').value = data.email;
                    document.getElementById('update-password').value = data.password;
                    document.getElementById('update-form').style.display = 'block';
                    document.getElementById('view-form').style.display = 'none';
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
