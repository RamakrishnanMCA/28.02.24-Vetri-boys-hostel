<?php
require 'config.php'; 


// Check if the admin is logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

$Id = $_SESSION["id"];
$result = mysqli_query($conn, "SELECT * FROM admins WHERE id='$Id'");
$admin = mysqli_fetch_assoc($result);
$sql="Select * from users";
$result=$conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="../Styles/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i> Upload Files</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div class="main-content">
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
            echo "<td><a href='edit-user.php?id=" . $row["id"] . "'>Edit</a></td>";
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
    ?>
</table>
    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        }
    </script>
</body>
</html>