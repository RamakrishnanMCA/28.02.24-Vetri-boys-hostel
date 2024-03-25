<?php
require 'config.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Delete the booking record
    $deleteQuery = "DELETE FROM bookings WHERE user_id = '$userId'";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<script>alert('Booking record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

$conn->close();

// Redirect back to the booking details page
header("Location: bookings.php");
exit();
?>
