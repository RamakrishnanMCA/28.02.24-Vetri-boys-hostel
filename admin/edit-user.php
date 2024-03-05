<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $father_name = $_POST['father_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    mysqli_query($conn, "UPDATE users SET name='$name', father_name='$father_name', dob='$dob', address='$address', phone_number='$phone_number', email='$email', password='$password' WHERE id=$id");

    header('Location: admin_manageuser.php');
}
?>
<link rel="stylesheet" href="../Styles/login.css">
<div class="container">
<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>
    <label>Father Name:</label>
    <input type="text" name="father_name" value="<?php echo $user['father_name']; ?>"><br>
    <label>Date of Birth:</label>
    <input type="date" name="dob" value="<?php echo $user['dob']; ?>"><br>
    <label>Address:</label>
    <input type="text" name="address" value="<?php echo $user['address']; ?>"><br>
    <label>Phone Number:</label>
    <input type="text" name="phone_number" value="<?php echo $user['phone_number']; ?>"><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>"><br>
    <label>Password:</label>
    <input type="password" name="password" value="<?php echo $user['password']; ?>"><br>
    <button type="submit" name="submit">Update</button>
</form>
</div>