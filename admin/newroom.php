<?php
// Include your database connection file here
require "config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $roomNumber = $_POST["room_number"];
    $availableSeats = $_POST["available_seats"];
    $occupiedSeats = $_POST["occupied_seats"];
    $totalSeats = $_POST["total_seats"];

    // Check if an image is uploaded
    if ($_FILES["image"]["name"]) {
        // Handle file upload
        $targetDirectory = "uploads/";
        $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDirectory . $imageName;

        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    } else {
        // Default image if none is provided
        $targetFile = "uploads/default_image.png";
    }

    // Insert room details into the database
    $insertQuery = "INSERT INTO room (room_number, available_seats, occupied_seats, total_seats, image_path)
                    VALUES ('$roomNumber', '$availableSeats', '$occupiedSeats', '$totalSeats', '$targetFile')";

    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<script>
                alert('Room added successfully.');
                window.location.href = 'addrooms.php';
              </script>";
        exit();
    } else {
        echo "Error adding room: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rooms</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/login.css">
</head>
<body>
    <div class="container">
        <h2>Add New Room</h2>
        <form action="" method="post" enctype="multipart/form-data">
            Room Number: <input type="text" name="room_number" required><br>
            Available Seats: <input type="number" name="available_seats" required><br>
            Occupied Seats: <input type="number" name="occupied_seats" required><br>
            Total Seats: <input type="number" name="total_seats" required><br>
            Image: <input type="file" name="image" accept="image/*"><br>
            <input type="submit" value="Add Room">
        </form>
    </div>
</body>
</html>
