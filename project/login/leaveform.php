<?php
require "config.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming $_SESSION['id'] holds the user's ID
$user_id = $_SESSION['id'];

// Fetch user details from the 'users' table
$userQuery = "SELECT name FROM users WHERE id = '$user_id'";
$userResult = $conn->query($userQuery);
$userNameQuery = "SELECT name FROM users WHERE id = '$user_id'";
$userNameResult = mysqli_query($conn, $userNameQuery);
$userparentsnoQuery ="SELECT emergency_contact FROM bookings WHERE user_id = '$user_id'";
$userparentsResult =mysqli_query($conn,$userparentsnoQuery);

if ($userNameResult) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}else {
    // Handle the error if necessary
    $userName = 'Guest'; // Default value if the name is not found
}
if ($userparentsResult === false) {
    // Check for errors in the query
    echo "Error: " . $userparentsnoQuery . "<br>" . mysqli_error($conn);
} else {
    // Proceed if the query was successful
    if (mysqli_num_rows($userparentsResult) > 0) {
        // Fetch the emergency contact information
        $row = mysqli_fetch_assoc($userparentsResult);
        $emergencyContactInfo = $row["emergency_contact"];
    } else {
        $emergencyContactInfo = "Not available";
    }
}
if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc();
    $userName = $userData['name'];
} else {
    $userName = ""; // Set a default value or handle the case where the user is not found
}

// Fetch room number from the 'bookings' table
$bookingQuery = "SELECT selected_room FROM bookings WHERE user_id = '$user_id'";
$bookingResult = $conn->query($bookingQuery);

if ($bookingResult->num_rows > 0) {
    $bookingData = $bookingResult->fetch_assoc();
    $roomNumber = $bookingData['selected_room'];
} else {
    $roomNumber = ""; // Set a default value or handle the case where the booking is not found
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $room = $_POST['room'];
    $startDate = $_POST['startDate'];
    $returnDate = $_POST['returnDate'];
    $place = $_POST['place'];
    $purpose = $_POST['purpose'];
    $parentsNumber = $_POST['parentsNumber'];

    // Get the form data
    $parentsNumber = $_POST['parentsNumber'];

    // Format the phone number to ensure it starts with '+91'
    $parentsNumber = '+91' . preg_replace('/[^0-9]/', '', $parentsNumber);


    // Prepare the message
    $messageContent = "Your son Mr. $userName ($room) is leaving the hostel at $startDate. He mentioned that he will return to the hostel at $returnDate. He is going to $place because of $purpose. Thank you. By V-Boys Hostel.";

    // Insert data into the database
    $insertQuery = "INSERT INTO leave_form (user_id, name, room, start_date, return_date, place, purpose, parents_number, message) VALUES ('$user_id', '$userName', '$room', '$startDate', '$returnDate', '$place', '$purpose', '$parentsNumber', '$messageContent')";
    if ($conn->query($insertQuery) === TRUE) {
        
        echo "<script>alert('Message sent successfully and data stored!')</script>";

        // Sinch Integration
        $service_plan_id = "aed8e25ec5a64d409c8bc2ea44a";   //change this create your own sinch id
        $bearer_token = "b1af6ddbc970481d8612e9fc140";      // change this create your own sinch token
        $send_from = "+447662447"; // Any phone number assigned to your API

        // Check recipient_phone_numbers for multiple numbers and make it an array.
        if (stristr($parentsNumber, ',')) {
            $recipient_phone_numbers = explode(',', $parentsNumber);
        } else {
            $recipient_phone_numbers = [$parentsNumber];
        }

        // Set necessary fields to be JSON encoded
        $content = [
            'to' => array_values($recipient_phone_numbers),
            'from' => $send_from,
            'body' => $messageContent
        ];

        $data = json_encode($content);

        $ch = curl_init("https://us.sms.api.sinch.com/xms/v1/{$service_plan_id}/batches");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BEARER);
        curl_setopt($ch, CURLOPT_XOAUTH2_BEARER, $bearer_token);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            //echo 'Curl error: ' . curl_error($ch);
        } else {
            //echo $result;
        }

        curl_close($ch);
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">
    <link rel="stylesheet" href="../Styles/innerlogin.css">
