<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Styles/dashboard.css">
    <style>
        #preview-container {
            display: none;
        }
        #preview-photo {
            margin-top: 10px;
            width: 48%;
            float: left;
        }
        #photo-preview {
            width: 45%;
            height: auto;
        }
        #preview-id-card {
            margin-top: 10px;
            width: 48%;
            float: left;
        }
        #id-card-preview {
            width: 100%;
            height: 600px;
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
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    </div>
    <div class="main-content">
    <h3>Upload Files</h3>
    <table>
    <th>Photo:</th>
    <td><input type="file" accept=".jpg,.png" onchange="previewImage(event, 'photo')"></td>
    <th>Id Proof: </th>
    
    <td><input type="file" accept=".pdf" onchange="previewPdf(event, 'id-card')"></td> </table>
    <div id="preview-container">
        <div id="preview-photo">
            <img id="photo-preview" src="" alt="Passport size photo preview">
        </div>
        <div id="preview-id-card">
            <embed id="id-card-preview" type="application/pdf" width="100%" height="600px">
        </div>
    </div>
   
    <script>
       function previewImage(event, id) {
            var previewContainer = document.getElementById("preview-container");
            previewContainer.style.display = "block";
            var previewImage = document.getElementById("photo-preview");
            var file = event.target.files[0];
            var reader = new FileReader();
            
            if (file.type.startsWith("image/")) {
                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                alert("Invalid file type. Please select an image file for the passport size photo.");
                previewContainer.style.display = "none";
            }
        }
        
        function previewPdf(event, id) {
            var previewContainer = document.getElementById("preview-container");
            previewContainer.style.display = "block";
            var previewPdf = document.getElementById("id-card-preview");
            var file = event.target.files[0];
            var reader = new FileReader();
            
            if (file.type === "application/pdf") {
                reader.onload = function (event) {
                    previewPdf.src = event.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                alert("Invalid file type. Please select a PDF file for the college ID card.");
                previewContainer.style.display = "none";
            }
        }
    </script>
</body>
</html>