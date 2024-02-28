<?php
// Assuming you have a database connection established
require "config.php";

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
    <link rel="stylesheet" href="../Styles/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="download.js"></script>
    <style>
    .table {
        margin: 0 auto;
        width: 38%;
        max-width: 100%; /* Make the table take the full width of its container */
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
    <div class="sidebar">
        <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i> Upload Files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Final Report</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
<div class="main-content" >
 <div id ="invoice">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <?php
                            $query = "SELECT u.name, u.father_name, u.dob, u.phone_number, b.start_date, b.duration, b.food_status, b.guardian_name, b.relation, b.guardian_contact, b.emergency_contact, b.total_fees, b.selected_room, uf.photo_filename, b.created_at
                                      FROM users u
                                      JOIN bookings b ON u.id = b.user_id
                                      JOIN user_files uf ON u.id = uf.user_id
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

</html>
<?php
} else {
// Authentication failed, show error message
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
    <style>
        body {
    background-image: url('background.jpg');
    background-size: cover;
    background-position: center;
    font-family: Arial, sans-serif;
}
        form 
        {
    display: flex;
    flex-direction: column;
        }
        .container {
    width: 300px;
    padding: 16px;
    background-color: white;
    margin: 0 auto;
    margin-top: 100px;
    box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.3);
    border-color: #00d0f5;
    outline: none;
    box-shadow: 0 0 10px rgba(0, 254, 254, 0.3);
}
input {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

label {
    display: block;
    margin-top: 10px;
    transition: transform 0.3s ease;
}

label:hover {
    transform: translateY(-5px);
}
input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
    display: block;
    width: 100%;
    padding: 8px;
    margin-top: 6px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="tel"]:focus {
    border-color: #6699cc;
    outline: none;
    box-shadow: 0 0 10px rgba(102, 153, 204, 0.3);
}

    </style>
</head>

<body>

<div class="container">

    <form action="" method="post">
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Login">

    </form>
</div>


    <script>
    function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        }
    </script>
</body>
</html>