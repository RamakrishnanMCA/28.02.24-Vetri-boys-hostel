<?php
require 'config.php'; 
if (!isset($_SESSION["admin_login"])) {
    header("Location: adminlogin.php");
    exit();
}
$roomQuery = "SELECT room_number FROM room";
$roomResult = mysqli_query($conn, $roomQuery);

$selectedRoom = isset($_POST['room_number']) ? $_POST['room_number'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission
    $selectedRoom = $_POST['room_number'];

    // Fetch booking details for the selected room
    $sql = "SELECT b.user_id, u.name, b.start_date, b.duration, b.selected_room
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.selected_room = '$selectedRoom'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error: " . $conn->error . "<br>SQL: " . $sql);
    }
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
            <li><a href="foodorder.php"><i class="fas fa-cog"></i>Order Food</a></li>
            <li><a href="monthpayment.php"><i class="fas fa-cog"></i>Monthly Payment details</a></li>
            <li><a href="transaction.php"><i class="fas fa-cog"></i>Transaction details</a></li>
            <li><a href="bookings.php"><i class="fas fa-envelope"></i>Edit Booking</a></li>
            <li><a href="userfiles.php"><i class="fas fa-cog"></i> User Files</a></li>
            <li><a class="nav-link active" href="roomdetails.php"><i class="fas fa-cog"></i> Room Details</a></li>
            <li><a href="addrooms.php"><i class="fas fa-cog"></i> Add and Edit rooms</a></li>
            <li><a href="userreport.php"><i class="fas fa-cog"></i>User Reports</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i>Leave Form Details</a></li>
            <li><a href="feedbackreply.php"><i class="fas fa-cog"></i>Query/Feedback</a></li>
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
    <h1>Room Details</h1>
        <form method="post" id="roomDetailsForm">
            <label for="room_number">Select Room Number:</label>
            <select id="room_number" name="room_number" required onchange="document.getElementById('roomDetailsForm').submit()">
                <?php
                // Loop through each row in the result set
                while ($roomRow = mysqli_fetch_assoc($roomResult)) {
                    $roomValue = $roomRow['room_number'];
                    $isSelected = (isset($selectedRoom) && $selectedRoom === $roomValue) ? 'selected' : '';

                    echo "<option value='$roomValue' $isSelected> $roomValue</option>";
                }
                ?>
            </select><br><br>
        </form>

        <?php
        // Display booking details if available
        if (isset($result) && $result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>Room</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['user_id'] . "</td>
                        <td>" . $row['name'] . "</td>
                        <td>" . $row['start_date'] . "</td>
                        <td>" . $row['duration'] . "</td>
                        <td>" . $row['selected_room'] . "</td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "No persons in ($selectedRoom).";
        }
        ?>
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
</script>
</body>
</html>
