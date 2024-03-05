<?php
require "config.php";
if (!isset($_SESSION["admin_login"])) {
    header("Location: adminlogin.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    if (isset($_POST['saveAdminReply'])) {
        // Assuming $_POST['adminReply'] is an array with keys as query IDs
        foreach ($_POST['adminReply'] as $queryId => $adminReply) {
            // Update the admin_reply in the database
            $updateQuery = "UPDATE user_query SET admin_reply = '$adminReply' WHERE id = '$queryId'";

            if ($conn->query($updateQuery) === TRUE) {
                // echo "Admin reply for query ID $queryId updated successfully<br>";
            } else {
                echo "Error updating admin reply for query ID $queryId: " . $conn->error . "<br>";
            }
        }
    }
}

// Fetch data from user_query and room tables
$query = "SELECT user_query.id, user_query.user_id, user_query.query_text, user_query.admin_reply, bookings.selected_room,users.name
          FROM user_query
          LEFT JOIN bookings ON user_query.user_id = bookings.user_id
          LEFT JOIN users ON user_query.user_id = users.id";
$result = $conn->query($query);

if ($result === FALSE) {
    echo "Error in query: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">
</head>
<body>
<div class="sidebar" id="sidebar" style="display: block; width:18%; height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f0f0f0; -webkit-scrollbar-width: thin; -webkit-scrollbar-color: #888 #f0f0f0;">
        <h2>Hello admin</h2>
        <br>
        <ul><li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Manage Admin</a></li>
            <li><a href="admin_manageuser.php"><i class="fas fa-user"></i> Manage User</a></li>
            <li><a href="transaction.php"><i class="fas fa-cog"></i>Transaction details</a></li>
            <li><a href="bookings.php"><i class="fas fa-envelope"></i>Edit Booking</a></li>
            <li><a href="userfiles.php"><i class="fas fa-cog"></i> User Files</a></li>
            <li><a href="roomdetails.php"><i class="fas fa-cog"></i> Room Details</a></li>
            <li><a href="addrooms.php"><i class="fas fa-cog"></i> Add and Edit rooms</a></li>
            <li><a href="userreport.php"><i class="fas fa-cog"></i>User Reports</a></li>
            <li><a class="nav-link active" href="feedbackreply.php"><i class="fas fa-cog"></i>Query/Feedback</a></li>
            <li><a href="oldusers.php"><i class="fas fa-cog"></i> Old Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello admin</h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
        <label><h1>Feedback Reply:</h1></label>
    <form class="form-inline mb-3">
        <input class="form-control mr-sm-2" type="search" placeholder="Search by Name or ID" id="searchInput">
        <button class="btn btn-outline-primary my-2 my-sm-0" type="button" onclick="searchUsers()">Search</button>
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="button" onclick="showAllUsers()">Show All</button>
    </form>
    <form method="POST">
        <table border="1">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Query Text</th>
                    <th>Room Number</th>
                    <th>Admin Reply</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['user_id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['query_text']}</td>";
                    echo "<td>{$row['selected_room']}</td>";
                    echo "<td><input type='textarea' name='adminReply[{$row['id']}]' value='{$row['admin_reply']}'></td>";
                    echo "<td><button type='submit' name='saveAdminReply[{$row['id']}]' value='{$row['admin_reply']}'>Save</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>
    </div>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var mainContent = document.getElementById("mainContent");

            if (sidebar.style.display === "none" || sidebar.style.display === "") {
                sidebar.style.display = "block";
                mainContent.style.marginLeft = "250px"; // Adjust the width as needed
            } else {
                sidebar.style.display = "none";
                mainContent.style.marginLeft = "0";
            }
        }

        function searchUsers() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#mainContent tbody tr');

        rows.forEach(row => {
            const columns = row.getElementsByTagName('td');
            let rowText = '';

            for (let i = 0; i < columns.length; i++) {
                rowText += columns[i].innerText.toLowerCase();
            }

            if (rowText.includes(searchValue)) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function showAllUsers() {
        const rows = document.querySelectorAll('#mainContent tbody tr');

        rows.forEach(row => {
            row.style.display = 'table-row';
        });
    }
    </script>
</body>
</html>

