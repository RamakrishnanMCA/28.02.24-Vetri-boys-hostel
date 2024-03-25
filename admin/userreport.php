<?php
// Assuming you have a database connection established
require "config.php";

if (!isset($_SESSION['admin_login'])) {
    header("Location: adminlogin.php");
    exit();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user report details
$query = "SELECT u.name,u.id, u.father_name, u.dob, u.phone_number, b.start_date, b.duration, b.food_status, b.guardian_name, b.relation, b.guardian_contact, b.emergency_contact, b.total_fees, b.selected_room, p.transaction_id,p.payment_date,uf.photo_filename, b.created_at
          FROM users u
          JOIN bookings b ON u.id = b.user_id
          JOIN user_files uf ON u.id = uf.user_id
          JOIN payment p ON u.id = p.user_id";

$result = $conn->query($query);

if ($result === false) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
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
        <h2>Hello admin</h2>
        <br>
        <ul><li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Manage Admin</a></li>
            <li><a href="admin_manageuser.php"><i class="fas fa-user"></i> Manage User</a></li>
            <li><a href="foodorder.php"><i class="fas fa-cog"></i>Order Food</a></li>
            <li><a href="monthpayment.php"><i class="fas fa-cog"></i>Monthly Payment details</a></li>
            <li><a href="transaction.php"><i class="fas fa-cog"></i>Transaction details</a></li>
            <li><a href="bookings.php"><i class="fas fa-envelope"></i>Edit Booking</a></li>
            <li><a href="userfiles.php"><i class="fas fa-cog"></i> User Files</a></li>
            <li><a href="roomdetails.php"><i class="fas fa-cog"></i> Room Details</a></li>
            <li><a href="addrooms.php"><i class="fas fa-cog"></i> Add and Edit rooms</a></li>
            <li><a class="nav-link active" href="userreport.php"><i class="fas fa-cog"></i>User Reports</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i>Leave Form Details</a></li>
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
    <form class="form-inline mb-3">
            <input class="form-control mr-sm-2" type="search" placeholder="Search UserName" id="searchInput">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="button" onclick="searchUser()">Search</button>
            <button class="btn btn-outline-secondary my-2 my-sm-0" type="button" onclick="showAll()">Show All</button>
    </form> 
    <?php
while ($row = $result->fetch_assoc()) {
    echo "<div class='invoice' id='invoice{$row['id']}'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table'>";
    echo "<tr>";
    echo "<th>Created At: </th>";
    echo "<td>{$row['created_at']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>User Id:</th>";
    echo "<td>{$row['id']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>User Photo: </th>";
    echo "<td><img src='../login/uploads/{$row['photo_filename']}' alt='User Photo' style='width: 80px; height: 80px'></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Name: </th>";
    echo "<td>{$row['name']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Father Name: </th>";
    echo "<td>{$row['father_name']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>DOB: </th>";
    echo "<td>{$row['dob']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Phone Number: </th>";
    echo "<td>{$row['phone_number']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Start Date: </th>";
    echo "<td>{$row['start_date']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Duration: </th>";
    echo "<td>{$row['duration']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Food Status: </th>";
    echo "<td>{$row['food_status']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Guardian Name: </th>";
    echo "<td>{$row['guardian_name']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Relation: </th>";
    echo "<td>{$row['relation']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Guardian Contact: </th>";
    echo "<td>{$row['guardian_contact']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Emergency Contact: </th>";
    echo "<td>{$row['emergency_contact']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Total Fees: </th>";
    echo "<td>{$row['total_fees']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Selected Room: </th>";
    echo "<td>{$row['selected_room']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Transaction Id: </th>";
    echo "<td>{$row['transaction_id']}</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Transaction Date: </th>";
    echo "<td>{$row['payment_date']}</td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>";
    echo "<center><button onclick='downloadReportPDF({$row['id']})' class='btn btn-primary'>Download PDF</button></center>";
    echo "</div>";

}
?>


    </div>
    <script>
        function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("mainContent");

        if (sidebar.style.display === "none" || sidebar.style.display === "") {
            sidebar.style.display = "block";
            mainContent.style.marginLeft = "25px"; // Adjust the width as needed
        } else {
            sidebar.style.display = "none";
            mainContent.style.marginLeft = "0";
        }
    }
        function downloadReportPDF(id) {
            const element = document.getElementById(`invoice${id}`);
            html2pdf().from(element).save();
        }
         function searchUser() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();

            // Loop through each user row and show/hide based on the search value
            <?php
            $result->data_seek(0); // Reset result set pointer
            while ($row = $result->fetch_assoc()) {
                echo "const userRow{$row['id']} = document.getElementById('invoice{$row['id']}');\n";
                echo "userRow{$row['id']}.style.display = (userRow{$row['id']}.innerText.toLowerCase().includes(searchValue)) ? 'table' : 'none';\n";
            }
            ?>
        }

        function showAll() {
            <?php
            $result->data_seek(0); // Reset result set pointer
            while ($row = $result->fetch_assoc()) {
                echo "const userRow{$row['id']} = document.getElementById('invoice{$row['id']}');\n";
                echo "userRow{$row['id']}.style.display = 'table';\n";
            }
            ?>
        }
    </script>
</body>
</html>