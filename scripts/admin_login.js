$(document).ready(function() {
    $('#adminloginform').submit(function(event) {
      event.preventDefault(); // prevent default form submission behavior
  
      // get input values
      let email = $('#email').val();
      let password = $('#password').val();
  
      // send input values to PHP file for validation
      $.ajax({
        url: './php/admin_login.php',
        type: 'POST',
        data: {
          email: email,
          password: password
        },
        success: function(response) {
          // handle success response from PHP file
            if(response == 'success') {
                // take appropriate action based on feedback
                $("#successMessage").text("Login successful!");
                $("#errorMessage").hide();
                $("#successMessage").show();
                setTimeout(function(){
                    window.location.href="./admin_homepage.html"
                }, 1500)
            } else {
                // handle validation error(s)
                $("#errorMessage").text(response);
                $("#successMessage").hide();
                $("#errorMessage").show();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log("Error: " + errorThrown);
        }
      });
    });
  });