<?php
// vieworder.php

require "config.php";

if (!isset($_SESSION["admin_login"])) {
    header("Location: adminlogin.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
</head>
<body>
    <div class="container">
        <?php
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            $userQuery = "SELECT name FROM users WHERE id = ?";
            $stmtUser = $conn->prepare($userQuery);
            $stmtUser->bind_param("i", $user_id);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();

            if ($resultUser === FALSE) {
                echo "Error in user query: " . $conn->error;
            }

            $rowUser = $resultUser->fetch_assoc();
            $userName = $rowUser['name'];


            // Fetch detailed food order information based on the user ID
            $query = "SELECT * FROM food_order WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result === FALSE) {
                echo "Error in query: " . $conn->error;
            }

            // Display the detailed food order information
            echo "<h1>Food Orders: $userName (ID: $user_id)</h1>";
            echo "<table border='1'>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Food Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Meal Type</th>
                            <th>Ordered Time</th>
                            <th>Action</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['foodname']}</td>";
                echo "<td>{$row['foodcount']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>{$row['bf_lun_din']}</td>";
                echo "<td>{$row['created_at']}</td>";
                echo "<td><button class='btn btn-danger' onclick='removeOrder({$row['id']})'>Remove</button></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";

            $stmt->close();
        } else {
            echo "User ID not provided.";
        }
        ?>
        <button class="btn btn-primary" onclick="goBack()">Go Back</button>
    </div>

    <script>
        function removeOrder(orderId) {
            // Confirm if the user wants to remove the order
            var confirmRemove = confirm("Are you sure you want to remove this order?");
            
            if (confirmRemove) {
                // Redirect to a script to handle order removal
                window.location.href = "delete_order.php?order_id=" + orderId;
            }
        }

        function goBack() {
            // Navigate back to the previous page
            window.history.back();
        }
    </script>
</body>
</html>
