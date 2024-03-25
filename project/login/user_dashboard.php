<?php
require 'config.php'; 

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}


$Id = $_SESSION["id"];
$userNameQuery = "SELECT name FROM users WHERE id = '$Id'";
$userNameResult = mysqli_query($conn, $userNameQuery);

if ($userNameResult) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}else {
    // Handle the error if necessary
    $userName = 'Guest'; // Default value if the name is not found
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$Id'");
$admin = mysqli_fetch_assoc($result);
$sql="Select * from users WHERE id = '$Id'";
$result=$conn->query($sql);
$query = "SELECT * FROM room";
$roomresult = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">
</head>
<body>
<div class="sidebar" id="sidebar" style="display: block; width:18%; height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f0f0f0; -webkit-scrollbar-width: thin; -webkit-scrollbar-color: #888 #f0f0f0;">
<h2>Welcome <span style="color:blue;"><?php echo $userName; ?> </span></h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a class="nav-link active" href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="payment.php"><i class="fas fa-cog"></i> Payment</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="foodorder.php"><i class="fas fa-cog"></i> Food Details</a></li>
            <li><a href="monthlypayment.php"><i class="fas fa-cog"></i>Monthly Payment</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello <?php echo $userName; ?></h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
        <h1>User Details</h1>
        <table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Father Name</th>
        <th>Date of Birth</th>
        <th>Address</th>
        <th>Phone number</th>
        <th>Email</th>
        <th>Password</th>
        <th>Option</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            if ($count % 2 == 0) {
                echo "<tr>";
            }
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["father_name"] . "</td>";
            echo "<td>" . $row["dob"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["phone_number"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["password"] . "</td>";
            echo "<td><a href='edit-user.php?id={$row['id']}'class='btn btn-primary'>Edit</a></td>";  
            if ($count % 2 == 1) {
                echo "</tr>";
            }
            $count++;
        }
        if ($count % 2 == 1) {
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No results found.</td></tr>";
    }
    echo "<table>
        <tr>
            <th>Room Number</th>
            <th>Total Seats</th>
            <th>Occupied Seats</th>
            <th>Available Seats</th>
        </tr>";

    while ($row = $roomresult->fetch_assoc()) {
    echo "<tr>
            <td>{$row['room_number']}</td>
            <td>{$row['total_seats']}</td>
            <td>{$row['occupied_seats']}</td>
            <td>{$row['available_seats']}</td>
          </tr>";
}

echo "</table>";
    ?>
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
    </script>
</body>
</html>