<?php
require "config.php";
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id'];
$userNameQuery = "SELECT name FROM users WHERE id = '$user_id'";
$userNameResult = mysqli_query($conn, $userNameQuery);

if ($userNameResult) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
} else {
    // Handle the error if necessary
    $userName = 'Guest'; // Default value if the name is not found
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$foods_query = "SELECT * FROM foods";
$foods_result = $conn->query($foods_query);

if ($foods_result === FALSE) {
    echo "Error in query for fetching foods: " . $conn->error;
}
$user_orders_query = "SELECT * FROM food_order WHERE user_id = '$user_id'";
$user_orders_result = $conn->query($user_orders_query);

if ($user_orders_result === FALSE) {
    echo "Error in query for fetching user orders: " . $conn->error;
}
$orderPlaced = false;
// Handle form submission to place an order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Iterate over submitted form values
    foreach ($_POST as $key => $value) {
        // Check if the key is related to food order
        if (strpos($key, 'food_item_') === 0) {
            $rowId = substr($key, strlen('food_item_'));

            // Retrieve values using correct names
            $food_item_id = $_POST["food_item_$rowId"];
            $foodcount = $_POST["foodcount_$rowId"];
            $meal_type = $_POST["meal_type_$rowId"];

            // Check if the food count is greater than 0
            if ($foodcount > 0) {
                // Fetch the selected food's details
                $food_details_query = "SELECT foodname, price, photo_name FROM foods WHERE id = $food_item_id";
                $food_details_result = $conn->query($food_details_query);

                if ($food_details_result === FALSE) {
                    echo "Error in query for fetching food details: " . $conn->error;
                }

                $food_details_row = $food_details_result->fetch_assoc();
                $foodname = $food_details_row['foodname'];
                $price = $food_details_row['price'];

                // Calculate total price
                $total_price = $foodcount * $price;

                // Insert the order into the database
                $order_query = "INSERT INTO food_order (user_id, foodname, foodcount, price, bf_lun_din) VALUES ('$user_id', '$foodname', '$foodcount', '$total_price', '$meal_type')";

                if ($conn->query($order_query) === TRUE) {
                    $orderPlaced = true;
                } else {
                    echo "Error placing order: " . $conn->error;
                }
            } else {
                //echo "Food count must be greater than 0.";
            }
        }
    }
}
if ($orderPlaced) {
    echo "<script>alert('Order placed successfully!'); window.location.href = 'foodorder.php';</script>";
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
    <style>#topImage {
            display: block;
            margin: 0 auto; /* Center the image horizontally */
            width: 50%; /* Adjust the width as needed */
            transition: transform 0.3s ease; /* Add smooth transition effect */
        }

        #topImage:hover {
            transform: scale(1.2); /* Adjust the scale factor as needed for zoom */
        }</style>
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
            <li><a href="report.php"><i class="fas fa-cog"></i> Download PDF</a></li>
            <li><a class="nav-link active" href="foodorder.php"><i class="fas fa-cog"></i> Food Details</a></li>
            <li><a href="monthlypayment.php"><i class="fas fa-cog"></i>Monthly Payment</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
    <h5 style="display: inline-block; margin-right: 10px;">Hello <?php echo $userName; ?></h5>
    <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
        <h1>Food Details:</h1>
    <img id="topImage" src="../images/foodmenu.jpg" alt="Top Image">

    <form method="post" action="foodorder.php">
    <table class="table table-bordered">
    <thead>
    <tr>
        <th>Food Name</th>
        <th>Image</th>
        <th>Price</th>
        <th>Food Count</th>
        <th>Meal Type</th>
        <th>Total Price</th>
    </tr>
</thead>
<tbody>
    <?php
    $rowId = 1;
    while ($food_row = $foods_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$food_row['foodname']}</td>";
        echo "<td><img src='../admin/uploads/{$food_row['photo_name']}' alt='Food Image' style='width: 100px; height:100px;'></td>";
        echo "<td>{$food_row['price']}</td>";
        echo "<td><input type='number' id='foodcount_$rowId' name='foodcount_$rowId' value='0' min='0' onchange='updateTotalPrice($rowId)' required></td>";
        
        // Add the new column for meal type
        echo "<td>";
        echo "<select name='meal_type_$rowId'>";
        echo "<option value='breakfast'>Breakfast</option>";
        echo "<option value='lunch'>Lunch</option>";
        echo "<option value='dinner'>Dinner</option>";
        echo "</select>";
        echo "</td>";
        
        echo "<td><input type='text' id='total_price_$rowId' name='total_price_$rowId' readonly></td>";
        
        echo "<td>";
        echo "<input type='hidden' name='food_item_$rowId' value='{$food_row['id']}'>";
        echo "<input type='hidden' id='price_$rowId' value='{$food_row['price']}'>";
        echo "</td>";
        echo "</tr>";
        $rowId++;
    }
    ?>
</tbody>

    </table>
    <center><button type="submit" class="btn btn-primary">Place Order</button></center>
</form>
<div>
        <h2>User's Food Orders:</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Food Name</th>
                    <th>Food Count</th>
                    <th>Total Price</th>
                    <th>Period</th>
                    <th>Ordered DateTime</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($order_row = $user_orders_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$order_row['id']}</td>";
                    echo "<td>{$order_row['foodname']}</td>";
                    echo "<td>{$order_row['foodcount']}</td>";
                    echo "<td>{$order_row['price']}</td>";
                    echo "<td>{$order_row['bf_lun_din']}</td>";
                    echo "<td>{$order_row['created_at']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>

    <script>
        function updateTotalPrice(rowId) {
            var quantityInput = document.getElementById("foodcount_" + rowId);
            var priceInput = document.getElementById("price_" + rowId);
            var totalInput = document.getElementById("total_price_" + rowId);

            // Calculate total price
            var total_price = quantityInput.value * priceInput.value;

            // Set the total price input value
            totalInput.value = total_price;
        }
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