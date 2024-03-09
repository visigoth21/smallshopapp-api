<?php 

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <title>Home</title>
        <script src="./src/jquery-3.6.4/jquery-3.6.4.min.js"></script>
        <script>

        </script>
    </head>
    <body> 
    <div class="container box">
        <nav class="navbar navbar-expand-md bg-light navbar-light justify-content-center">
        <div class="container-fluid">
            <div class="navbar-brand">
                <img src="./i/brand.gif" width="50"> Small Shop App
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="invoice.php" class="nav-link">Invoice</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link ">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="upload.php" class="nav-link ">Upload</a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
        <div class="login">
            <label for="userValue">User : </label>
            <input type="text" id="userValue" >
            <label for="pwdValue">Password : </label>
            <input type="password" id="pwdValue" >
            <button id="login_button">Login</button><br>
            <div id="errorValue"></div>
        </div>
    </div>
        <script src="./src/login.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>