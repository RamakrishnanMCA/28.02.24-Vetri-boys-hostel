<?php
require 'config.php';

if(isset($_GET['userid']) && isset($_GET['room'])) {
    $room = $_GET['room'];
    $userid = $_GET['userid'];
    
    $checkPaymentQuery = "SELECT * FROM payment WHERE user_id = '$userid'";
    $paymentResult = mysqli_query($conn, $checkPaymentQuery);
    // Update seats in the database
    if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
    $updateQuery = "UPDATE room SET occupied_seats = occupied_seats + 1, available_seats = available_seats - 1 WHERE room_number = '$room' AND available_seats > 0";
    
    if(mysqli_query($conn, $updateQuery)) {
        // Fetch the updated available seats after the update
        $fetchQuery = "SELECT available_seats FROM room WHERE room_number = '$room'";
        $result = mysqli_query($conn, $fetchQuery);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            echo $row['available_seats'];
        } else {
            echo "Error fetching available seats.";
        }
    } else {
        echo "Error updating seats.";
    }
} else {
    echo "User has no payment. Please make a payment first.";
}
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
