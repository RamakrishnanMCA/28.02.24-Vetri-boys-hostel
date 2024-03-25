<?php
require 'config.php';


if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}


$user_id = $_SESSION['id'];
$sql = "SELECT * FROM room";
$result = mysqli_query($conn, $sql);
$userNameQuery = "SELECT name FROM users WHERE id = '$user_id'";
$userNameResult = mysqli_query($conn, $userNameQuery);

if ($userNameResult) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}else {
    // Handle the error if necessary
    $userName = 'Guest'; // Default value if the name is not found
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $start_date = $_POST['startdate'];
    $duration = $_POST['duration'];
    $food_status = $_POST['foodstatus'];
    $guardian_name = $_POST['gname'];
    $relation = $_POST['grelation'];
    $guardian_contact = $_POST['gcontact'];
    $emergency_contact = $_POST['econtact'];
    $selected_room = $_POST['room'];

    
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $check_user_sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($check_user_sql);

    if ($result->num_rows > 0) {
        
        $check_transaction_query = "SELECT * FROM payment WHERE user_id = '$user_id'";
        $transaction_result = $conn->query($check_transaction_query);

        if ($transaction_result->num_rows > 0) {

            $base_fees = ($food_status == 1) ? 6000 : 3000;
            $total_fees = $base_fees * $duration;
            $check_booking_query = "SELECT * FROM bookings WHERE user_id = '$user_id'";
            $booking_result = $conn->query($check_booking_query);
            if ($booking_result->num_rows == 0) {
                
                $sql = "INSERT INTO bookings (user_id, start_date, duration, food_status, guardian_name, relation, guardian_contact, emergency_contact, total_fees, selected_room)
                        VALUES ('$user_id', '$start_date', '$duration', '$food_status', '$guardian_name', '$relation', '$guardian_contact', '$emergency_contact', '$total_fees', '$selected_room')";

                if ($conn->query($sql) === TRUE) {
                    $update_room_query = "UPDATE room SET occupied_seats = occupied_seats + 1, available_seats = available_seats - 1 WHERE room_number = '$selected_room' AND available_seats > 0";
                    if ($conn->query($update_room_query) === TRUE) {
                        echo "<script>alert('Booking added successfully');</script>";
                    } 
                    else {
                        echo "<script>alert('Error updating room: " . $conn->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
                }
            } else {
                
                echo "<script>alert('You have already made a booking. Each user can only book once.');</script>";
            }
    } 
    else {
        echo "<script>alert('User has no transaction. Please make a transaction first.');</script>";
    }
    }else {

        echo "<script>alert('Invalid User ID. Please enter a valid User ID.');</script>";
    }
    $check_available_seats_query = "SELECT available_seats FROM room WHERE room_number = '$selected_room'";
    $available_seats_result = $conn->query($check_available_seats_query);

    if ($available_seats_result && $available_seats_row = $available_seats_result->fetch_assoc()) {
        $available_seats = $available_seats_row['available_seats'];

        if ($available_seats == 0) {
            echo "<script>alert('No available seats in $selected_room');</script>";
        }
    } else {
        echo "<script>alert('Error checking available seats: " . $conn->error . "');</script>";
    }
    
}
$conn->close();
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
    <div class="sidebar" id="sidebar" style="display: block; width:18%; height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f0f0f0; -webkit-scrollbar-width: thin; -webkit-scrollbar-color: #888 #f0f0f0;">
        <h2>Welcome <span style="color:blue;"><?php echo $userName; ?> </span></h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="payment.php"><i class="fas fa-cog"></i> Payment</a></li>
            <li><a class="nav-link active" href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="foodorder.php"><i class="fas fa-cog"></i> Food Details</a></li>
            <li><a href="monthlypayment.php"><i class="fas fa-cog"></i>Monthly Payment</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello  <?php echo $userName; ?></h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
    <form action="bookroom.php" method="POST">
    <h3 class="card-title mt-5"></h3>
<div class="row">
<div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <label class="card-title">User ID</label>
                                        <div class="form-group">
                                            <input type="number" name="userid" id="userid" required class="form-control" value="<?php echo $user_id; ?>" readonly>
                                        </div>
                                </div>
                            </div>
                        </div>
    <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
        <div class="card">
            <div class="card-body">
                <label class="card-title">Name</label>
                    <div class="form-group">
                        <input type="text" name="name" id="name" required class="form-control" value="<?php echo $userName; ?>" readonly>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <label class="card-title">Start Date</label>
                                        <div class="form-group">
                                            <input type="date" name="startdate" id="startdate" required class="form-control" placeholder="date">
                                        </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <label class="card-title">Expected Duration</label>
                                        <div class="form-group">
                                        <select class="custom-select mr-sm-2" id="duration" name="duration" onchange="updateTotalFees()">
                                            <option selected>Choose...</option>
                                            <option value="1">One Month</option>
                                            <option value="2">Two Month</option>
                                            <option value="3">Three Month</option>
                                            <option value="4">Four Month</option>
                                            <option value="5">Five Month</option>
                                            <option value="6">Six Month</option>
                                            <option value="7">Seven Month</option>
                                            <option value="8">Eight Month</option>
                                            <option value="9">Nine Month</option>
                                            <option value="10">Ten Month</option>
                                            <option value="11">Eleven Month</option>
                                            <option value="12">Twelve Month</option>
                                        </select>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <label class="card-title">Food Status:</label>
                                        <div class="form-group">
                                        <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" value="1" name="foodstatus"
                                        class="custom-control-input" onclick="updateTotalFees(3000)">
                                    <label class="custom-control-label" for="customRadio1">Required <code><br>Extra 3000 Per Month</code></label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio2" value="0" name="foodstatus"
                                        class="custom-control-input" checked onclick="updateTotalFees(3000)">
                                    <label class="custom-control-label" for="customRadio2">Not Required</label>
                                        </div>
                                </div>
                            </div>
                        </div>
</div>

</div>
    <h3 class="card-title mt-5">Guardian's Information</h3>
    <div class="row">

    <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
        <div class="card">
            <div class="card-body">
                <label class="card-title">Guardian Name</label>
                    <div class="form-group">
                        <input type="text" name="gname" id="gname" class="form-control" placeholder="Enter Guardian's Name" required>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <label class="card-title">Relation</label>
                                        <div class="form-group">
                                            <input type="text" name="grelation" id="grelation" required class="form-control" placeholder="Student's Relation with Guardian">
                                        </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <label class="card-title">Contact Number</label>
                                        <div class="form-group">
                                            <input type="text" name="gcontact" id="gcontact" required class="form-control" placeholder="Enter Guardian's Contact No.">
                                        </div>
                                </div>
                            </div>
                        </div>
    </div>
    
    <h3 class="card-title mt-5">Important details:</h3>
    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                        <div class="card">
                            <div class="card-body">
                                <label class="card-title">Parent's Number: </label>
                                    <div class="form-group">
                                        <input type="text" name="econtact" id="econtact" class="form-control" placeholder="Enter Parent's Contact No." required>
                                    </div>
                            </div>
                        </div>
                    </div>
    <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
                        <div class="card">
                            <div class="card-body">
                                <label class="card-title">Total Fees Per Month</label>
                                    <div class="form-group">
                                    <p id="totalFees">3000</p>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4" style="margin-bottom: 20px;">
        <div class="card">
            <div class="card-body">
                <label class="card-title">Total Fees for Selected Duration:</label>
                <p class="card-text" id="totalFeesWithDuration">3000</p>
            </div>
        </div>
    </div>
             </div>
<!-- <h3 class="card-title mt-5">Choose Room:</h3>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room1" name="room" value="room1">
            <label for="room1">Room1</label>
            <img src="../images/Room1.png" alt="Room1" style="width:100%">
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room2" name="room" value="room2"> 
            <label for="room2">Room2</label>
            <img src="../images/Room2.png" alt="Room2" style="width:100%; height:200px">
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room3" name="room" value="room3" >
            <label for="room3">Room3</label>
            <img src="../images/Room3.png" alt="Room3" style="width:100%; height:200px">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room4" name="room" value="room4">
            <label for="room4">Room4</label>
            <img src="../images/Room4.png" alt="Room4" style="width:100%; height:200px">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room5" name="room" value="room5">
            <label for="room5">Room5</label>
            <img src="../images/Room5.png" alt="Room5" style="width:100%">
        </div>
    </div>
</div> -->
<h3 class="card-title mt-5">Choose Room:</h3>

    <div class="row">
        <?php
        // Loop through each row in the result set and generate radio buttons
        while ($row = mysqli_fetch_assoc($result)) {
            $roomNumber = $row['room_number'];
            $roomImagePath = $row['image_path'];
        ?>
            <div class="col-md-4" style="margin-bottom: 20px;">
                <div class="card">
                    <input type="radio" id="room<?= $roomNumber ?>" name="room" value="<?= $roomNumber ?>">
                    <label for="room<?= $roomNumber ?>" style="font-weight: bold; text-align: center;"><?= $roomNumber ?></label>
                    <img src="../admin/<?= $roomImagePath ?>" alt="Room<?= $roomNumber ?>" style="width: 100%">
                </div>
            </div>
        <?php
        }
        ?>
    </div>
<center><button type="submit" name="submit" style="color:white; background-color: blue;">Book Room</button></center>
</form>

    
    <script>
         function updateTotalFees() {
                const totalFeesElement = document.getElementById('totalFees');
                const totalFeesWithDurationElement = document.getElementById('totalFeesWithDuration');
                const foodStatus = document.querySelector('input[name="foodstatus"]:checked').value;
                const duration = document.getElementById('duration').value;

                const baseFees = (foodStatus == 1) ? 6000 : 3000;
                const totalFees = baseFees;
                const totalFeesWithDuration = totalFees * duration;

                totalFeesElement.textContent = totalFees;
                totalFeesWithDurationElement.textContent = totalFeesWithDuration;
            }

            // Call the updateTotalFees function initially to set default values
            window.onload = function () {
                updateTotalFees();
            }
        

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