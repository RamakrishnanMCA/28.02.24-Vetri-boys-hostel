<?php
require "config.php";

if (isset($_POST['remove'])) {
    $delete_id = $_POST['delete_id'];

    // Perform the deletion query
    $deleteQuery = "DELETE FROM leave_form WHERE id = '$delete_id'";
    if ($conn->query($deleteQuery) === TRUE) {
        // Redirect to the same page to prevent form resubmission
        header("Location: leaveform.php");
        exit();
    } else {
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }
    
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<script>alert('Entry removed successfully!')</script>";
        // You may also redirect or perform any other actions after deletion.
    } else {
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }
}
?>