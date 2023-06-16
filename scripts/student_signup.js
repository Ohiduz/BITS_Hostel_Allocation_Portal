$(document).ready(function() {
    $('#studentsignupform').submit(function(event) {
      event.preventDefault(); // prevent default form submission behavior
  
      // get input values
      let id = $('#id').val();
      let name = $('#name').val();
      let gender = $("input[name='gender']:checked").val();
      let batch = $('#batch').val();
      let email = $('#email').val();
      let password = $('#password').val();
  
      // send input values to PHP file for validation
      $.ajax({
        url: './php/student_signup.php',
        type: 'POST',
        data: {
            id: id,
            name: name,
            gender: gender,
            batch: batch,
            email: email,
            password: password
        },
        success: function(response) {
          // take appropriate action based on feedback
          if(response == 'success') {
              // handle success response from PHP file
              $("#successMessage").text("Registered successfully! Please login!");
              $("#errorMessage").hide();
              $("#successMessage").show();
              setTimeout(function(){
                  window.location.href="./student_login.html"
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