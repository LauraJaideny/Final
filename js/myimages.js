var idOfImage = 0;
var idOfDelete = 0;
$(document).ready(function(){
	getImagesUser();

	$("#gallery").on("click","#deleteImage", function(){
		idOfDelete = $(this).parent().parent().find("#idImage").text();
		deleteImage();
	});

    $("#uploadimage").on("submit", function(e) {
        e.preventDefault();
        //uploadImage();
        var formData = new FormData(this);
        //formData.append('action', 'UPLOADIMAGE');
        //var jsonToSend = { "action" : 'UPLOADIMAGE'};
        $.ajax({
                //url: "data/applicationLayer.php",
                url: "data/imgapplication.php",
                type: "POST",             // Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData:false,        // To send DOMDocument or non processed data file it is set to false
                success: function(data) {
                    console.log("Image added");
                    if (data[2] == "s")
                        alert("Image added succesfully");
                    getImagesUser();
                },
                error: function(error_message) {
                    alert(error_message.statusText);
                }
            });
    });

});

function deleteImage(){
	var jsonObject = {
            "action" : "DELETEIMAGE",
            "idImage" : idOfDelete
    };
	$.ajax({
        type: "POST",
        url: "data/applicationLayer.php",
        data : jsonObject,
        dataType : "json",
        ContentType : "application/json",
        success: function(jsonData) {
            alert("Image deleted succesfully"); 
            getImagesUser();
        },
        error: function(errorMsg){
            alert(errorMsg.statusText);
        }
    });
}

function getImagesUser(){
    var jsonToSend = {"action" : 'GETIMAGESUSER' };
    $("#gallery").empty();
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){                 
            for(var i=0;i<dataReceived.length;i++) {
                $("#gallery").append("<div class='card-gal'><div id='idImage' style='display:none;'>"+dataReceived[i].imageID+"</div><img class='card-img-top' src='" + dataReceived[i].image + " 'alt='Card image cap'><div class='card-body-gal'><p class='card-text-bottom'>" + dataReceived[i].firstname + " " + dataReceived[i].lastname + "</p><p class='card-text-bottom'>" + dataReceived[i].postDate + "</p><input type='button' class='delete btn btn-light' value='Delete' id='deleteImage'/></div></div>");
                console.log("Success in receiving user images");
            }
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
        }
    });
}

