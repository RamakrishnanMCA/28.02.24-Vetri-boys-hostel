<?php
require "config.php";
if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $room = $_POST['room'];
    $startDate = $_POST['startDate'];
    $returnDate = $_POST['returnDate'];
    $place = $_POST['place'];
    $purpose = $_POST['purpose'];
    $parentsNumber = $_POST['parentsNumber'];

    // Prepare the message
    $message = "Your son Mr. $name ($room) is leaving the hostel at $startDate. He mentioned that he will return to the hostel at $returnDate. He is going to $place because of $purpose. Thank you. By V-Boys Hostel.";

	// Authorisation details.
	$username = "ramkismsproject@gmail.com";
	$hash = "80b447987f0a428204c8511c08c10ad06ed56119434702124685c0fe936d2607";

	// Config variables. Consult http://api.textlocal.in/docs for more info.
	$test = "0";

	// Data for text message. This is the text message data.
    $sender = "V-Boys Hostel";
    $numbers = $parentsNumber;
    $message = urlencode($message);
    $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
	
    $ch = curl_init('http://api.textlocal.in/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);

     // Check the result
     if ($result === false) {
        echo "Error sending SMS";
    } else {
        echo "SMS sent successfully!";
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
</head>
<body>
    <div class="sidebar">
        <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i> Upload Files</a></li>
            <li><a href="payment.php"><i class="fas fa-cog"></i> Payment</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div class="main-content">
    <form id="travelForm" action="process_form.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="room">Room: </label>
    <input type="text" id="room" name="room" required><br>

    <label for="startDate">From Date and Time:</label>
    <input type="datetime-local" id="startDate" name="startDate" required><br>

    <label for="returnDate">Return Date and Time:</label>
    <input type="datetime-local" id="returnDate" name="returnDate" required><br>

    <label for="place">Place:</label>
    <input type="text" id="place" name="place" required><br>

    <label for="purpose">Purpose:</label>
    <input type="text" id="purpose" name="purpose" required><br>

    <label for="parentsNumber">Parents' Number:</label>
    <input type="tel" id="parentsNumber" name="parentsNumber" required><br>

    <button type="submit">Submit</button>
</form>

    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        }
    </script>
</body>
</html>