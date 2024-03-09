<?php

$companyName = "Hogarth's Lawn Mower";
$companyAddress = "2800 Norwich Street";
$companyCity = "Brunswick";
$companyState = "Ga";
$companyZip = "31520";
$companyPhone = "912-264-3725";



?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="company">
      <table>
        <thead>
          <tr>
            <th colspan="3"><?php echo $companyName; ?></th>
          </tr>
          <tr>
            <th colspan="3"><?php echo $companyAddress; ?></th>
          </tr>
          <tr>
            <th><?php echo $companyCity; ?></th>
            <th><?php echo $companyState . ","; ?></th>
            <th><?php echo $companyZip; ?></th>
          </tr>
          <tr>
            <th colspan="3"><?php echo $companyPhone ; ?></th>
          </tr>
          <tr>
            <th colspan="3">&nbsp;</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
          <table id="item-table" class="table table-striped" width="670">
            <thead>
              <tr>
                <th class="col-xs-1"></th>
                <th width="70">Part Number</th>
                <th width="70">UPC</th>
                <th>Description</th>
                <th width="60">List</th>
                <th width="70">Quantity</th>
                <th width="70">Total</th>
                <th width="40">Taxable</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5" id="choice"></td> 
                <td>Items : </td>
                <td id="invoice-total">0.00</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="5" id="choice"></td>
                <td id="invoice-taxRate" hidden><?php echo $taxRate; ?></td> 
                <td>Tax : </td>
                <td id="invoice-tax"></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="5" id="choice"></td> 
                <td>Total : </td>
                <td id="invoice-grand-total"></td>
                <td></td>
              </tr>              
            </tfoot>
          </table>
        </div>
    </div>
      <script src="./src/invoicePrint.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
