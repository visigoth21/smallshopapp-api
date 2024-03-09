var items = [];
var lineItem = [];
var lineChoices = [];
// var searchApiUrl = "https://smallshopapp.com/api/v1/search/";
var searchApiUrl = "http://localhost/api/v1/search/";
// Create our number formatter.
const formatter = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
  // These options are needed to round to whole numbers if that's what you want.
  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
  //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

var DeleteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
<path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
</svg>`;

var EditIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
<path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>`;

var AddIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"/>
</svg>`;

var SaveFillIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save-fill" viewBox="0 0 16 16">
  <path d="M8.5 1.5A1.5 1.5 0 0 1 10 0h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h6c-.314.418-.5.937-.5 1.5v7.793L4.854 6.646a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l3.5-3.5a.5.5 0 0 0-.708-.708L8.5 9.293V1.5z"/>
</svg>`;

var lineIcons = '<div class="edit-btn" title="Edit">' + EditIcon + '</div><div class="delete-btn" title="Delete">' + DeleteIcon + '</div>';

var SaveEditlineIcons = '<div class="save-edit-btn" id="save-edit-btn" title="Edit">' + AddIcon + '</div>';

//add tool tips to html elements
$(function () {
  $(document).tooltip();
});

//-----------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
  var container = document.getElementById('sysContainer');
  var mainInput = document.getElementById('inputValue');

  // Add event listener to the container element
  container.addEventListener('keydown', function(event) {
    var target = event.target;
    
    // Check if the target is an input element and the pressed key is Enter (key code 13)
    if (target.tagName === 'INPUT' && event.keyCode === 13) {
      // Call a function based on the focused input box
      if (target === mainInput) {        
        document.getElementById("search-btn").click();
      } else {
        $("#save-edit-btn").click();
      }
    }
  });
});

// Search price list and Add item
$("#search-btn").on("click", function () {
  getItemFromSearch();
});

// Add item
$("#add-btn").on("click", function () {
  var line = items.length + 1;
  var quantity = $("#quantity-input").val();
  var id = "0";
  var part_number = $("#part-input").val();
  var upc = $("#upc-input").val();
  var description = $("#description-input").val();
  var list = parseFloat($("#list-input").val());
  var total = parseFloat($("#quantity-input").val() * $("#list-input").val());

  // Add validation to ensure all fields are filled out
  if (!description || !list || !quantity) {
    alert("Please fill out all fields.");
    return;
  }

  // Add item to items array
  items.push({
    line: line,
    quantity: quantity,
    id: id,
    part_number: part_number,
    upc: upc,
    description: description,
    list: list,
  });

  // Clear input fields
  clearInput();
  renderTable();
});

// Edit item
$("#item-table").on("click", ".edit-btn", function () {
  var row = $(this).closest("tr");
  var TDPartID = row.find("td:eq(9)").text();
  // Select all <td> elements in the specified column within the table body
  var $columnCells = $("#item-table tbody td:nth-child(9)");
  // Clear the contents of the selected cells
  $columnCells.empty();

  if (TDPartID == 0) {
    row.find("td:eq(2)").html(`<input type="text" id="part-num-row-input" value=` + row.find("td:eq(2)").text() + `>`);
    row.find("td:eq(3)").html(`<input type="text" id="upc-row-input" value=` + row.find("td:eq(3)").text() + `>`);
    row.find("td:eq(4)").html(`<input type="text" id="description-row-input" value=` + row.find("td:eq(4)").text() + `>`);
  }
  row.find("td:eq(5)").html(`<input type="number" id="list-row-input" value=` + row.find("td:eq(5)").text() + `>`);
  row.find("td:eq(6)").html(`<input type="number" id="quantity-row-input" value=` + row.find("td:eq(6)").text() + `>`);
  row.find("td:eq(8)").html(SaveEditlineIcons);

});

// Edit item
$("#item-table").on("click", ".edit-line", function () {
  var row = $(this).closest("tr");
  //var TDlineNum = row.find("td:eq(1)").text();
  var TDPartID = row.find("td:eq(9)").text();
  if (TDPartID == 0) {
    row.find("td:eq(2)").html(`<input type="text" id="part-num-row-input" value=` + row.find("td:eq(2)").text() + `>`);
    row.find("td:eq(3)").html(`<input type="text" id="upc-row-input" value=` + row.find("td:eq(3)").text() + `>`);
    row.find("td:eq(4)").html(`<input type="text" id="description-row-input" value=` + row.find("td:eq(4)").text() + `>`);
  }
  row.find("td:eq(5)").html(`<input type="number" id="list-row-input" value=` + row.find("td:eq(5)").text() + `>`);
  row.find("td:eq(6)").html(`<input type="number" id="quantity-row-input" value=` + row.find("td:eq(6)").text() + `>`);
  row.find("td:eq(8)").html(SaveEditlineIcons);

});

// Seve Edits item
$("#item-table").on("click", ".save-edit-btn", function () {
  var row = $(this).closest("tr");
  var TDlineNum = row.find("td:eq(1)").text();
  lineUpdateById(TDlineNum);
  renderTable();
});

