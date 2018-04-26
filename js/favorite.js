$(document).ready(function() {

    getFavorites();

    $("#posts").on("click","#deleteFavorite", function(){
        idOfFavorite = $(this).parent().parent().find("#idPost").text();
        deleteFavorite();
    });        

});

function getFavorites(){
	var jsonToSend = {"action" : 'GETFAVORITES' };
    $("#posts").empty();
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){
            for(var i=0; i<dataReceived.length; i++) {
                $("#posts").append("<div class='card centered card-post'>"+dataReceived[i].postDate+"<div id='idPost' style='display:none;'>"+dataReceived[i].postID+"</div><div class='card-body'></div><div post-content><p class='card-text'>"+dataReceived[i].comment+"</p></div><h6 class='card-subtitle mb-2 text-muted writtenby'>Written by: "+dataReceived[i].firstname+" "+dataReceived[i].lastname+"</h6><div class='buttonGroup float-left'><button type='button' class='delete-post btn btn-light' id='deleteFavorite'>Delete from favorites</button></div></div></div>");
                console.log("Success in getting favorites");
            }
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
        }
    });
}

function deleteFavorite(){
    var jsonObject = {
        "action" : "DELETEFAVORITE",
        "idPost" : idOfFavorite
    };
    $.ajax({
        type: "POST",
        url: "data/applicationLayer.php",
        data : jsonObject,
        dataType : "json",
        ContentType : "application/json",
        success: function(jsonData) {
            alert("Favorite deleted succesfully"); 
            getFavorites();
        },
        error: function(errorMsg){
            alert(errorMsg.statusText);
        }
    });
}