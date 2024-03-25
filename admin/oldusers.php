<?php
require 'config.php'; 

if (!isset($_SESSION["admin_login"])) {
    header("Location: adminlogin.php");
    exit();
}

$adminId = $_SESSION["admin_id"];
$result = mysqli_query($conn, "SELECT * FROM admins WHERE id='$adminId'");
$admin = mysqli_fetch_assoc($result);
$sql="Select * from archived_users";
$result=$conn->query($sql);

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
            <li><a href="foodorder.php"><i class="fas fa-cog"></i>Order Food</a></li>
            <li><a href="monthpayment.php"><i class="fas fa-cog"></i>Monthly Payment details</a></li>
            <li><a href="transaction.php"><i class="fas fa-cog"></i>Transaction details</a></li>
            <li><a href="bookings.php"><i class="fas fa-envelope"></i>Edit Booking</a></li>
            <li><a href="userfiles.php"><i class="fas fa-cog"></i> User Files</a></li>
            <li><a href="roomdetails.php"><i class="fas fa-cog"></i> Room Details</a></li>
            <li><a href="addrooms.php"><i class="fas fa-cog"></i> Add and Edit rooms</a></li>
            <li><a href="userreport.php"><i class="fas fa-cog"></i>User Reports</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i>Leave Form Details</a></li>
            <li><a href="feedbackreply.php"><i class="fas fa-cog"></i>Query/Feedback</a></li>
            <li><a class="nav-link active" href="oldusers.php"><i class="fas fa-cog"></i> Old Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello admin</h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
    <form class="form-inline mb-3">
        <input class="form-control mr-sm-2" type="search" placeholder="Search by Name or ID" id="searchInput">
        <button class="btn btn-outline-primary my-2 my-sm-0" type="button" onclick="searchUsers()">Search</button>
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="button" onclick="showAllUsers()">Show All</button>
    </form>
    <h2>Archived Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Father's Name</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Password</th>
                    <th>Booking ID</th>
                    <th>Start Date</th>
                    <th>Duration</th>
                    <th>Food Status</th>
                    <th>Guardian Name</th>
                    <th>Relation</th>
                    <th>Guardian Contact</th>
                    <th>Emergency Contact</th>
                    <th>Total Fees</th>
                    <th>Selected Room</th>
                    <th>Created At</th>
                    <th>Transaction ID</th>
                    <th>Payment Date</th>
                    <th>Photo Filename</th>
                    <th>ID Proof Filename</th>
                    <!-- Add more columns based on your archived_users table structure -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['father_name'] . "</td>";
                        echo "<td>" . $row['dob'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>" . $row['phone_number'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>" . $row['booking_id'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['duration'] . "</td>";
                        echo "<td>" . $row['food_status'] . "</td>";
                        echo "<td>" . $row['guardian_name'] . "</td>";
                        echo "<td>" . $row['relation'] . "</td>";
                        echo "<td>" . $row['guardian_contact'] . "</td>";
                        echo "<td>" . $row['emergency_contact'] . "</td>";
                        echo "<td>" . $row['total_fees'] . "</td>";
                        echo "<td>" . $row['selected_room'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>" . $row['transaction_id'] . "</td>";
                        echo "<td>" . $row['payment_date'] . "</td>";
                        echo "<td><img src='../login/uploads/" . $row["photo_filename"] . "' alt='User Photo' width='100'></td>";
            echo "<td><a href='../login/uploads/" . $row["id_proof_filename"] . "' target='_blank'>View Proof</a></td>";
                        // Add more cells based on your archived_users table structure
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='24'>No archived users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
            var searchValue = document.getElementById('searchInput').value.toLowerCase();
            var rows = document.querySelectorAll('#mainContent tbody tr');

            rows.forEach(function(row) {
                var name = row.children[1].innerText.toLowerCase();
                var id = row.children[0].innerText.toLowerCase();

                if (name.includes(searchValue) || id.includes(searchValue)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function showAllUsers() {
            var rows = document.querySelectorAll('#mainContent tbody tr');

            rows.forEach(function(row) {
                row.style.display = 'table-row';
            });
        }
</script>
</body>
</html>