<?php
require 'config.php';
if(isset($_POST["submit"])){
    $email=$_POST["email"];
    $password=$_POST["password"];
    $result=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    $row=mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result)>0){
        if($password == $row["password"]){
            session_start();
            $_SESSION["login"]=true;
            $_SESSION["id"]=$row["id"];
            header("Location: user_dashboard.php");
        }
        else{
            echo
            "<script> alert('Wrong Password'); </script>";    
        }
    }
    else{
        echo
        "<script> alert('User not registered'); </script>";
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/login.css" type="text/css">
</head>
<body>
    
    <div class="container">
        <h1>Hostel Booking System</h1>
        <form method="post">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="submit">Login</button>
        </form><br>
        <p>Don't have an account? <a href="registration.php">Sign Up</a></p><br>
        <center><a href="../login.html" style="background-color:white; color:blue">Home</a></center>
    </div>
</body>
</html>