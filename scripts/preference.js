$(function(){
    $.ajax({
        url: './php/preference.php',
        type: 'POST',
        data: {
            nameNid: true
        },
        dataType: "json",
        success: function(response){
            // console.log(response);
            $("#hnm").text(response.studentName);
            $("#hid").text(response.studentId);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
    $.ajax({
        url: './php/preference.php',
        type: 'POST',
        data: {
            hostels: true
        },
        dataType: "json",
        success: function(thehostels){
            // console.log(thehostels);
            $.each(thehostels, function(key, value) {
                $('#hostel').append('<option value="' + value.hostelName + '">' + value.hostelName + '</option>');
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
    $("#homebtn").click(function(){
        window.location.href = './student_homepage.html';
    });
    $("#lgout").click(function(){
        window.location.href = './index.html';
    });
    $('#prefform').submit(function(event) {
        event.preventDefault(); // prevent default form submission behavior
    
        // get input values
        let hostelName = $("#hostel").val();
        let self = $("#self").val();
        let wingie1 = $("#wingie1").val();
        let wingie2 = $("#wingie2").val();
        let wingie3 = $("#wingie3").val();
        let wingie4 = $("#wingie4").val();
        let wingie5 = $("#wingie5").val();
        let wingie6= $("#wingie6").val();
        let wingie7 = $("#wingie7").val();
        let wingie8 = $("#wingie8").val();
        let wingie9 = $("#wingie9").val();
        let wingie10 = $("#wingie10").val();
        let wingie11 = $("#wingie11").val();
    
        // send input values to PHP file for validation
        $.ajax({
            url: './php/preference.php',
            type: 'POST',
            data: {
                submitted: true,
                hostelName: hostelName,
                self: self,
                wingie1: wingie1,
                wingie2: wingie2,
                wingie3: wingie3,
                wingie4: wingie4,
                wingie5: wingie5,
                wingie6: wingie6,
                wingie7: wingie7,
                wingie8: wingie8,
                wingie9: wingie9,
                wingie10: wingie10,
                wingie11: wingie11,
            },
            dataType: "json",
            success: function(response) {
                // console.log(response);
                if(response.invalidHostel || response.invalidIds){
                    $("#errorMessage").text(response.message);
                    $("#successMessage").hide();
                    $("#errorMessage").show();
                }
                else if(response.preferenceAdded){
                    $("#successMessage").text("Your preference has been recorded!");
                    $("#errorMessage").hide();
                    $("#successMessage").show();
                    if(response.allocated){
                        $("#successMessage").append("\nYour wing has been alloted as follows");
                        $("#alctbl").show();
                        $("#alchnm").text(response.allocatedHostel);
                        $("#yourroom").text(response.studroom);
                        $("#w1").text(response.wingie1);
                        $("#w2").text(response.wingie2);
                        $("#w3").text(response.wingie3);
                        $("#w4").text(response.wingie4);
                        $("#w5").text(response.wingie5);
                        $("#w6").text(response.wingie6);
                        $("#w7").text(response.wingie7);
                        $("#w8").text(response.wingie8);
                        $("#w9").text(response.wingie9);
                        $("#w10").text(response.wingie10);
                        $("#w11").text(response.wingie11);
                    }
                    else{
                        $("#successMessage").append("\nWing could not be allocated");
                    }
                    $("#sbmtpref").hide();
                    $("#caution").hide();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + errorThrown);
            }
        });
    });
});