// Delete item
$("#item-table").on("click", ".delete-btn", function () {
  var row = $(this).closest("tr");
  var TDlineNum = row.find("td:eq(1)").text();
  deleteFromArray(TDlineNum);
  renderTable();
});

function setChoiceData(dataLine) {
  var docItems = "";
  lineChoices = dataLine;
  let i = 0;

  $.each(dataLine, function (index, cLine) {
    docItems = docItems + cLine.description + ` - ` + formatter.format(cLine.list) + 
      '&nbsp <img src="../i/save-fill.svg" alt="Save" title="Choose" onclick="onChoiceID(' + i + ');"><br>';
    i = ++i;
  });

  $("#choice").html(docItems);
  $("#inputValue").val("");
}

function onChoiceID(id) {
  if (!updateQuantityById(lineChoices[id].id)) {
    items.push({
      line: items.length + 1,
      quantity: 1,
      id: lineChoices[id].id,
      part_number: lineChoices[id].part_number,
      upc: lineChoices[id].upc,
      description: lineChoices[id].description,
      list: lineChoices[id].list,
    });
  }
  renderTable();
  $("#choice").html("");
  searchInput.focus();
}


// Add item to array
function deleteFromArray(lineNum) {
  let index = items.findIndex((element) => element.line == lineNum);
  if (index !== -1) {
    items.splice(index, 1);
  }
}

// Add item to array
function addToArray(dataPush) {
  $.each(dataPush, function (index, item) {
    if (!updateQuantityById(item.id)) {
      items.push({
        line: items.length + 1,
        quantity: 1,
        id: item.id,
        part_number: item.part_number,
        upc: item.upc,
        description: item.description,
        list: item.list,
      });
    }
  });
}

// Function to update quantity based on id value
function lineUpdateById(lineEditId) {
  // Loop through the array to find the item with matching id
  for (var i = 0; i < items.length; i++) {
    if (items[i].line == lineEditId) {
      // Update the quantity value
      items[i].quantity = $("#quantity-row-input").val();
      items[i].list = $("#list-row-input").val();
      if (items[i].id == 0) {
        items[i].part_number = $("#part-num-row-input").val();
        items[i].upc = $("#upc-row-input").val();      
        items[i].description = $("#description-row-input").val();
      }
      return true;
    }
  }
  // If id not found, return false
  return false;
}

// Function to update quantity based on id value
function updateById(id) {
  var quantity = $("#quantity-input").val();
  var id = $("#1d-input").val();
  var part_number = $("#part-input").val();
  var upc = $("#upc-input").val();
  var description = $("#description-input").val();
  var list = parseFloat($("#list-input").val());
  // Loop through the array to find the item with matching id
  for (var i = 0; i < items.length; i++) {
    if (items[i].id === id) {
      // Update the quantity value
      items[i].quantity = ++items[i].quantity;
      return true;
    }
  }
  // If id not found, return false
  return false;
}

// Function to update quantity based on id value
function updateQuantityById(id) {
  // Loop through the array to find the item with matching id
  for (var i = 0; i < items.length; i++) {
    if (items[i].id === id) {
      // Update the quantity value
      items[i].quantity = ++items[i].quantity;
      return true;
    }
  }
  // If id not found, return false
  return false;
}

// Clear input widget
function clearInput() {
  $("#quantity-input").val(0);
  $("#part-input").val("");
  $("#upc-input").val("");
  $("#description-input").val("");
  $("#list-input").val("0.00");
  $("#total-input").val("0.00");
  $("#id-input").val(0);
}

//Pull data from API search
function getItemFromSearch() {
  var inputValue = $("#inputValue").val();
  // var apiUrl = searchApiUrl + inputValue;
  var apiUrl = searchApiUrl + inputValue;
  $.ajax({
    url: apiUrl,
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (Number(data.data.length) > Number(1)) {
        setChoiceData(data.data);
      } else {
        addToArray(data.data);
        clearInput();
        renderTable();
        $("#inputValue").val("");
        searchInput.focus();
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
      $("#testValue").val("error");
      searchInput.focus();
    },
  });
}

// Render table rows
function renderTable() {
  var localTotal = parseFloat(0.0);
  var incId = 0;
  $("#item-table tbody").empty();
  $.each(items, function (index, item) {
    incId++;
    var row = $("<tr>").appendTo("#item-table tbody");
    $("<td>").text(incId).appendTo(row);
    $("<td hidden>").text(item.line).appendTo(row);
    $("<td>").text(item.part_number).appendTo(row);
    $("<td>").text(item.upc).appendTo(row);
    $("<td>").text(item.description).appendTo(row);
    $("<td>").text(item.list).appendTo(row);
    $("<td class=`edit-line`>").text(item.quantity).appendTo(row);
    $("<td>").text(formatter.format(item.quantity * item.list)).appendTo(row);
    $("<td>").html(lineIcons).appendTo(row);    
    $("<td hidden>").text(item.id).appendTo(row);
    localTotal = parseFloat(localTotal) + parseFloat(item.quantity * item.list);
  });

  $("table #invoice-total").text(formatter.format(localTotal));
  $("table #top-total").text(formatter.format(localTotal));
}

renderTable();
