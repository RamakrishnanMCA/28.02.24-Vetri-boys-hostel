<?php
// Include your database connection file here
require "config.php";

// Check if room_number is set in the URL
if (isset($_GET['id'])) {
    $roomNumber = $_GET['id'];

    // Fetch room details from the database
    $selectQuery = "SELECT * FROM room WHERE room_number = '$roomNumber'";
    $result = mysqli_query($conn, $selectQuery);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Room details found
        $roomNumber = $row['room_number'];

        // Remove the room from the database
        $deleteQuery = "DELETE FROM room WHERE room_number = '$roomNumber'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "Room removed successfully.";
        } else {
            echo "Error removing room: " . mysqli_error($conn);
        }
    } else {
        // Room not found
        echo "Room not found.";
    }
} else {
    // Room number not provided in the URL
    echo "Room number not provided.";
}
?>
