<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];

    // Perform the deletion from the food_order table
    $delete_query = "DELETE FROM food_order WHERE id = '$order_id'";
    
    if ($conn->query($delete_query) === TRUE) {
        echo "<script>alert('Order removed successfully!')</script>";
        // Redirect back to the foodorder.php page
        header("Location: foodorder.php");
        exit();
    } else {
        echo "Error removing order: " . $conn->error;
    }
} else {
    // Invalid request
    echo "Invalid request.";
}
?>
