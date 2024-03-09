<?php
  $taxRate = ".07";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <title>Invoice</title>
    <script src="./src/jquery-3.6.4/jquery-3.6.4.min.js"></script>
    <style>
      .edit-btn { display: inline;
                    padding-top: 5px;
                    padding-right: 5px;
                    padding-bottom: 5px;
                    padding-left: 5px;}
      .delete-btn { display: inline; 
                    padding-top: 5px;
                    padding-right: 5px;
                    padding-bottom: 5px;
                    padding-left: 5px;}
    </style>
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
                    <a href="invoice.php" class="nav-link active">Invoice</a>
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
        <br>
      <div class="container box" id="sysContainer">
        <label for="inputValue">Search Price Lists </label>
        <input type="text" id="inputValue" title="Search for UPC or Part Number">
        <button id="search-btn">Add</button>
        <br>
        <div id="errorValue"></div>
        <br>
        <div class="container box"></div>
          <table id="item-table" class="table table-striped">
            <thead>
              <tr>
                <td colspan="9" id="choice"></td>
              </tr>
              <tr bgcolor="LightGray">
                <th class="col-xs-1"></th>
                <th hidden></th>
                <th style="width: 180px;">Part Number</th>
                <th style="width: 190px;">UPC</th>
                <th style="width: 450px;">Description</th>
                <th style="width: 140px;">List</th>
                <th style="width: 100px;">Quantity</th>
                <th>Total</th>
                <th style="width: 75px;"></th>
                <th>Taxable</th>
                <th hidden></th>
              </tr>
              <tr id="misc-input">
                <td></td>
                <td hidden><input type="text" id="misc-line-input" placeholder="Id"></td>
                <td><input type="text" id="misc-part-input" placeholder="Part number" class="form-control"></td>
                <td><input type="text" id="misc-upc-input" placeholder="UPC code" class="form-control"></td>
                <td><input type="text" id="misc-description-input" placeholder="Description" class="form-control"></td>
                <td><input type="number" id="misc-list-input" placeholder="0.00" class="form-control" onkeypress="return event.charCode >= 48" min="1"></td>
                <td><input type="number" id="misc-quantity-input" placeholder="1" class="form-control" onkeypress="return event.charCode >= 48" min="1"></td>
                <td></td>
                <td><div id="misc-add-btn" title="Add item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"/>
                </svg></div></td>
                <td><input type="checkbox" id="misc-taxable-input" value="1" checked></td>
                <td hidden><input type="text" id="misc-id-input" placeholder="0"></td>
              </tr>
              <tr bgcolor="LightGray">
                <th colspan="6"></th>
                <th id="top-total"></th>
                <th><button id="iPrint" onclick="printInvoice()">Print</button></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot bgcolor="LightGray">
              <tr>
                <td colspan="5" id="choice"></td> 
                <td>Items : </td>
                <td id="invoice-total">0.00</td>
                <td colspan="3"></td>
              </tr>
              <tr>
                <td colspan="5" id="choice"></td>
                <td id="invoice-taxRate" hidden><?php echo $taxRate; ?></td> 
                <td>Tax : </td>
                <td id="invoice-tax"></td>
                <td colspan="3"></td>
              </tr>
              <tr>
                <td colspan="5" id="choice"></td> 
                <td>Total : </td>
                <td id="invoice-grand-total"></td>
                <td colspan="3"></td>
              </tr>              
            </tfoot>
          </table>
        </div>
      </div>
    <script src="./src/invoiceTable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
