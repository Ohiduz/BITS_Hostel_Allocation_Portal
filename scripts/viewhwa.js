$(function(){
    $.ajax({
        url: './php/viewhwa.php',
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

    $("#viewhwaform").submit(function(event){
        event.preventDefault();
        let hid = $("#hostel").val();
        $.ajax({
            url: './php/viewhwa.php',
            type: 'POST',
            data: {
                vldthst: true,
                hid: hid
            },
            dataType: "text",
            success: function(vldtres){
                if(vldtres=="success"){
                    $.ajax({
                        url: './php/viewhwa.php',
                        type: 'POST',
                        data: {
                            fillTable: true,
                            hid: hid
                        },
                        dataType: "json",
                        success: function(results){
                            $("#hdline").siblings().remove();
                            $("#errorMessage").hide();
                            $.each(results, function(index, record){
                                let html = '<tr><td>' + record.roomNo + '</td><td class="text-light">' 
                                    + record.studentId + '</td><td>' + record.name + '</td>';
                                $("#vhwatbl").append(html);
                            });
                            $("#vhwadiv").show(1000);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Error: " + errorThrown);
                        }
                    });
                }
                else{
                    $("#errorMessage").text(vldtres);
                    $("#successMessage").hide();
                    $("#vhwadiv").hide();
                    $("#errorMessage").show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + errorThrown);
            }
        });
    });

    $("#homebtn").click(function(){
        window.location.href = './admin_homepage.html';
    });
    $("#lgout").click(function(){
        window.location.href = './index.html';
    });
});