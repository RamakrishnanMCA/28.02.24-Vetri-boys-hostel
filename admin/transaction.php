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
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Manage Admin</a></li>
            <li><a href="admin_manageuser.php"><i class="fas fa-user"></i> Manage User</a></li>
            <li><a class="nav-link active" href="transaction.php"><i class="fas fa-cog"></i>Transaction details</a></li>
            <li><a href="bookings.php"><i class="fas fa-envelope"></i>Edit Booking</a></li>
            <li><a href="userfiles.php"><i class="fas fa-cog"></i> User Files</a></li>
            <li><a href="roomdetails.php"><i class="fas fa-cog"></i> Room Details</a></li>
            <li><a href="addrooms.php"><i class="fas fa-cog"></i> Add and Edit rooms</a></li>
            <li><a href="userreport.php"><i class="fas fa-cog"></i>User Reports</a></li>
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
        <h1>User Details With Payments: </h1>
    <form class="form-inline mb-3">
            <input class="form-control mr-sm-2" type="text" placeholder="Search by Name or ID" id="searchInput">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="button" onclick="searchUser()">Search</button>
            <button class="btn btn-outline-secondary my-2 my-sm-0" type="button" onclick="showAll()">Show All</button>
    </form>
    <?php
        // Include the configuration file
        require 'config.php';
        
        if (!isset($_SESSION["admin_login"])) {
            header("Location: adminlogin.php");
            exit();
        }

        // Your SQL query to fetch user details from the 'users' table
        $userQuery = "SELECT id, name FROM users";

        // Your SQL query to fetch user details from the 'archived_users' table
        $archivedUserQuery = "SELECT id, name FROM archived_users";

        // Combine both queries using UNION to get unique records
        $combinedQuery = "($userQuery) UNION ($archivedUserQuery)";

        // Execute the combined query
        $combinedResult = mysqli_query($conn, $combinedQuery);

        // Your SQL query to fetch payment details from the 'payment' table
        $paymentQuery = "SELECT user_id, payment_id, transaction_id, payment_date FROM payment";

        // Execute the payment query
        $paymentResult = mysqli_query($conn, $paymentQuery);

        // Check if the queries were successful
        if ($combinedResult && $paymentResult) {
            // Fetch user details
            $users = mysqli_fetch_all($combinedResult, MYSQLI_ASSOC);

            // Fetch payment details
            $payments = mysqli_fetch_all($paymentResult, MYSQLI_ASSOC);

            // Process and display the data
            echo '<table border="1" id="userTable">';
            echo '<tr><th>User ID</th><th>Name</th><th>Payment ID</th><th>Transaction ID</th><th>Payment Date</th></tr>';

            foreach ($users as $user) {
                // Find the payment details for the current user
                $userPayment = array_filter($payments, function ($payment) use ($user) {
                    return $payment['user_id'] == $user['id'];
                });

                if (!empty($userPayment)) {
                    $userPayment = current($userPayment);

                    echo '<tr>';
                    echo '<td>' . $user['id'] . '</td>';
                    echo '<td>' . $user['name'] . '</td>';
                    echo '<td>' . $userPayment['payment_id'] . '</td>';
                    echo '<td>' . $userPayment['transaction_id'] . '</td>';
                    echo '<td>' . $userPayment['payment_date'] . '</td>';
                    echo '</tr>';
                } else {
                    // Handle the case where no payment details are found for the user
                    echo '<tr>';
                    echo '<td>' . $user['id'] . '</td>';
                    echo '<td>' . $user['name'] . '</td>';
                    echo '<td colspan="3">No payment details found</td>';
                    echo '</tr>';
                }
            }

            echo '</table>';
        } else {
            // Handle errors if any
            echo 'Error fetching data: ' . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    ?>
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

        function searchUser() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const tableRows = document.querySelectorAll("#userTable tr");

            tableRows.forEach(row => {
                if (row.getElementsByTagName('td').length > 0) {
                    // Check if the row contains data cells
                    const rowText = Array.from(row.getElementsByTagName('td')).map(td => td.innerText.toLowerCase()).join('');
                    row.style.display = (rowText.includes(searchValue)) ? 'table-row' : 'none';
                }
            });
        }

        function showAll() {
            const tableRows = document.querySelectorAll("#userTable tr");

            tableRows.forEach(row => {
                row.style.display = 'table-row';
            });
        }
    </script>
</body>
</html>
