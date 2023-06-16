
function executePhp(){
    let clientData = {}
    $.post('./php/index.php', clientData, function(serverResponse){
        $("body").prepend(serverResponse);
    });
}

$(function(){
    executePhp();
    $("#thnxt").click();
    // $('#carouselExampleIndicators').carousel({
    //     interval: 4000 // Change interval time here (in milliseconds)
    // });
});