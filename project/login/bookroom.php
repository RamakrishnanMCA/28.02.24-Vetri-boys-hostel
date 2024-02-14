<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>Hello user</h2>
        <br>
        <ul>
            <li><a href="home.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i>My details</a></li>
            <li><a href="bookroom.php"><i class="fas fa-envelope"></i> Book a Room</a></li>
            <li><a href="uploadfiles.php"><i class="fas fa-cog"></i>Upload files</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div class="main-content">
    <h3 class="card-title mt-5"></h3>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Start Date</h6>
                                        <div class="form-group">
                                            <input type="date" name="startdate" id="startdate" required class="form-control" placeholder="date">
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Seater</h6>
                                        <div class="form-group">
                                            <input type="number" name="seater" id="seater" required class="form-control" placeholder="Seater">
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Expected Duration</h6>
                                        <div class="form-group">
                                        <select class="custom-select mr-sm-2" id="duration" name="duration">
                                            <option selected>Choose...</option>
                                            <option value="1">One Month</option>
                                            <option value="2">Two Month</option>
                                            <option value="3">Three Month</option>
                                            <option value="4">Four Month</option>
                                            <option value="5">Five Month</option>
                                            <option value="6">Six Month</option>
                                            <option value="7">Seven Month</option>
                                            <option value="8">Eight Month</option>
                                            <option value="9">Nine Month</option>
                                            <option value="10">Ten Month</option>
                                            <option value="11">Eleven Month</option>
                                            <option value="12">Twelve Month</option>
                                        </select>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Food Status:</h6>
                                        <div class="form-group">
                                        <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" value="1" name="foodstatus"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio1">Required <code>Extra 3000 Per Month</code></label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio2" value="0" name="foodstatus"
                                        class="custom-control-input" checked>
                                    <label class="custom-control-label" for="customRadio2">Not Required</label>
                                        </div>
                                </div>
                            </div>
                        </div>
</div>

</div>
    <h3 class="card-title mt-5">Guardian's Information</h3>
    <div class="row">

    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Guardian Name</h6>
                    <div class="form-group">
                        <input type="text" name="gname" id="gname" class="form-control" placeholder="Enter Guardian's Name" required>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Relation</h6>
                                        <div class="form-group">
                                            <input type="text" name="grelation" id="grelation" required class="form-control" placeholder="Student's Relation with Guardian">
                                        </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Contact Number</h6>
                                        <div class="form-group">
                                            <input type="text" name="gcontact" id="gcontact" required class="form-control" placeholder="Enter Guardian's Contact No.">
                                        </div>
                                </div>
                            </div>
                        </div>
    </div>
    
    <h3 class="card-title mt-5">Note</h3>
    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Emergency Contact Number</h6>
                                    <div class="form-group">
                                        <input type="number" name="econtact" id="econtact" class="form-control" placeholder="Enter Emergency Contact No." required>
                                    </div>
                            </div>
                        </div>
                    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Total Fees Per Month</h6>
                                    <div class="form-group">
                                        <input type="text" name="fpm" id="fpm" placeholder="Your total fees" class="form-control" value="3000" readonly>
                                    </div>
                            </div>
                        </div>
                    </div>
             </div>
<h3 class="card-title mt-5">Choose Room:</h3>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room1" name="room" value="room1">
            <label for="room1">Room1</label>
            <img src="../images/Room1.png" alt="Room1" style="width:100%">
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room2" name="room" value="room2">
            <label for="room2">Room2</label>
            <img src="../images/Room2.png" alt="Room2" style="width:100%; height:200px">
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room3" name="room" value="room3">
            <label for="room3">Room3</label>
            <img src="../images/Room3.png" alt="Room3" style="width:100%; height:200px">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room4" name="room" value="room3">
            <label for="room4">Room4</label>
            <img src="../images/Room4.png" alt="Room4" style="width:100%; height:200px">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <input type="radio" id="room5" name="room" value="room3">
            <label for="room5">Room5</label>
            <img src="../images/Room5.png" alt="Room5" style="width:100%">
        </div>
    </div>
</div>
<!-- Add more rooms as needed -->

    
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        }
    </script>
</body>
</html>