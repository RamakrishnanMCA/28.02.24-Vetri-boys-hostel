<?php
require 'config.php';

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $result = mysqli_query($conn, "SELECT * FROM bookings WHERE user_id=$userId");
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $userId = $_POST['user_id'];
    $startDate = $_POST['start_date'];
    $foodStatus = $_POST['food_status'];
    $guardianName = $_POST['guardian_name'];
    $relation = $_POST['relation'];
    $guardianContact = $_POST['guardian_contact'];
    $emergencyContact = $_POST['emergency_contact'];
    $selectedRoom = $_POST['selected_room'];
     // Check if the selected room is changed
     if ($selectedRoom != $user['selected_room']) {
        // Perform room-related updates only if the selected room is changed
        $prevRoom = $user['selected_room'];

        // Update the previous room (increase available seats, decrease occupied seats)
        mysqli_query($conn, "UPDATE room SET 
            available_seats = available_seats + 1,
            occupied_seats = occupied_seats - 1
            WHERE room_number = '$prevRoom'");

        // Update the new room (increase occupied seats, decrease available seats)
        mysqli_query($conn, "UPDATE room SET 
            available_seats = available_seats - 1,
            occupied_seats = occupied_seats + 1
            WHERE room_number = '$selectedRoom'");
    }
    
    mysqli_query($conn, "UPDATE bookings SET 
    start_date = '$startDate', 
    food_status = '$foodStatus', 
    guardian_name = '$guardianName', 
    relation = '$relation', 
    guardian_contact = '$guardianContact', 
    emergency_contact = '$emergencyContact', 
    selected_room = '$selectedRoom' 
    WHERE user_id = '$userId'");
    

    header('Location: bookings.php');
}
?>

<link rel="stylesheet" href="../Styles/login.css">
<div class="container">

<form action="" method="post">
    <h2>Edit Booking Details</h2>
    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
    <!-- Allow the user to update the start date -->
    <label for="start_date">New Start Date:</label>
    <input type="date" id="start_date" name="start_date" required value="<?php echo $user['start_date']; ?>">

    <!-- Other fields for updating -->
    <label for="food_status">Food Status:</label>
    <select id="food_status" name="food_status" required>
        <option value="1" <?php echo ($user['food_status'] == 1) ? 'selected' : ''; ?>>Required</option>
        <option value="0" <?php echo ($user['food_status'] == 0) ? 'selected' : ''; ?>>Not Required</option>
    </select>

    <label for="guardian_name">Guardian Name:</label>
    <input type="text" id="guardian_name" name="guardian_name" required value="<?php echo $user['guardian_name']; ?>">

    <label for="relation">Relation:</label>
    <input type="text" id="relation" name="relation" required value="<?php echo $user['relation']; ?>">

    <label for="guardian_contact">Guardian Contact:</label>
    <input type="text" id="guardian_contact" name="guardian_contact" required value="<?php echo $user['guardian_contact']; ?>">

    <label for="emergency_contact">Emergency Contact:</label>
    <input type="text" id="emergency_contact" name="emergency_contact" required value="<?php echo $user['emergency_contact']; ?>">

    <label for="selected_room">Selected Room:</label>
    <input type="text" id="selected_room" name="selected_room" required value="<?php echo $user['selected_room']; ?>">

    <!-- Submit button to update thethe booking details -->
    <button type="submit" name="submit">Update Booking</button>
</form>

</div>
