$(function(){

    $.ajax({
        url: './php/updateroom.php',
        type: 'POST',
        data: {
            getCount: true
        },
        dataType: "json",
        success: function(response){
            // console.log(response);
            $("#stdcount").append(response.std);
            $("#hstcount").append(response.hst);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });

    $("#homebtn").click(function(){
        window.location.href = './admin_homepage.html';
    });
    $("#lgout").click(function(){
        window.location.href = './index.html';
    });

    $("#updateroomform").submit(function(event){
        event.preventDefault();
        let rno = $("#roomno").val();
        let hid = $("#hostel").val();
        let stat = $("#status").val();
        $.ajax({
            url: './php/updateroom.php',
            type: 'POST',
            data: {
                submitted: true,
                rno: rno,
                hid: hid,
                stat: stat
            },
            dataType: "text",
            success: function(response){
                if(response=="success"){
                    $("#successMessage").text("Room status has been updated!");
                    $("#errorMessage").hide();
                    $("#successMessage").show();
                }
                else{
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