<?php
require "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (code for handling query submissions remains unchanged) ...

    // Handle query deletion
    if (isset($_POST['deleteQuery'])) {
        if (isset($_POST['deleteQueryID'])) {
            $deleteQueryID = $_POST['deleteQueryID'];
            $deleteQuery = "DELETE FROM user_query WHERE id = '$deleteQueryID'";

            if ($conn->query($deleteQuery) === TRUE) {
                // Redirect to the same page to prevent form resubmission
                header("Location: feedback.php");
                exit();
            } else {
                echo "Error: " . $deleteQuery . "<br>" . $conn->error;
            }
        } else {
            echo "Error: deleteQueryID is not set.";
        }
    }
}
?>
