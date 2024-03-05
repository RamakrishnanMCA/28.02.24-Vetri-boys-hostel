<?php
require "config.php";
if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user_id and transaction_id from the form
    $user_id = $_POST['user_id'];
    $transaction_id = $_POST['transaction_id'];

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $checkUserQuery = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $checkUserQuery);
    if (mysqli_num_rows($result) > 0) {
    // Insert payment details into the Payment table
    $query = "INSERT INTO payment (user_id, transaction_id) VALUES ('$user_id', '$transaction_id')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Payment successfully recorded.')</script>";
    } else {
        echo "<script>alert('Payment already recorded.')</script>";
    }
    }
    else {
        echo "<script>alert('User not found.')</script>";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">
    <link rel="stylesheet" href="../Styles/innerlogin.css">

    
    </head>
<body>
<div class="sidebar" id="sidebar" style="display: block; width:18%; height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f0f0f0; -webkit-scrollbar-width: thin; -webkit-scrollbar-color: #888 #f0f0f0;">
        <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a class="nav-link active" href="payment.php"><i class="fas fa-cog"></i> Payment</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello User</h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
        <div class="container">
            <h3>Payment QR Code</h3>
            <h6> Pay ₹500 to confirm your room (Only Once)</h6>
         
            <img src="../images/payment.jpg" alt="Payment QR Code">

            <form action="payment.php" method="post">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" placeholder="refer 'My details' Page"required>

                <label for="transaction_id">Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id" placeholder="Careful" required>

                <button type="submit">Submit Payment</button>
            </form>
        </div>
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
