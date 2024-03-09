let localTable;


$(document).ready(function () {
  $("#loadTableButton").click(function () {
    var inputValue = $("#inputValue").val();
    // var apiUrl = "https://smallshopapp.com/api/v1/search/" + inputValue;
    var apiUrl = "http://localhost/api/v1/search/" + inputValue;
    $.ajax({
      url: apiUrl,
      type: "GET",
      dataType: "json",
      success: function (data) {
        let html = populateTable(data.data);
        
        var tableBody = $("#myTable tbody");
        tableBody.empty();
        $("#data-table tbody").html(html);
        $('#inputValue').val('');
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  });
});

function populateTable(data) {

  var html = "";
  $.each(data, function (index, item) {
    html += "<tr>";
    html += "<td>1</td>";
    html += "<td>" + item.part_number + "</td>";
    html += "<td>" + item.description + "</td>";
    html += "<td>" + item.list + "</td>";
    html += "<td>" + item.id + "</td>";
    html += "</tr>";
  });

  return html;
  
}

var input = document.getElementById("inputValue");

// Execute a function when the user presses a key on the keyboard
input.addEventListener("keypress", function(event) {
  // If the user presses the "Enter" key on the keyboard
  if (event.key === "Enter") {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("loadTableButton").click();
  }
});