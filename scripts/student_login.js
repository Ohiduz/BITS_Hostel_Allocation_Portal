$(document).ready(function() {
    $('#studentloginform').submit(function(event) {
      event.preventDefault(); // prevent default form submission behavior
  
      // get input values
      let email = $('#email').val();
      let password = $('#password').val();
  
      // send input values to PHP file for validation
      $.ajax({
        url: './php/student_login.php',
        type: 'POST',
        data: {
          email: email,
          password: password
        },
        success: function(response) {
          // take appropriate action based on feedback
          if(response == 'success') {
              // handle success response from PHP file
              $("#successMessage").text("Login successful!");
              $("#errorMessage").hide();
              $("#successMessage").show();
              setTimeout(function(){
                  window.location.href="./student_homepage.html"
              }, 1500)
          } else {
              // handle validation error(s)
              $("#errorMessage").text(response);
              $("#errorMessage").show();
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log("Error: " + errorThrown);
        }
      });
    });
  });