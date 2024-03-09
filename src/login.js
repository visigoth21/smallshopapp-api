var ApiUrl = "https://smallshopapp.com/api/v1/login.php";
//var ApiUrl = "http://localhost/api/v1/login.php";
var loginContainer = document.getElementById('login');

// login button ---------------------------------------------------------------
$("#login_button").on("click", function () {
  postLogin();
});
//-----------------------------------------------------------------------------
//Pull data from API search
function postLogin() {
  var passwordInput = $('#pwdValue').val();
  var userInput = $('#userValue').val();

  var sendData = {
    username: userInput,
    password: passwordInput
  };
  
  $.ajax({
    type: "POST",
    url: ApiUrl,
    data: JSON.stringify(sendData),
    contentType: "application/json",
    success: function(response) {
      if (!response["access_token"]) {
        alert("Password or User Name incorrect");
      } else {
        sessionStorage.setItem('access_token', response["access_token"]);
        sessionStorage.setItem('refresh_token', response["refresh_token"]);
        $('.login').children().remove()
      }

      console.log(response);
    },
    error: function(xhr, status, error) {
      // Handle the error here
      $('#errorValue').text("Password or User Name incorrect");
      console.error(error);
    }
  });
}
//-----------------------------------------------------------------------------
