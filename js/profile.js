var idOfPost = 0;
var idOfDelete = 0;
$(document).ready(function(){
	getPostsU();
	
	$("#addPostText").on("click", function(){
		postComment();
	});

	$("#posts").on("click","#editPost", function(){
		idOfPost = $(this).parent().parent().find("#idPost").text();
		var text = $(this).parent().parent().find("#pText").text();
		$("#postText2").val(text);
	});

	$("#posts").on("click","#deletePost", function(){
		idOfDelete = $(this).parent().parent().find("#idPost").text();
		deletePost();
	});

	$("#updatePostText").on("click", function(){
		editComment();
	});
});

function postComment(){
	if($("#postText").val()==null || $("#postText").val()=='') {
		$("#postEmpty").text("Please post comment");
	}
	else {
		var jsonObject = {
            "comment" : $("#postText").val(),
            "action" : "POST"
        };
        console.log($("#postText").val());

		$.ajax({
            type: "POST",
            url: "data/applicationLayer.php",
            data : jsonObject,
            dataType : "json",
            ContentType : "application/json",
            success: function(jsonData) {
                alert("Comment posted succesfully"); 
	            $(".modal #closeAdd").click();
                $("#postEmpty").text("");
                $("#postText").val("");
                getPostsU();
            },
            error: function(errorMsg){
                alert(errorMsg.statusText);
                console.log(errorMsg);
            }
        });
	}
}

function deletePost(){
	var jsonObject = {
        "action" : "DELETEP",
        "idPost" : idOfDelete
    };
	$.ajax({
        type: "POST",
        url: "data/applicationLayer.php",
        data : jsonObject,
        dataType : "json",
        ContentType : "application/json",
        success: function(jsonData) {
            alert("Comment deleted succesfully"); 
            getPostsU();
        },
        error: function(errorMsg){
            alert(errorMsg.statusText);
            //console.log(errorMsg);
        }
    });
}

function editComment(){

	if($("#postText2").val()==null || $("#postText2").val()=='') {
		$("#postEmpty").text("Please post comment");
	}
	else {
		var jsonObject = {
            "comment" : $("#postText2").val(),
            "action" : "EDIT",
            "idPost" : idOfPost
        };

		$.ajax({
            type: "POST",
            url: "data/applicationLayer.php",
            data : jsonObject,
            dataType : "json",
            ContentType : "application/json",
            success: function(jsonData) {
                alert("Comment edited succesfully"); 
                $(".modal #closeUpdate").click();
                $("#postEmpty").text("");
                $("#postText2").val("");
                getPostsU();
            },
            error: function(errorMsg){
                alert(errorMsg.statusText);
            }
        });		
	}
}

function getPostsU(){
    var jsonToSend = {"action" : 'GETPOSTSU' };
    $("#posts").empty();
    $.ajax({
        url : "data/applicationLayer.php",
        type : "POST",
        dataType : "json",
        data : jsonToSend,
        ContentType : "application/json",
        success : function(dataReceived){                    
            for(var i=0;i<dataReceived.length;i++) {
                $("#posts").append("<div class='card centered card-post'>"+dataReceived[i].postDate+"<div id='idPost' style='display:none;'>"+dataReceived[i].postID+"</div><div class='card-body'></div><div post-content><p class='card-text' id='pText'>"+dataReceived[i].comment+"</p></div><h6 class='card-subtitle mb-2 text-muted writtenby'>Written by: "+dataReceived[i].firstname+" "+dataReceived[i].lastname+"</h6><div class='buttonGroup float-left'><button type='button' class='btn-edit btn btn-light' id='editPost' data-toggle='modal' data-target='#exampleModal2'>Edit post</button><button type='button' class='delete-post btn btn-light' id='deletePost'>Delete post</button></div></div></div>");
                console.log("Success in getting user posts");
            }
        },
        error : function(errorMessage){
            alert(errorMessage.statusText);
            //console.log(errorMessage);
        }
    });
}
