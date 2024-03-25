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

// Fetch food_status from the bookings table
$bookingQuery = "SELECT food_status FROM bookings WHERE user_id = '$user_id'";
$bookingResult = mysqli_query($conn, $bookingQuery);

if ($bookingResult) {
    $bookingRow = mysqli_fetch_assoc($bookingResult);
    $foodStatus = $bookingRow['food_status'];

    // Set the values based on food_status
    if ($foodStatus == 1) {
        $monthlyFees = 6000;
    } else {
        $monthlyFees = 3000;
    }
} else {
    // Handle the error if necessary
    $monthlyFees = 3000; // Default value if unable to fetch from the database
}

$previousMonth = date("Y-m-d H:i:s", strtotime("last month"));
$totalFoodPriceQuery = "SELECT COALESCE(SUM(price), 0) AS total_price FROM food_order WHERE user_id = '$user_id' AND MONTH(created_at) = MONTH('$previousMonth') AND YEAR(created_at) = YEAR('$previousMonth')";

$totalFoodPriceResult = mysqli_query($conn, $totalFoodPriceQuery);

if ($totalFoodPriceResult) {
    $totalFoodPriceRow = mysqli_fetch_assoc($totalFoodPriceResult);
    $totalMessFoodPrice = $totalFoodPriceRow['total_price'];
} else {
    // Handle the error if necessary
    echo "Error: " . mysqli_error($conn);
    $totalMessFoodPrice = 0; // Default value if unable to fetch from the database
}

$totalAmount = $monthlyFees + $totalMessFoodPrice;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission

    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $monthly_fees = $_POST['monthly_fees'];
    $total_mess_food_price = $_POST['total_mess_food_price'];
    $total_amount = $_POST['total_amount'];
    $transaction_id = $_POST['transaction_id'];

    // Check if a payment record already exists for the current month
    $currentDateTime = date("Y-m-d H:i:s");
    $currentMonth = date("Y-m");
    $existingPaymentQuery = "SELECT id FROM month_payment WHERE user_id = '$user_id' AND created_at >= '$currentMonth-01 00:00:00' AND created_at <= '$currentMonth-31 23:59:59'";
    $existingPaymentResult = mysqli_query($conn, $existingPaymentQuery);

    if (mysqli_num_rows($existingPaymentResult) > 0) {
        // Payment record for the current month already exists
        echo "<script>alert('You have already paid for the current month. Payment for the next month can be made after the current month ends.')</script>";
    } else {
        // Insert data into the payment table with created_at
        $insertPaymentQuery = "INSERT INTO month_payment (user_id, name, monthlyfees, total_mess_food_price, total_amount, transaction_id) VALUES ('$user_id', '$name', '$monthly_fees', '$total_mess_food_price', '$total_amount', '$transaction_id')";

        if (mysqli_query($conn, $insertPaymentQuery)) {
            // Successful insertion, you can redirect or show a success message
            echo "<script>alert('Payment successfully submitted!')</script>";
        } else {
            // Handle the error if necessary 
            echo "Error: " . mysqli_error($conn);
        }
    }

}

// Fetch payment records from the month_payment table
$paymentRecordsQuery = "SELECT * FROM month_payment WHERE user_id = '$user_id'";
$paymentRecordsResult = mysqli_query($conn, $paymentRecordsQuery);

$paymentTable = "<h3>Payment History</h3><table class='table'><thead><tr><th>ID</th><th>User ID</th><th>Name</th><th>Monthly Fees</th><th>Total Mess Food Price</th><th>Total Amount</th><th>Transaction ID</th><th>Created At</th></tr></thead><tbody>";

if ($paymentRecordsResult) {
    while ($row = mysqli_fetch_assoc($paymentRecordsResult)) {
        $paymentTable .= "<tr>";
        $paymentTable .= "<td>{$row['id']}</td>";
        $paymentTable .= "<td>{$row['user_id']}</td>";
        $paymentTable .= "<td>{$row['name']}</td>";
        $paymentTable .= "<td>{$row['monthlyfees']}</td>";
        $paymentTable .= "<td>{$row['total_mess_food_price']}</td>";
        $paymentTable .= "<td>{$row['total_amount']}</td>";
        $paymentTable .= "<td>{$row['transaction_id']}</td>";
        $paymentTable .= "<td>{$row['created_at']}</td>";
        $paymentTable .= "</tr>";
    }
} else {
    // Handle the error if necessary
    $paymentTable .= "<tr><td colspan='8'>No payment records found.</td></tr>";
}

$paymentTable .= "</tbody></table>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/user_dashboard.css">
    <link rel="stylesheet" href="../Styles/innerlogin.css">
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
            <li><a href="foodorder.php"><i class="fas fa-cog"></i> Food Details</a></li>
            <li><a class="nav-link active" href="monthlypayment.php"><i class="fas fa-cog"></i>Monthly Payment</a></li>
            <li><a href="leaveform.php"><i class="fas fa-cog"></i> Leave Form</a></li>
            <li><a  href="feedback.php"><i class="fas fa-cog"></i> Query/Feedback</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div id="helloAdminSection">
        <h5 style="display: inline-block; margin-right: 10px;">Hello <?php echo $userName; ?></h5>
        <div class="show-sidebar-btn" onclick="toggleSidebar()"  style="display: inline-block;font-size: 24px; color: blue; cursor: pointer;">&#9776;</div>
    </div>
    <div class="main-content" id="mainContent">
        <div class="container">
            <h3>Payment QR Code</h3>
            <h6> Pay Consciously </h6>
            <img src="../images/payment.jpg" alt="Payment QR Code">
            <form action="monthlypayment.php" method="post">
                <label for="user_id">User ID:</label>
                <input type="number" name="user_id" id="user_id" required class="form-control" value="<?php echo $user_id; ?>" readonly>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required class="form-control" value="<?php echo $userName; ?>" readonly>
                <label for="monthly_fees">Monthly Fees:</label>
                <input type="number" id="monthly_fees" name="monthly_fees" class="form-control" value="<?php echo $monthlyFees; ?>" readonly>
                <label for="total_mess_food_price">Total Mess Food Price(last Month):</label>
                <input type="number" id="total_mess_food_price" name="total_mess_food_price" class="form-control" value="<?php echo $totalMessFoodPrice; ?>" readonly>
                <label for="total_amount">Total Amount:</label>
                <input type="number" id="total_amount" name="total_amount" class="form-control" value="<?php echo $totalAmount; ?>" readonly>
                <label for="transaction_id">Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id" placeholder="Careful" required>
                <button type="submit">Submit Payment</button>
            </form>
        </div>
        <?php echo $paymentTable; ?>
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
