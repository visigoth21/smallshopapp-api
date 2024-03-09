<?php 
//------------------------------------------------V1---------------------------------------------//
session_start();        
//-----------------------------------------------------------------------------------------------//
if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    if (isset($_POST['btnClear'])) {
        unset ($_SESSION['cart']);
        $json = "Enter Parts";
        $_SESSION['cart'] = [];
    } 
    else 
    {
        $ItemNum = $_POST["item"];
        if(!isset($_SESSION['cart']))
        { 
            $dataSet = [];
            $_SESSION['cart'] = [];
        }
            $dataSet = $_SESSION['cart'];         
            $testSet = AddItemToCart($dataSet, $ItemNum);

            if ($testSet) {
                $dataSet = $testSet;
            }
            else
            {
                $result = GetItem($ItemNum);
                if ($result)
                {
                    $dataSet[] = ["count" => 1, 
                        "part_number" => $result["data"][0]["part_number"], 
                        "upc" => $result["data"][0]["upc"], 
                        "description" => $result["data"][0]["description"], 
                        "list" => $result["data"][0]["list"]];           
                }
            }
        
        $_SESSION['cart'] = $dataSet; 
        $json = json_encode($dataSet);
    }
//-----------------------------------------------------------------------------------------------//
}
function AddItemToCart(array $dataLine, string $ItemVal): array | false
{
    foreach($dataLine as &$value) 
    {
        if ($value["part_number"] === $ItemVal || $value["upc"] === $ItemVal) {
            $set = $value["count"];
            $value["count"] = ++$set;
            return $dataLine;
        }
    }
    return false;
}
//-----------------------------------------------------------------------------------------------//
function GetItem(string $upcOrPartNum): array | false
{
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, "http://localhost/api/v1/search/". $upcOrPartNum);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Small Shop app');
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

    $responseData  = json_decode($query, true);
    
    if (array_key_exists("data", $responseData) && !$upcOrPartNum == "") {
        return $responseData;
    }
    else
    {
        return false;
    }
}
//-----------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <title>Search</title>
        <script src="./src/jquery-3.6.4.min.js"></script>
    </head>
    <body>
    <div class="container box">
        <nav class="navbar navbar-expand-md bg-light navbar-light justify-content-center">
        <div class="container-fluid">
            <div class="navbar-brand">
                <img src="./i/brand.gif" width="50"> Store Name
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
                        <a href="#" class="nav-link active">Search</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link ">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">Link 4</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">Link 5</a>
                    </li>
                    <li class="nav-item">
                      <a href="test3.html" class="nav-link ">Test 3</a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
    </div>
    
    
    <main class="container">
    <h1>Search</h1>

    <form method="post">

        <label for="name">
            Item
            <input name="item" id="item" autofocus />
        </label>
        <button>Search</button>
        <button name="btnClear">Clear</button>
    </form>
    <br><?php print_r($json); 
    //$set = $dataSet;
    echo "<br><br>";
    // echo var_dump($_SESSION['cart']); 
    $test = [];

    $test[] = json_encode($json); //$_SESSION['cart'];
    echo sizeof($test); 
    foreach($test as $item) 
    { 
         echo 'x'; //$item["count"]." / ".$item["part_number"]." /  ".$item["description"]." /  ".$item["list"]."<br";  
    }
    
    ?>

    </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

