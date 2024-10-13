<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Admin Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4; 
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #333; 
            margin-bottom: 20px;
        }

        table {
            width: 70%; 
            margin: 20px auto; 
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.2);
        }

        th, td {
            padding: 12px;
            text-align: left;
            color: white;
        }

        th {
            background: rgba(0, 0, 0, 0.5);
            font-weight: bold;
        }

        td {
            background: rgba(0, 0, 0, 0.2);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            background-color: rgba(255, 255, 255, 0.3); 
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 5px; 
        }

        .btn:hover {
            background-color: rgba(255, 255, 255, 0.5); 
        }

        .action-btn {
            padding: 8px 15px;
            background-color: rgba(255, 255, 255, 0.2); 
            border: none;
            border-radius: 4px;
            color: white; 
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; 
        }

        .action-btn:hover {
            background-color: rgba(255, 255, 255, 0.3); 
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            color: black;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #viewDetails {
            text-align: left;
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
                    <li><a href="customers.php" class="link">Customers</a></li>
                    <li><a href="admin.html" class="link">Admin</a></li>
                </ul>
            </div>
        </nav>

        <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ceylonLibrary";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, first_name, last_name, email FROM admin";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>
                            <button class='action-btn' onclick='openViewModal(" . $row['id'] . ")'>View</button>
                            <button class='action-btn' onclick='openUpdateModal(" . $row['id'] . ")'>Update</button>
                            <button class='action-btn' onclick='deleteAdmin(" . $row['id'] . ")'>Delete</button>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>

        <a href="admin-register.html" class="btn add-new">Add New Admin</a>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeViewModal()">&times;</span>
            <h2>View Admin</h2>
            <div id="viewDetails"></div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2>Update Admin</h2>
            <form id="updateForm" onsubmit="return updateAdmin();">
                <input type="hidden" id="updateId">
                <label for="updateFirstName">First Name:</label>
                <input type="text" id="updateFirstName" required>
                <label for="updateLastName">Last Name:</label>
                <input type="text" id="updateLastName" required>
                <label for="updateEmail">Email:</label>
                <input type="email" id="updateEmail" required>
                <button type="submit" class="btn">Update</button>
            </form>
        </div>
    </div>

    <script>
        function openViewModal(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_admin.php?id=' + id, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const admin = JSON.parse(this.responseText);
                    document.getElementById('viewDetails').innerHTML = `
                        <p><strong>ID:</strong> ${admin.id}</p>
                        <p><strong>First Name:</strong> ${admin.first_name}</p>
                        <p><strong>Last Name:</strong> ${admin.last_name}</p>
                        <p><strong>Email:</strong> ${admin.email}</p>
                    `;
                    document.getElementById('viewModal').style.display = 'block';
                }
            };
            xhr.send();
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        function openUpdateModal(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_admin.php?id=' + id, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const admin = JSON.parse(this.responseText);
                    document.getElementById('updateId').value = admin.id;
                    document.getElementById('updateFirstName').value = admin.first_name;
                    document.getElementById('updateLastName').value = admin.last_name;
                    document.getElementById('updateEmail').value = admin.email;
                    document.getElementById('updateModal').style.display = 'block';
                }
            };
            xhr.send();
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        function updateAdmin() {
            const id = document.getElementById('updateId').value;
            const firstName = document.getElementById('updateFirstName').value;
            const lastName = document.getElementById('updateLastName').value;
            const email = document.getElementById('updateEmail').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_admin.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    alert('Admin updated successfully');
                    closeUpdateModal();
                    location.reload(); 
                }
            };
            xhr.send(`id=${id}&first_name=${firstName}&last_name=${lastName}&email=${email}`);
            return false; 
        }

        function deleteAdmin(id) {
            if (confirm('Are you sure you want to delete this admin?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_admin.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        alert('Admin deleted successfully');
                        location.reload(); 
                    }
                };
                xhr.send('id=' + id);
            }
        }
    </script>
</body>
</html>
