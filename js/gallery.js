$(document).ready(function() {

    var date_input=$('input[name="date"]'); //our date input has the name "date"
    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    var options={
        format: 'yyyy/mm/dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input.datepicker(options);

    getGallery();

    $("#gallery").on("click", "#favorite", addFavorite); 
    $("#filterBtn").on("click", function(){
        getGalleryDate();
    });
    $("#clearBtn").on("click", function(){
        $("#dateText").val("");
        getGallery();
    });
          
});

function getGallery(){
    var jsonToSend = {"action" : 'GETGALLERY' };
    $("#gallery").empty();
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){
            $("#gallery").empty();
            for(var i = 0; i<dataReceived.length; i++) {	
                $("#gallery").append("<div class='card-gal'><div id='idImage' style='display:none;'>"+dataReceived[i].imageID+"</div><img class='card-img-top' src='" + dataReceived[i].image + " 'alt='Card image cap'><div class='card-body-gal'><p class='card-text-bottom'>" + dataReceived[i].firstname + " " + dataReceived[i].lastname + "</p><p class='card-text-bottom'>" + dataReceived[i].postDate + "</p><input type='button' class='favorite btn btn-light' value='Favorite' id='favorite'/></div></div>");
                console.log("Success in getting images");
            }
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
        }
    });
}

function getGalleryDate(){
    var jsonToSend = {"action" : 'GETGALLERYDATE',
    "date" :  $("#dateText").val()};
    $("#gallery").empty();
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){    
            for(var i = 0; i<dataReceived.length; i++) {   
                $("#gallery").append("<div class='card-gal'><div id='idImage' style='display:none;'>"+dataReceived[i].imageID+"</div><img class='card-img-top' src='" + dataReceived[i].image + " 'alt='Card image cap'><div class='card-body-gal'><p class='card-text-bottom'>" + dataReceived[i].firstname + " " + dataReceived[i].lastname + "</p><p class='card-text-bottom'>" + dataReceived[i].postDate + "</p><input type='button' class='favorite' value='Favorite' id='favorite'/></div></div>");
                console.log("Success in receiving images");
            }
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
        }
    });
}


function addFavorite(){
    $(this).css("background-color", "#c48b80");
    var idImage = $(this).parent().parent().find("#idImage").text();
    var jsonToSend = {"action" : 'ADDFAVORITEIMAGE',
                      "idImage" : idImage };
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data: jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){
            alert(dataReceived.success);
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
            //console.log("Fail in adding favorite");
        }
    })
 }
