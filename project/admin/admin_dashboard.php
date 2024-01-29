<?php
require 'config.php'; 


// Check if the admin is logged in
if (!isset($_SESSION["admin_login"])) {
    header("Location: adminlogin.php");
    exit();
}

$adminId = $_SESSION["admin_id"];
$result = mysqli_query($conn, "SELECT * FROM admins WHERE id='$adminId'");
$admin = mysqli_fetch_assoc($result);
$sql="Select * from admins";
$result=$conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../Styles/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>Hello admin</h2>
        <br>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Manage Admin</a></li>
            <li><a href="admin_manageuser.php"><i class="fas fa-user"></i> Manage User</a></li>
            <li><a href="#"><i class="fas fa-envelope"></i> Messages</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div class="main-content">
        <h1>Manage Admins</h1>
        <table>
            <tr>
                <th>Id: </th>
                <th>Name: </th>
                <th>Email: </th>
                <th>Password: </th>
            </tr>
            <?php
            if($result-> num_rows>0){
                while($row=$result->fetch_assoc()){
                    echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["email"]."</td><td>".$row["password"]."</td></tr>";
                }
                echo"</tableY";
            }
            else{
                echo"0 result";
            }
            $conn->close();
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