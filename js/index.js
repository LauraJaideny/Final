 var idOfPost;
 $(document).ready(function() {

    var date_input = $('input[name="date"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    var options = {
        format: 'yyyy/mm/dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input.datepicker(options);

    getPosts();

    $("#posts").on("click", "#favoritePost", addFavorite);          
    $("#posts").on("click","#commentPost", function(){
        idOfPost = $(this).parent().parent().parent().find("#idPost").text();
    });  
    $("#commentPostText").on("click", function(){
        postReply();
    });    
    $("#filterBtn").on("click", function(){
        getPostsDate();
    });
    $("#clearBtn").on("click", function(){
        $("#dateText").val("");
        getPosts();
    });

});

 function addFavorite() {
    $(this).css("background-color", "#c48b80");
    var idPost = $(this).parent().parent().parent().find("#idPost").text();
    var jsonToSend = {"action" : 'ADDFAVORITE',
                      "idPost" : idPost };
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

 function getPosts(){
    var jsonToSend = {"action" : 'GETPOSTS' };
    $("#posts").empty();
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){
            for(var i=0; i<dataReceived.length; i++) {
                $("#posts").append("<div class='card centered card-post' id='postCard"+dataReceived[i].postID+"'>"+dataReceived[i].postDate+"<div id='idPost' style='display:none;'>"+dataReceived[i].postID+"</div><div class='card-body'><div post-content><p class='card-text'>"+dataReceived[i].comment+"</p></div><h6 class='card-subtitle mb-2 text-muted writtenby'>Written by: "+dataReceived[i].firstname+" "+dataReceived[i].lastname+"</h6><div class='buttonGroup float-left'><button type='button' class='btn btn-com btn-light' id='commentPost' data-toggle='modal' data-target='#exampleModal'>Comment</button><button type='button' id='favoritePost' class='btn btn-light fav-btn'>Favorite</button></div></div></div>");
                getReplies(dataReceived[i].postID);
            }
            console.log("Posts were received");
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
            //console.log("Error getting posts");
        }
    });
}

function getPostsDate(){
    $("#posts").empty();
    var jsonToSend = {"action" : 'GETPOSTSDATE',
                        "date" :  $("#dateText").val()};

    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){
            for(var i=0; i<dataReceived.length; i++) {
                $("#posts").append("<div class='card centered card-post' id='postCard"+dataReceived[i].postID+"'>"+dataReceived[i].postDate+"<div id='idPost' style='display:none;'>"+dataReceived[i].postID+"</div><div class='card-body'><div post-content><p class='card-text'>"+dataReceived[i].comment+"</p></div><h6 class='card-subtitle mb-2 text-muted writtenby'>Written by: "+dataReceived[i].firstname+" "+dataReceived[i].lastname+"</h6><div class='buttonGroup float-left'><button type='button' class='btn btn-light btn-com' id='commentPost' data-toggle='modal' data-target='#exampleModal'>Comment</button><button type='button' id='favoritePost' class='btn btn-light fav-btn'>Favorite</button></div></div></div>");
                getReplies(dataReceived[i].postID);
            }
            console.log("Posts in the date defined were retrieved")
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
        }
    });
}

function getReplies(idPost) {
    var jsonToSend = {"action" : 'GETREPLIES',
                      "idPost" : idPost };
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){
            for(var i=0; i<dataReceived.length; i++) {
                $("#postCard"+idPost).append("<hr>");
                $("#postCard"+idPost).append("<div class='card-body reply'><p class='card-text'>"+dataReceived[i].reply+" - by "+dataReceived[i].firstname+" "+dataReceived[i].lastname+"</p>");
            }
            console.log("Success in getting replies");
        },
        error : function(errorMessage){
            //alert(errorMessage.statusText);
            console.log("No current replies");
        }
    });
}

function postReply() {
    if($("#postText").val()==null || $("#postText").val()=='') {
        $("#postEmpty").text("Please post your comment");
    }
    else {
        var jsonObject = {
            "reply" : $("#postText").val(),
            "action" : "ADDREPLY",
            "idPost" : idOfPost
        };
        $.ajax({
            type: "POST",
            url: "data/applicationLayer.php",
            data : jsonObject,
            dataType : "json",
            ContentType : "application/json",
            success: function(jsonData) {
                alert("Replied succesfully"); 
                $(".modal #closeComment").click();
                $("#postEmpty").text("");
                $("#postText").val("");
                getPosts();
            },
            error: function(errorMsg){
                alert(errorMsg.statusText);
            }
        });
    }
}
