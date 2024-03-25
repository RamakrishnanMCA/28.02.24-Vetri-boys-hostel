<?php
// Include the configuration file
require 'config.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the user ID from the URL
    $userId = $_GET['id'];

    // Check if the user exists in the 'users' table
    $checkUserInUsersQuery = "SELECT * FROM users WHERE id = '$userId'";
    $checkUserInUsersResult = mysqli_query($conn, $checkUserInUsersQuery);

    if ($checkUserInUsersResult && mysqli_num_rows($checkUserInUsersResult) > 0) {
        // User found in the 'users' table
         // Fetch user details

        // Check if the user has a booking in the 'bookings' table
        $checkUserInBookingsQuery = "SELECT * FROM bookings WHERE user_id = '$userId'";
        $checkUserInBookingsResult = mysqli_query($conn, $checkUserInBookingsQuery);

        if ($checkUserInBookingsResult && mysqli_num_rows($checkUserInBookingsResult) > 0) {
            // User has a booking, proceed with archiving and other actions

            // Fetch the selected room for the user
            $getSelectedRoomQuery = "SELECT selected_room FROM bookings WHERE user_id = '$userId'";
            $selectedRoomResult = mysqli_query($conn, $getSelectedRoomQuery);

            if ($selectedRoomResult && $selectedRoomRow = mysqli_fetch_assoc($selectedRoomResult)) {
                // Get the selected room
                $selectedRoom = $selectedRoomRow['selected_room'];

                // Fetch user details
                $getUserDetailsQuery = "SELECT * FROM users WHERE id = '$userId'";
                $userDetailsResult = mysqli_query($conn, $getUserDetailsQuery);
                $userDetails = mysqli_fetch_assoc($userDetailsResult);

                // Fetch booking details
                $getBookingDetailsQuery = "SELECT * FROM bookings WHERE user_id = '$userId'";
                $bookingDetailsResult = mysqli_query($conn, $getBookingDetailsQuery);
                $bookingDetails = mysqli_fetch_assoc($bookingDetailsResult);

                // Fetch payment details
                $getPaymentDetailsQuery = "SELECT * FROM payment WHERE user_id = '$userId'";
                $paymentDetailsResult = mysqli_query($conn, $getPaymentDetailsQuery);
                $paymentDetails = mysqli_fetch_assoc($paymentDetailsResult);

                // Fetch user files details
                $getUserFilesDetailsQuery = "SELECT * FROM user_files WHERE user_id = '$userId'";
                $userFilesDetailsResult = mysqli_query($conn, $getUserFilesDetailsQuery);
                $userFilesDetails = mysqli_fetch_assoc($userFilesDetailsResult);

                // Flag to determine whether user files details are found or not
                $userFilesFound = ($userFilesDetails !== null);

                // Check if user exists in 'user_files' table
                if ($userFilesFound) {
                    // Insert details into 'archived_users' table
                    $insertArchivedUserQuery = "INSERT INTO archived_users 
                    VALUES ('$userDetails[id]','$userDetails[name]','$userDetails[father_name]','$userDetails[dob]','$userDetails[address]',
                    '$userDetails[phone_number]','$userDetails[email]','$userDetails[password]','$bookingDetails[booking_id]','$bookingDetails[start_date]','$bookingDetails[duration]',
                    '$bookingDetails[food_status]','$bookingDetails[guardian_name]','$bookingDetails[relation]','$bookingDetails[guardian_contact]',
                    '$bookingDetails[emergency_contact]','$bookingDetails[total_fees]','$bookingDetails[selected_room]',
                    '$bookingDetails[created_at]','$paymentDetails[transaction_id]','$paymentDetails[payment_date]','$userFilesDetails[photo_filename]',
                    '$userFilesDetails[id_proof_filename]')";

                    mysqli_query($conn, $insertArchivedUserQuery);

                    // Delete user from 'users' table
                    $deleteUserQuery = "DELETE FROM users WHERE id = $userId";
                    mysqli_query($conn, $deleteUserQuery);

                    // Delete user's booking from 'bookings' table
                    $deleteBookingQuery = "DELETE FROM bookings WHERE user_id = '$userId'";
                    mysqli_query($conn, $deleteBookingQuery);

                    // Delete user's payment from 'payment' table
                    $deletePaymentQuery = "DELETE FROM payment WHERE user_id = '$userId'";
                    mysqli_query($conn, $deletePaymentQuery);

                    // Delete user's files from 'user_files' table
                    $deleteFilesQuery = "DELETE FROM user_files WHERE user_id = '$userId'";
                    mysqli_query($conn, $deleteFilesQuery);

                    // Update 'room' table by decrementing occupied_seats and incrementing available_seats
                    $updateRoomQuery = "UPDATE room SET occupied_seats = occupied_seats - 1, available_seats = available_seats + 1 WHERE room_number = '$selectedRoom'";
                    mysqli_query($conn, $updateRoomQuery);

                    // Redirect to the admin_manageuser.php page
                    header('Location: admin_manageuser.php');
                    exit();
                } else {
                    // Handle the case where user files details are not found
                    echo "<script>alert('No user_files found for the user.')</script>";

                    // Insert details into 'archived_users' table with null values
                    $insertArchivedUserQuery = "INSERT INTO archived_users VALUES ('$userDetails[id]','$userDetails[name]','$userDetails[father_name]','$userDetails[dob]','$userDetails[address]',
                    '$userDetails[phone_number]','$userDetails[email]','$userDetails[password]','$bookingDetails[booking_id]','$bookingDetails[start_date]','$bookingDetails[duration]',
                    '$bookingDetails[food_status]','$bookingDetails[guardian_name]','$bookingDetails[relation]','$bookingDetails[guardian_contact]',
                    '$bookingDetails[emergency_contact]','$bookingDetails[total_fees]','$bookingDetails[selected_room]',
                    '$bookingDetails[created_at]','$paymentDetails[transaction_id]','$paymentDetails[payment_date]', NULL, NULL)";

                    mysqli_query($conn, $insertArchivedUserQuery);

                    // Delete user from 'users' table
                    $deleteUserQuery = "DELETE FROM users WHERE id = $userId";
                    mysqli_query($conn, $deleteUserQuery);

                    // Delete user's booking from 'bookings' table
                    $deleteBookingQuery = "DELETE FROM bookings WHERE user_id = '$userId'";
                    mysqli_query($conn, $deleteBookingQuery);

                    // Delete user's payment from 'payment' table
                    $deletePaymentQuery = "DELETE FROM payment WHERE user_id = '$userId'";
                    mysqli_query($conn, $deletePaymentQuery);

                    // Update 'room' table by decrementing occupied_seats and incrementing available_seats
                    $updateRoomQuery = "UPDATE room SET occupied_seats = occupied_seats - 1, available_seats = available_seats + 1 WHERE room_number = '$selectedRoom'";
                    mysqli_query($conn, $updateRoomQuery);

                    // Redirect to the admin_manageuser.php page
                    header('Location: admin_manageuser.php');
                    exit();
                }
            }
        } else {
            // Handle the case where the user doesn't have a booking
            echo "<script>alert('No booking found for the user.'); window.location.href = 'admin_manageuser.php';</script>";

            // Redirect or perform any other action as needed
            $getUserDetailsQuery = "SELECT * FROM users WHERE id = '$userId'";
            $userDetailsResult = mysqli_query($conn, $getUserDetailsQuery);
            $userDetails = mysqli_fetch_assoc($userDetailsResult);

            // Insert into archived_users with null values
            $insertArchivedUserQuery = "INSERT INTO archived_users VALUES ('$userDetails[id]','$userDetails[name]','$userDetails[father_name]','$userDetails[dob]','$userDetails[address]',
                '$userDetails[phone_number]','$userDetails[email]','$userDetails[password]',NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";

            mysqli_query($conn, $insertArchivedUserQuery);

            // Delete user from 'users' table
            $deleteUserQuery = "DELETE FROM users WHERE id = $userId";
            mysqli_query($conn, $deleteUserQuery);
        }
    } else {
        // Handle the case where the user is not found in the 'users' table
        echo "<script>alert('No user found with the specified ID.')</script>";

        // Redirect or perform any other action as needed
        // ...
    }
} else {
    // Handle the case where the 'id' parameter is not set
    echo "Error: User ID not provided.";
}
?>
