$(function(){
    
    $.ajax({
        url: './php/addroom.php',
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

    $("#addroomform").submit(function(event){
        event.preventDefault();
        let rno = $("#roomno").val();
        let hid = $("#hostel").val();
        let wid = $("#wingid").val();
        $.ajax({
            url: './php/addroom.php',
            type: 'POST',
            data: {
                submitted: true,
                rno: rno,
                hid: hid,
                wid: wid
            },
            dataType: "text",
            success: function(response){
                if(response=="success"){
                    $("#successMessage").text("Room has been added!");
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