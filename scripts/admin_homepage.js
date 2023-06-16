$(function(){
    $.ajax({
        url: './php/admin_homepage.php',
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

    $.ajax({
        url: './php/admin_homepage.php',
        type: 'POST',
        data: {
            fillTableH: true
        },
        dataType: "json",
        success: function(resultsH){
            // console.log(resultsH);
            $.each(resultsH, function(indexH, recordH) {
                let htmlH = '<tr><td>' + recordH.hostelId + '</td><td class="text-light">' + recordH.hostelName + '</td><td>' + recordH.gender + '</td></tr>';
                $('#vhsttbl').append(htmlH);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
    $.ajax({
        url: './php/admin_homepage.php',
        type: 'POST',
        data: {
            fillTableA: true
        },
        dataType: "json",
        success: function(resultsA){
            $.each(resultsA, function(indexA, recordA){
                let htmlA = '<tr><td>' + recordA.studentId + '</td><td class="text-light">' + recordA.hostelName + '</td><td>' + recordA.roomNo + '</td></tr>';
                $("#valctbl").append(htmlA);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
    $.ajax({
        url: './php/admin_homepage.php',
        type: 'POST',
        data: {
            fillTableR: true
        },
        dataType: "json",
        success: function(resultsR){
            $.each(resultsR, function(indexR, recordR){
                let htmlR = '<tr><td>' + recordR.roomNo + '</td><td class="text-light">' + recordR.hostelName + '</td><td>' + recordR.wingId + '</td></tr>';
                $("#vfrtbl").append(htmlR);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });
    $.ajax({
        url: './php/admin_homepage.php',
        type: 'POST',
        data: {
            fillTableW: true
        },
        dataType: "json",
        success: function(resultsW){
            $.each(resultsW, function(indexW, recordW){
                let htmlW = '<tr><td>' + recordW.hostelName + '</td><td class="text-light">' 
                    + recordW.wingId + '</td><td>' + recordW.roomNo + '</td><td class="text-light">' + recordW.studentId + '</td><td>'
                    + recordW.name + '</td></tr>';
                $("#vwtbl").append(htmlW);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + errorThrown);
        }
    });

    $("#vw").click(function(){
        $("#vwdiv").siblings().hide();
        $("#vwdiv").slideToggle(1000);
    });

    $("#vfr").click(function(){
        $("#vfrdiv").siblings().hide();
        $("#vfrdiv").slideToggle(1000);
    });

    $("#vhst").click(function(){
        $("#vhstdiv").siblings().hide();
        $("#vhstdiv").slideToggle(1000);
    });

    $("#valc").click(function(){
        $("#valcdiv").siblings().hide();
        $("#valcdiv").slideToggle(1000);
    });

    $("#addroom").click(function(){
        window.location.href = './addroom.html';
    });

    $("#removeroom").click(function(){
        window.location.href = './removeroom.html';
    });

    $("#updateroom").click(function(){
        window.location.href = './updateroom.html';
    });
    $("#viewhwa").click(function(){
        window.location.href = './viewhwa.html';
    });
    
    $("#lgout").click(function(){
        window.location.href = './index.html';
    });
});