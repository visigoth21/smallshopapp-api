$('#myTable').on('click', '#add-row', function() {
  var newRow = $('<tr>');
  var itemCell = $('<td><input type="text" class="item-input"></td>');
  var quantityCell = $('<td><input type="number" class="quantity-input"></td>');
  var priceCell = $('<td><input type="number" class="price-input"></td>');
  var totalCell = $('<td><span class="total"></span></td>');
  var deleteCell = $('<td><button class="delete-row">Delete</button></td>');
  newRow.append(itemCell, quantityCell, priceCell, totalCell, deleteCell);
  $('#myTable tbody').append(newRow);
});

$('#myTable').on('input', '.quantity-input, .price-input', function() {
  var row = $(this).closest('tr');
  var quantity = row.find('.quantity-input').val();
  var price = row.find('.price-input').val();
  var total = quantity * price;
  row.find('.total').text(total);
});

$('#myTable').on('click', '.delete-row', function() {
  $(this).closest('tr').remove();
});

// var inputValue = $("#inputValue").val();
// // var apiUrl = "https://smallshopapp.com/api/v1/search/" + inputValue;
// var apiUrl = "http://localhost/api/v1/search/" + inputValue;

// $.ajax({
  
//   url: apiUrl,
//   method: 'GET',
//   success: function(data) {
//     $.each(data.data, function(index, item) {
//       var newRow = $('<tr>');
//       var itemCell = $('<td><input type="text" class="item-input" value="' + item.name + '"></td>');
//       var quantityCell = $('<td><input type="number" class="quantity-input" value="' + item.quantity + '"></td>');
//       var priceCell = $('<td><input type="number" class="price-input" value="' + item.price + '"></td>');
//       var totalCell = $('<td><span class="total">' + (item.quantity * item.price) + '</span></td>');
//       var deleteCell = $('<td><button class="delete-row">Delete</button></td>');
//       newRow.append(itemCell, quantityCell, priceCell, totalCell, deleteCell);
//       $('#myTable tbody').append(newRow);
//     });
//   },
//   error: function(jqXHR, textStatus, errorThrown) {
//     console.log('Error: ' + errorThrown);
//   }
// });


// Handle quantity and price changes
$('#myTable').on('input', '.quantity-input, .price-input', function() {
  var row = $(this).closest('tr');
  var quantity = row.find('.quantity-input').val();
  var price = row.find('.price-input').val();
  var total = quantity * price;
  row.find('.total').text(total);
});

// Handle item name changes
$('#myTable').on('input', '.item-input', function() {
  var row = $(this).closest('tr');
  var item = {
    name: row.find('.item-input').val(),
    quantity: row.find('.quantity-input').val(),
    price: row.find('.price-input').val()
  };
  // Update item in API
});

// Handle row deletion
$('#myTable').on('click', '.delete-row', function() {
  var row = $(this).closest('tr');
  // Delete item from API
  row.remove();
});

// var input = document.getElementById("inputValue");

// // Execute a function when the user presses a key on the keyboard
// input.addEventListener("keypress", function(event) {
//   // If the user presses the "Enter" key on the keyboard
//   if (event.key === "Enter") {
//     // Cancel the default action, if needed
//     event.preventDefault();
//     // Trigger the button element with a click
//     document.getElementById("loadTableButton").click();
//   }
// });