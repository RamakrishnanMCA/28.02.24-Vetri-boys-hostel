<?php
require 'config.php';


if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}


$user_id = $_SESSION['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input_user_id = $_POST['userid'];
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

    
    $check_user_sql = "SELECT * FROM users WHERE id = '$input_user_id'";
    $result = $conn->query($check_user_sql);

    if ($result->num_rows > 0) {
        
        $base_fees = ($food_status == 1) ? 6000 : 3000;
        $total_fees = $base_fees * $duration;
        $check_booking_query = "SELECT * FROM bookings WHERE user_id = '$input_user_id'";
        $booking_result = $conn->query($check_booking_query);
        if ($booking_result->num_rows == 0) {
            
            $sql = "INSERT INTO bookings (user_id, start_date, duration, food_status, guardian_name, relation, guardian_contact, emergency_contact, total_fees, selected_room)
                    VALUES ('$input_user_id', '$start_date', '$duration', '$food_status', '$guardian_name', '$relation', '$guardian_contact', '$emergency_contact', '$total_fees', '$selected_room')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Booking added successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
            }
        } else {
            
            echo "<script>alert('You have already made a booking. Each user can only book once.');</script>";
        }
    } else {

        echo "<script>alert('Invalid User ID. Please enter a valid User ID.');</script>";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Final Report</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div class="main-content">
    <form action="bookroom.php" method="POST">
    <h3 class="card-title mt-5"></h3>
<div class="row">
<div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">User ID</h6>
                                        <div class="form-group">
                                            <input type="number" name="userid" id="userid" required class="form-control" placeholder="id refer MyDetails page">
                                        </div>
                                </div>
                            </div>
                        </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Start Date</h6>
                                        <div class="form-group">
                                            <input type="date" name="startdate" id="startdate" required class="form-control" placeholder="date">
                                        </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Expected Duration</h6>
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
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Food Status:</h6>
                                        <div class="form-group">
                                        <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" value="1" name="foodstatus"
                                        class="custom-control-input" onclick="updateTotalFees(3000)">
                                    <label class="custom-control-label" for="customRadio1">Required <code>Extra 3000 Per Month</code></label>
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

    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Guardian Name</h6>
                    <div class="form-group">
                        <input type="text" name="gname" id="gname" class="form-control" placeholder="Enter Guardian's Name" required>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Relation</h6>
                                        <div class="form-group">
                                            <input type="text" name="grelation" id="grelation" required class="form-control" placeholder="Student's Relation with Guardian">
                                        </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Contact Number</h6>
                                        <div class="form-group">
                                            <input type="text" name="gcontact" id="gcontact" required class="form-control" placeholder="Enter Guardian's Contact No.">
                                        </div>
                                </div>
                            </div>
                        </div>
    </div>
    
    <h3 class="card-title mt-5">Note</h3>
    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Emergency Contact Number</h6>
                                    <div class="form-group">
                                        <input type="text" name="econtact" id="econtact" class="form-control" placeholder="Enter Emergency Contact No." required>
                                    </div>
                            </div>
                        </div>
                    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Total Fees Per Month</h6>
                                    <div class="form-group">
                                    <p id="totalFees">3000</p>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Total Fees for Selected Duration:</h6>
                <p class="card-text" id="totalFeesWithDuration">3000</p>
            </div>
        </div>
    </div>
             </div>
<h3 class="card-title mt-5">Choose Room:</h3>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room1" name="room" value="room1" onclick="updateSeats('room1')">
            <label for="room1">Room1</label>
            <img src="../images/Room1.png" alt="Room1" style="width:100%">
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room2" name="room" value="room2" onclick="updateSeats('room2')"> 
            <label for="room2">Room2</label>
            <img src="../images/Room2.png" alt="Room2" style="width:100%; height:200px">
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room3" name="room" value="room3" onclick="updateSeats('room3')">
            <label for="room3">Room3</label>
            <img src="../images/Room3.png" alt="Room3" style="width:100%; height:200px">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room4" name="room" value="room4" onclick="updateSeats('room4')">
            <label for="room4">Room4</label>
            <img src="../images/Room4.png" alt="Room4" style="width:100%; height:200px">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room5" name="room" value="room5" onclick="updateSeats('room5')">
            <label for="room5">Room5</label>
            <img src="../images/Room5.png" alt="Room5" style="width:100%">
        </div>
    </div>
</div>
<button type="submit" name="submit">Book Room</button>
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
                document.querySelector('.sidebar').classList.toggle('active');
                document.querySelector('.main-content').classList.toggle('active');
            }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        }
        function updateSeats(room) {
            // Make an AJAX request to update seats in the database
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Check if available seats are zero, show an alert
                    if (this.responseText == '0') {
                        alert("No available seats in " + room);
                    }
                }
            };

            // Send room information to the server
            xhttp.open("GET", "update_seats.php?room=" + room, true);
            xhttp.send();
        }
    </script>
</body>
</html>