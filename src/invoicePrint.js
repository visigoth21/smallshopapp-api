
var items = [];
var taxRate = .07;
// Create our number formatter ------------------------------------------------
const formatter = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
  // These options are needed to round to whole numbers if that's what you want.
  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
  //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});
//alert(location.search);

var urlParams = new URLSearchParams(decodeURIComponent(location.search));
//var urlParams = new URLSearchParams(location.search);
var jsonStr = urlParams.get('PrintObj');
// Parse the JSON string into an array of objects
items = JSON.parse(jsonStr);
//alert(items);
//-----------------------------------------------------------------------------
// Render table rows
//function renderTable() {
  var localTotal = parseFloat(0.0);
  var taxableTotal = parseFloat(0.0);
  var incId = 0;
  //alert(items);
  $("#item-table tbody").empty();
  $.each(items, function (index, item) {
    incId++;
    var row = $("<tr>").appendTo("#item-table tbody");
    $("<td>").text(incId).appendTo(row);
    $("<td>").text(item.part_number).appendTo(row);
    $("<td>").text(item.upc).appendTo(row);
    $("<td>").text(item.description).appendTo(row);
    $("<td>").text(item.list).appendTo(row);
    $("<td>").text(item.quantity).appendTo(row);
    $("<td>").text(formatter.format(item.quantity * item.list)).appendTo(row); 
    localTotal = parseFloat(localTotal) + parseFloat(item.quantity * item.list);
    // alert(item.taxable);
    if (item.taxable == 1 ) {
      taxableTotal = parseFloat(taxableTotal) + parseFloat(item.quantity * item.list);
    }
    $("<td>").text(taxableDisp(item.taxable)).appendTo(row);
  });
  $("table #invoice-total").text(formatter.format(localTotal));
  $("table #top-total").text(formatter.format(localTotal)); 
  $("table #invoice-tax").text(formatter.format((taxRate * taxableTotal)));
  $("table #invoice-grand-total").text(formatter.format(((taxRate * taxableTotal) + localTotal)));
  $("#errorValue").text("");


function taxableDisp(sTaxable) {
  var taxStamp = "";
  if (sTaxable == 1) {
    taxStamp = "x";
  }
  return taxStamp;
}
//}
//-----------------------------------------------------------------------------
//renderTable();
//-----------------------------------------------------------------------------