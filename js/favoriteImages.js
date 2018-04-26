$(document).ready(function() {

    getFavorites();

    $("#gallery").on("click","#deleteFavorite", function(){
        idOfFavorite = $(this).parent().parent().find("#idImage").text();
        deleteFavorite();
    });        

});

function getFavorites(){
	var jsonToSend = {"action" : 'GETFAVORITEIMAGES' };
    $("#gallery").empty();
        $.ajax({
                url : "data/applicationLayer.php",
                type : "POST",
                dataType : "json",
                data : jsonToSend,
                ContentType : "application/json",
                success : function(dataReceived){
                    for(var i=0;i<dataReceived.length;i++) {
                        $("#gallery").append("<div class='card-gal'><div id='idImage' style='display:none;'>"+dataReceived[i].imageID+"</div><img class='card-img-top' src='" + dataReceived[i].image + " 'alt='Card image cap'><div class='card-body-gal'><p class='card-text-bottom'>" + dataReceived[i].firstname + " " + dataReceived[i].lastname + "</p><p class='card-text-bottom'>" + dataReceived[i].postDate + "</p><input type='button' class='delete btn btn-light' value='Delete' id='deleteFavorite'/></div></div>");
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
            "action" : "DELETEFAVORITEIMAGE",
            "idImage" : idOfFavorite
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