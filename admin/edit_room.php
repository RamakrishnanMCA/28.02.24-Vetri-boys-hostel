<?php
// Include your database connection file here
require "config.php";
if (!isset($_SESSION["admin_login"])) {
    header("Location: adminlogin.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $roomNumber = $_POST["room_number"];
    $availableSeats = $_POST["available_seats"];
    $occupiedSeats = $_POST["occupied_seats"];
    $totalSeats = $_POST["total_seats"];

    // Check if a new image is uploaded
    if ($_FILES["image"]["name"]) {
        // Handle file upload
        $targetDirectory = "uploads/";
        $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDirectory . $imageName;

        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

        // Update the image path in the database
        $updateImageQuery = "UPDATE room SET image_path = '$targetFile' WHERE room_number = '$roomNumber'";
        mysqli_query($conn, $updateImageQuery);
    }

    // Update other room details in the database
    $updateQuery = "UPDATE room SET
                    available_seats = '$availableSeats',
                    occupied_seats = '$occupiedSeats',
                    total_seats = '$totalSeats'
                    WHERE room_number = '$roomNumber'";
    mysqli_query($conn, $updateQuery);

    // Redirect to roomdetails.php after updating
    header("Location: addrooms.php");
    exit();
}

// Fetch room data based on the provided room_number in the URL parameter
if (isset($_GET['id'])) {
    $roomId = $_GET['id'];
    $selectQuery = "SELECT * FROM room WHERE room_number = '$roomId'";
    $result = mysqli_query($conn, $selectQuery);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Room details found
        $roomNumber = $row['room_number'];
        $availableSeats = $row['available_seats'];
        $occupiedSeats = $row['occupied_seats'];
        $totalSeats = $row['total_seats'];
        $imagePath = $row['image_path'];
    } else {
        // Room not found, redirect to roomdetails.php
        header("Location: addrooms.php");
        exit();
    }
} else {
    // Room ID not provided, redirect to roomdetails.php
    header("Location: addrooms.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/login.css">
</head>
<body>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            Room Number: <input type="text" name="room_number" value="<?php echo $roomNumber; ?>" readonly><br>
            Available Seats: <input type="number" name="available_seats" value="<?php echo $availableSeats; ?>" required><br>
            Occupied Seats: <input type="number" name="occupied_seats" value="<?php echo $occupiedSeats; ?>" required><br>
            Total Seats: <input type="number" name="total_seats" value="<?php echo $totalSeats; ?>" required><br>
            Current Image: <img src="<?php echo $imagePath; ?>" alt="Current Image" style="width: 150px; height: 100px;"><br>
            New Image: <input type="file" name="image" accept="image/*"><br>
            <input type="submit" value="Update Room">
        </form>
    </div>
</body>
</html>
