<?php
require 'config.php';
if(isset($_POST["submit"])){
    $name=$_POST["name"];
    $father_name=$_POST["father-name"];
    $dob=date('y-m-d',strtotime($_POST["dob"]));
    $address=$_POST["address"];
    $phone_number=$_POST["phone-number"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $reenter_password=$_POST["re-enter-password"];
    $duplicate=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'" );
    if(mysqli_num_rows($duplicate)>0){
        echo
        "<script> alert('Email has Already Taken'); </script>";
    }
    else{
        if($password==$reenter_password){
            $query ="INSERT INTO users VALUES('','$name','$father_name','$dob','$address','$phone_number','$email','$password')";
            mysqli_query($conn,$query);
            echo
        "<script> alert('Registration Successful'); </script>"; 
               
        }  
        else{
            echo
        "<script> alert('Password does not match'); </script>";
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="en"> <head> <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Sign Up Page</title> 
    <link rel="stylesheet" href="../Styles/login.css" type="text/css">
    <script>
        function validateForm() {
            var name = document.getElementById("name").value;
            var fatherName = document.getElementById("father-name").value;
            var dob = document.getElementById("dob").value;
            var address = document.getElementById("address").value;
            var phoneNumber = document.getElementById("phone-number").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var reenterPassword = document.getElementById("re-enter-password").value;

            
            if (name === "" || fatherName === "" || dob === "" || address === "" || phoneNumber === "" || email === "" || password === "" || reenterPassword === "") {
                alert("All fields must be filled out");
                return false;
            }
            var phoneno = /^(\+\d{1,2}\s?)?1?\-?\.?\s?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/;
            
            if (!phoneno.test(phoneNumber)) {
                alert('Please enter a valid phone number in the format (123) 456-7890.');
                return false;
            }

           
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Invalid email address");
                return false;
            }

            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            
            if (!regex.test(password)) {
                alert('Please enter a strong password. The password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.');
                return false;
            }

     
            if (password !== reenterPassword) {
                alert("Passwords do not match");
                return false;
            }

            return true;
        }
    </script>
</head> 
    <body> 
        <div class="container">
            <h1>Hostel Booking System</h1>
        <h1>Sign Up</h1> 
        <form method="post" autocomplete="off" onsubmit="return validateForm()" action="registration.php">

     <label for="name">Name:</label> 
     <input type="text" id="name" name="name" required>

    <label for="father-name">Father Name:</label>
    <input type="text" id="father-name" name="father-name" required>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" required>

    <label for="address">Address:</label>
    <textarea id="address" name="address" required></textarea>

    <label for="phone-number">Phone Number:</label>
    <input type="tel" id="phone-number" name="phone-number" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="re-enter-password">Re-enter Password:</label>
    <input type="password" id="re-enter-password" name="re-enter-password" required>

    <button type="submit" name="submit">Sign Up</button>
</form>
<br>

<center><a href="login.php">Login</a></center>
</div>


</body>

</html>