</head>
<body>
<div class="sidebar" id="sidebar" style="display: block; width:18%; height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f0f0f0; -webkit-scrollbar-width: thin; -webkit-scrollbar-color: #888 #f0f0f0;">
        <h2>Welcome <span style="color:blue;"><?php echo $userName; ?> </span></h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="payment.php"><i class="fas fa-cog"></i> Payment</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="foodorder.php"><i class="fas fa-cog"></i> Food Details</a></li>
            <li><a href="monthlypayment.php"><i class="fas fa-cog"></i>Monthly Payment</a></li>
            <li><a class="nav-link active" href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello <?php echo $userName; ?></h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
        <h1>Leave Form:</h1>
        <div class="container">
    <form id="travelForm" action="leaveform.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $userName; ?>" readonly><br>

        <label for="room">Room:</label>
        <input type="text" id="room" name="room" value="<?php echo $roomNumber; ?>" readonly><br>

        <label for="startDate">From Date and Time:</label>
        <input type="datetime-local" id="startDate" name="startDate" required><br>

        <label for="returnDate">Return Date and Time:</label>
        <input type="datetime-local" id="returnDate" name="returnDate" required><br>

        <label for="place">Place:</label>
        <input type="text" id="place" name="place" required><br>

        <label for="purpose">Purpose:</label>
        <input type="text" id="purpose" name="purpose" required><br>

        <label for="parentsNumber">Parents' Number:</label>
        <input type="tel" id="parentsNumber" name="parentsNumber" value="<?php echo $emergencyContactInfo; ?>" readonly><br>

        <button type="submit">Submit</button>
</form>
</div>

<h1>Your Leave Form History:</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Room</th>
        <th>Start Date</th>
        <th>Return Date</th>
        <th>Place</th>
        <th>Purpose</th>
        <th>Parents' Number</th>
        <th>Applied Date</th>
        <th>No. of days</th>
        <th>Action</th>
    </tr>

    <?php
    // Fetch leave form history for the current user
    $leaveFormQuery = "SELECT * FROM leave_form WHERE user_id = '$user_id'  ORDER BY created_at DESC";
    $leaveFormResult = $conn->query($leaveFormQuery);

    if ($leaveFormResult->num_rows > 0) {
        // Output data of each row
        while ($row = $leaveFormResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["room"] . "</td>";
            echo "<td>" . $row["start_date"] . "</td>";
            echo "<td>" . $row["return_date"] . "</td>";
            echo "<td>" . $row["place"] . "</td>";
            echo "<td>" . $row["purpose"] . "</td>";
            echo "<td>" . $row["parents_number"] . "</td>";
            echo "<td>" . $row["created_at"] . "</td>";
            // Calculate the number of days
            $startDate = new DateTime($row["start_date"]);
            $returnDate = new DateTime($row["return_date"]);
            $interval = $startDate->diff($returnDate);
            $numberOfDays = $interval->days;

            // Display the number of days in a new column
            echo "<td>" . ($numberOfDays == 1 ? $numberOfDays . " day" : $numberOfDays . " days") . "</td>";

            // Add a Remove button with a form for each row
            echo "<td>";
            echo "<form method='post' action='delete_leave.php'>";
            echo "<input type='hidden' name='delete_id' value='" . $row["id"] . "'>";
            echo "<button type='submit' name='remove' onclick='return confirm(\"Are you sure you want to remove this entry?\")'>Remove</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No leave form history available</td></tr>";
    }
    ?>

</table>

    </div>
    <script>
        function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("mainContent");

        if (sidebar.style.display === "none" || sidebar.style.display === "") {
            sidebar.style.display = "block";
            mainContent.style.marginLeft = "250px"; // Adjust the width as needed
        } else {
            sidebar.style.display = "none";
            mainContent.style.marginLeft = "0";
        }
    }
    </script>
</body>
</html>