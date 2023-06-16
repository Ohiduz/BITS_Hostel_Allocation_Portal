$(function(){
    let sid="", sname="", setPreference=false, preferredHostel="", allocated=false, allocatedHostel="";
    $.ajax({
        url: './php/student_homepage.php',
        type: 'POST',
        data: {
            studentData: true
        },
        dataType: "json",
        success: function(response) {
            sid = response.id;
            sname = response.name;
            setPreference = response.setPreference;
            preferredHostel = response.preferredHostel;
            allocated = response.allocated;
            allocatedHostel = response.allocatedHostel;
            $("#hnm").text(sname);
            $("#hid").text(sid);
            if(!setPreference){
                $("#sp").text("Not selected");
                $("#thetxt").show();
                $("#prefbtn").show();
            }
            else{
                $("#sp").text(preferredHostel);
            }
            if(!allocated){
                $("#allc").text("Not allocated");
            }
            else{
                $("#allc").text(allocatedHostel);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
    $("#lgout").click(function(){
        window.location.href = "./index.html";
    });
    $("#prefbtn").click(function(){
        window.location.href = "./preference.html";
    });
});