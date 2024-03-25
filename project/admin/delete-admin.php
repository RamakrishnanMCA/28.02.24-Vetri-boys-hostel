<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM admins WHERE id=$id");
}

header('Location: admin_dashboard.php');