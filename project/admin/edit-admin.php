<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM admins WHERE id=$id");
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    mysqli_query($conn, "UPDATE admins SET name='$name', email='$email', password='$password' WHERE id=$id");

    header('Location: admin_dashboard.php');
}
?>
<link rel="stylesheet" href="../Styles/login.css">
<div class="container">
<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>"><br>
    <label>Password:</label>
    <input type="password" name="password" value="<?php echo $user['password']; ?>"><br>
    <button type="submit" name="submit">Update</button>
</form>
</div>