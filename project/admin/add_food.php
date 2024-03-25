<?php
// add_food.php

require "config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Handle image upload
    $targetDirectory = "uploads/"; // Change this to the directory where you want to store uploaded images

    // Generate a unique name for the uploaded file
    $uploadedFileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetFilePath = $targetDirectory . $uploadedFileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if the uploaded file is an image
    $uploadOk = getimagesize($_FILES["image"]["tmp_name"]) !== false;

    // Check if file already exists (though unlikely due to unique names)
    if (file_exists($targetFilePath)) {
        echo "Error: File already exists.";
        $uploadOk = 0;
    }

    // Check file size (you can adjust the limit)
    if ($_FILES["image"]["size"] > 500000) {
        echo "Error: File is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can adjust the formats)
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($fileType, $allowedFormats)) {
        echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Error: Your file was not uploaded.";
    } else {
        // If everything is ok, try to upload the file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Insert the new food into the foods table
            $insertQuery = "INSERT INTO foods (name, price, photo_name) VALUES ('$name', '$price', '$uploadedFileName')";
            $result = $conn->query($insertQuery);

            if ($result === FALSE) {
                echo "Error adding food: " . $conn->error;
            } else {
                // Redirect back to the main page after adding the food
                header("Location: foodorder.php");
                exit();
            }
        } else {
            echo "Error: There was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/login.css">
</head>
<body>
    <div class="container" id="mainContent">
        <h1>Add Food:</h1>

        <!-- Form to add a new food with image upload -->
        <form method="post" action="" enctype="multipart/form-data">
            <label for="name">Food Name:</label>
            <input type="text" name="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" step="0.01" required>

            <label for="image">Select Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit" class="btn btn-primary" name="submit">Add Food</button>
        </form>
    </div>
</body>
</html>
