<?php
//---------------------------------------------------------------------------//
require $_SERVER['DOCUMENT_ROOT'] . '\vendor\autoload.php';
set_error_handler("ErrorHandler::handleError");
set_exception_handler("errorHandler::handleException");
ini_set('MAX_EXECUTION_TIME', 103600);
//---------------------------------------------------------------------------//
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
//---------------------------------------------------------------------------//
$database = new Database(
    $_ENV["DB_HOST"],
    $_ENV["DB_NAME"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"]
);
//---------------------------------------------------------------------------//
//---------------------------------------------------------------------------//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $i = 1;
    $targetDir = "C:/xampp/htdocs/upFiles/";
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $iSkip = 0;
    // Check if file is a CSV
    if ($fileType != "csv") {
        echo "Only CSV files are allowed.";
        exit();
    }

    $parts = explode(".", $fileName);
    $Name = $parts[0];
    // skip Lines, part_number, upc, description, mfg, list, cost, supersedes_to, weight, part_html
    switch ($Name) {
        case "Briggs":
            $order = array(1, 0, 13, 1, 2, 5, 8, 4, 1);
            break;
        case "Kawasaki":
            $order = array(1, 1, 4, 2, "x", 7, 6, 0, 20, "KW");
            break;
        case "Rotary":
            $order = array(1, 9, 14, 4, "x", 11, 13, 3, 5, "RY");
            break;
        case "Kohler":
            $order = array(2, 1, 20, 2, 0, 8, 9, 3, 15);
            break;
        case "Oregon":
            $order = array(2, 1, 20, 2, 0, 8, 9, 3, 15);
            break;
        case "MTD":
            $order = array(2, 1, 20, 2, 0, 8, 9, 3, 15);
            break;
        case "Generac":
            $order = array(2, 1, 20, 2, 0, 8, 9, 23, 15);
            break;
        case "HydroGear":
            $order = array(2, 1, 20, 2, 0, 8, 9, 23, 15);
            break;
        default:
            echo "Resource error non-standard file uesd";
            $order = null;
            exit;
    }

    // Move uploaded file to target directory
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        // Parse the CSV file
        $file = fopen($targetFile, "r");
        if ($file) {
            // if file success prepare loader object
            $Loader_gateway = new LoaderGateway($database);
            // loop data
            while (($data = fgetcsv($file)) !== false) {
                $prodMfg = $order[4];
                if ($prodMfg == "x") {
                    $prodMfg = $order[9];
                } else {
                    $prodMfg = $data[$order[4]];
                }
                // set rows to skip
                $iSkip = $order[0];
                if ($i > $iSkip) {
                    // Load line using Loader object
                    if ($Loader_gateway->loadLine(
                        $data[$order[1]],
                        $data[$order[2]],
                        $data[$order[3]],
                        $prodMfg,
                        $data[$order[5]],
                        $data[$order[6]],
                        $data[$order[7]],
                        $data[$order[8]]
                    )) {
                    } else {
                        echo " -- Line Error --";
                    }
                }
                $i = $i + 1;
            }
            fclose($file);
            echo $i . " - Lines processed <br>";
            echo $Loader_gateway->itemCount() . " - Items in system <br>";
            //echo " | count - " . $i . " | skips = " . $iSkip . " | MFG = " . $prodMfg;
        } else {
            echo "Error opening the CSV file.";
        }
    } else {
        echo "Error uploading the file.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <title>CSV File Upload</title>
    <script src="./src/jquery-3.6.4/jquery-3.6.4.min.js"></script>
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
                            <a href="index.php" class="nav-link ">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="invoice.php" class="nav-link ">Invoice</a>
                        </li>
                        <li class="nav-item">
                            <a href="register.php" class="nav-link ">Register</a>
                        </li>
                        <li class="nav-item">
                            <a href="upload.php" class="nav-link active">Upload</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <h2>Upload a CSV File</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" required>
        <input type="submit" value="Upload" name="submit">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>