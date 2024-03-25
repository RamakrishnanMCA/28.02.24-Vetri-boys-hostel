<?php
// Assuming you have a database connection established
require "config.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}
$input_user_id = $_SESSION['id'];
$userNameQuery = "SELECT name FROM users WHERE id = '$input_user_id'";
$userNameResult = mysqli_query($conn, $userNameQuery);

if ($userNameResult) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}else {
    // Handle the error if necessary
    $userName = 'Guest'; // Default value if the name is not found
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user credentials are provided
if (isset($_POST['user_id']) && isset($_POST['password'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // Query to authenticate user
    $auth_query = "SELECT * FROM users WHERE id = '$user_id' AND password = '$password'";
    $auth_result = $conn->query($auth_query);

    // Check if authentication is successful
    if ($auth_result === false) {
        die("Authentication query error: " . $conn->error);
    } elseif ($auth_result->num_rows > 0) {
        // User authenticated, proceed with displaying the table
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <!-- <script src="download.js"></script> -->
    <style>
    .table {
        margin: 0 auto;
        width: 38%;
        max-width: 100%; 
    }
        th, td {
            text-align: left;
            border: none;
        }

        th {
            width: 150px;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
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
            <li><a class="nav-link active"  href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="foodorder.php"><i class="fas fa-cog"></i> Food Details</a></li>
            <li><a href="monthlypayment.php"><i class="fas fa-cog"></i>Monthly Payment</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px; padding: 5px;">Hello <?php echo $userName; ?></h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
 <div id ="invoice">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <?php
                            $query = "SELECT u.name, u.father_name, u.dob, u.phone_number, b.start_date, b.duration, b.food_status, b.guardian_name, b.relation, b.guardian_contact, b.emergency_contact, b.total_fees, b.selected_room, p.transaction_id,p.payment_date,uf.photo_filename, b.created_at
                                      FROM users u
                                      JOIN bookings b ON u.id = b.user_id
                                      JOIN user_files uf ON u.id = uf.user_id
                                      JOIN payment p ON u.id = p.user_id
                                      WHERE u.id = '$user_id'";

                            $result = $conn->query($query);

                            if ($result === false) {
                                die("Query error: " . $conn->error);
                            }

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<th>Created At:</th>";
                                echo "<td>{$row['created_at']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>User Photo:</th>";
                                echo "<td><img src='uploads/{$row['photo_filename']}' alt='User Photo'></td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Name:</th>";
                                echo "<td>{$row['name']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Father Name:</th>";
                                echo "<td>{$row['father_name']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>DOB:</th>";
                                echo "<td>{$row['dob']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Phone Number:</th>";
                                echo "<td>{$row['phone_number']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Start Date:</th>";
                                echo "<td>{$row['start_date']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Duration (in months):</th>";
                                echo "<td>{$row['duration']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Food Status:</th>";
                                echo "<td>{$row['food_status']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Guardian Name:</th>";
                                echo "<td>{$row['guardian_name']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Relation:</th>";
                                echo "<td>{$row['relation']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Guardian Contact:</th>";
                                echo "<td>{$row['guardian_contact']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Emergency Contact:</th>";
                                echo "<td>{$row['emergency_contact']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Total Fees:</th>";
                                echo "<td>{$row['total_fees']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Selected Room:</th>";
                                echo "<td>{$row['selected_room']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Transaction Id:</th>";
                                echo "<td>{$row['transaction_id']}</td>";
                                echo "</tr>";
                                echo "<th>Transaction Date::</th>";
                                echo "<td>{$row['payment_date']}</td>";
                                echo "</tr>";
                            }

                            // Close the result set
                            $result->close();
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
           <center> <button onclick="downloadReportPDF()" class="btn btn-primary">Download PDF</button></center>
        </div>
</body>
<script>
    function downloadReportPDF(){
    const element= document.getElementById("invoice");
    html2pdf()
    .from(element)
    .save();
    
}
</script>

</html>
<?php
} else {

echo "Invalid credentials. Please try again.";
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <link rel="stylesheet" href="../Styles/innerlogin.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css ">
</head>

<body>

<div class="container">

    <form action="" method="post">
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" value="<?php echo $input_user_id; ?>" readonly>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <input type="submit" value="Login">
        <center><a href="user_dashboard.php" style="text-decoration: none">Back</a></center>
    </form>
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