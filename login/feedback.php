<?php
require "config.php";
if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
    $queryText = isset($_POST['query']) ? $_POST['query'] : null;
    $adminReply = isset($_POST['admin_reply']) ? $_POST['admin_reply'] : 'Admin will reply soon';

    $validateUserQuery = "SELECT * FROM users WHERE id = '$userID'";
    $result = $conn->query($validateUserQuery);

    if ($result->num_rows > 0) {
        $insertQuery = "INSERT INTO user_query (user_id, query_text, admin_reply) VALUES ('$userID', '$queryText', '$adminReply')";

        if ($conn->query($insertQuery) === TRUE) {
            // Query submitted successfully
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    } else {
        echo "Invalid user ID.";
    }
}
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
    <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="payment.php"><i class="fas fa-cog"></i> Payment</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a class="nav-link active" href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello User</h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
    <form id="queryForm" action="feedback.php" method="post" style="margin-top: 20px;">
        <div class="form-group">
            <h2><label for="query">Submit your Query:</label></h2>
            <textarea class="form-control" id="query" name="query" rows="4" style="width:50%;" required></textarea>
        </div>

        <button type="submit">Submit Query</button>
        </form>

        <?php 
        
        $userID = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $fetchQueriesQuery = "SELECT * FROM user_query WHERE user_id = '$userID'";
        $queriesResult = $conn->query($fetchQueriesQuery);

        if ($queriesResult === FALSE) {
            
            echo "Error in fetchQueriesQuery: " . $conn->error;
        } elseif ($queriesResult->num_rows > 0) {
            echo "<label><h2>Previous Queries:</h2></label>";
            echo "<table border='1'>";
            echo "<tr><th>Query</th><th>Admin Reply</th><th>Action</th></tr>";
            while ($row = $queriesResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['query_text']}</td>";
                echo "<td>{$row['admin_reply']}</td>";
                echo "<td>
                        <form action='delete_feedback.php' method='post'>
                            <input type='hidden' name='deleteQueryID' value='{$row['id']}'>
                            <button type='submit' name='deleteQuery'>Remove</button>
                        </form>
                      </td>";
            
                echo "</tr>";
            }
            
        } else {
            echo "No previous queries found.";
        }
        ?>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var mainContent = document.getElementById("mainContent");

            if (sidebar.style.display === "none" || sidebar.style.display === "") {
                sidebar.style.display = "block";
                mainContent.style.marginLeft = "250px"; 
            } else {
                sidebar.style.display = "none";
                mainContent.style.marginLeft = "0";
            }
        }

        
    </script>
</body>
</html>
