<?php
// Assuming you have a database connection in config.php
require "config.php";
date_default_timezone_set('Asia/Kolkata');
// Fetch data from the leave_form table
$leaveFormQuery = "SELECT * FROM leave_form";
$leaveFormResult = $conn->query($leaveFormQuery);
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="../Styles/user_dashboard.css">
    </head>
    <body>
        <div class="sidebar" id="sidebar" style="display: block; width:18%; height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #888 #f0f0f0; -webkit-scrollbar-width: thin; -webkit-scrollbar-color: #888 #f0f0f0;">
            <h2>Hello admin</h2>
            <br>
            <ul><li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
                <li><a  href="admin_dashboard.php"><i class="fas fa-home"></i> Manage Admin</a></li>
                <li><a href="admin_manageuser.php"><i class="fas fa-user"></i> Manage User</a></li>
                <li><a href="foodorder.php"><i class="fas fa-cog"></i>Order Food</a></li>
                <li><a href="monthpayment.php"><i class="fas fa-cog"></i>Monthly Payment details</a></li>
                <li><a href="transaction.php"><i class="fas fa-cog"></i>Transaction details</a></li>
                <li><a href="bookings.php"><i class="fas fa-envelope"></i>Edit Booking</a></li>
                <li><a href="userfiles.php"><i class="fas fa-cog"></i> User Files</a></li>
                <li><a href="roomdetails.php"><i class="fas fa-cog"></i> Room Details</a></li>
                <li><a href="addrooms.php"><i class="fas fa-cog"></i> Add and Edit rooms</a></li>
                <li><a href="userreport.php"><i class="fas fa-cog"></i>User Reports</a></li>
                <li><a class="nav-link active" href="leaveform.php"><i class="fas fa-cog"></i>Leave Form Details</a></li>
                <li><a href="feedbackreply.php"><i class="fas fa-cog"></i>Query/Feedback</a></li>
                <li><a href="oldusers.php"><i class="fas fa-cog"></i> Old Users</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
            <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
        </div>
        <div id="helloAdminSection">
        <h5 style="display: inline-block; margin-right: 10px;">Hello admin</h5>
        <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
        </div>
        <div class="main-content" id="mainContent">
        <h1>Leave Form Details:</h1>
        <form method="post">
            <label for="filterDate">Filter by Date:</label>
            <input type="date" id="filterDate" name="filterDate">

            
            <label for="filterTime">Filter by Time:</label>
            <input type="time" id="filterTime" name="filterTime" value="<?php echo date('H:i'); ?>">
            <!-- Set the default value to the current time -->

            <button type="submit" class="btn btn-outline-primary my-2 my-sm-0">Filter</button>
            <button type="button" class="btn btn-outline-primary my-2 my-sm-0" onclick="showAll()">Show All</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Room</th>
                    <th>Start Date</th>
                    <th>Return Date</th>
                    <th>Place</th>
                    <th>Purpose</th>
                    <th>Parents' Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are rows in the result set
                if ($leaveFormResult->num_rows > 0) {
                    // Loop through each row and display data in the table
                    while ($row = $leaveFormResult->fetch_assoc()) {
                        // Check if the row should be displayed based on the filter date and time
                        if (isset($_POST['filterDate']) && !empty($_POST['filterDate']) && isset($_POST['filterTime']) && !empty($_POST['filterTime'])) {
                            $filterDate = $_POST['filterDate'];
                            $filterTime = $_POST['filterTime'];
                            $startDateTime = new DateTime($row['start_date']);
                            $endDateTime = new DateTime($row['return_date']);
                            $filterDateTime = new DateTime($filterDate . ' ' . $filterTime);

                            if ($startDateTime <= $filterDateTime && $endDateTime >= $filterDateTime) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["user_id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["room"] . "</td>";
                                echo "<td>" . $row["start_date"] . "</td>";
                                echo "<td>" . $row["return_date"] . "</td>";
                                echo "<td>" . $row["place"] . "</td>";
                                echo "<td>" . $row["purpose"] . "</td>";
                                echo "<td>" . $row["parents_number"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Display all entries if no filter is applied
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["room"] . "</td>";
                            echo "<td>" . $row["start_date"] . "</td>";
                            echo "<td>" . $row["return_date"] . "</td>";
                            echo "<td>" . $row["place"] . "</td>";
                            echo "<td>" . $row["purpose"] . "</td>";
                            echo "<td>" . $row["parents_number"] . "</td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='10'>No leave form entries found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>

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
        function showAll() {
                document.getElementById("filterDate").value = ""; // Clear the filter date
                document.forms[0].submit(); // Submit the form to show all entries
            }
        </script>
    </body>
    </html>