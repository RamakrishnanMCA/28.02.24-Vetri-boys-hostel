<?php
require 'config.php'; 

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0 && $password==$row["password"]) {
        
        session_start();
        $_SESSION["admin_login"] = true;
        $_SESSION["admin_id"] = $row["id"];
        header("Location: admin_dashboard.php"); 
    } else {
        echo "<script> alert('Invalid credentials'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../Styles/login.css" type="text/css">
</head>
<body>
<div class="container">
    <h1>Admin Login</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="adminsignup.php">Sign Up</a></p>
</div>
</body>
</html>
