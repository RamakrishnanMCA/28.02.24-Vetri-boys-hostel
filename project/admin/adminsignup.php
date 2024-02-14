<?php
require 'config.php';

if (isset($_POST["submit"])) {
    $name=$_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $code = $_POST["code"];

    
    if ($code === "admin123") {
        $duplicate = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
        
        if (mysqli_num_rows($duplicate) > 0) {
            echo "<script> alert('Email is already registered'); </script>";
        } else {
            $query = "INSERT INTO admins (name,email, password) VALUES ('$name','$email', '$password')";
            mysqli_query($conn, $query);
            echo "<script> alert('Admin registration successful'); </script>";

        }
    } else {
        echo "<script> alert('Incorrect code. You need a valid code to register as an admin.'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../Styles/login.css" type="text/css">
<body>
<div class="container">
    <h1>Admin Registration</h1>

    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="code">Admin Code:</label>
        <input type="password" id="code" name="code" required>

        <button type="submit" name="submit">Register</button>
    </form>

    <br>

    <center><a href="adminlogin.php">Login</a></center>
</div>
</body>
</html>
