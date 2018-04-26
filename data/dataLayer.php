<?php
	ini_set('display_errors', 1); 
	ini_set('log_errors', 1); 
	error_reporting(E_ALL);

	function connectionToDB() {

		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "LauraAdrian";

		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			return null;
		}
		else {
			return $conn;
		}
	}

	function dbLogin($uName) {
		$connection = connectionToDB();

		if($connection != null) {
			$sql = "SELECT * 
				FROM Users 
				WHERE username = '$uName'";
	
			$resultDB = $connection->query($sql);

			if ($resultDB->num_rows > 0) {
				
				while ($row = $resultDB->fetch_assoc()) {
					$response = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "uName"=>$row["username"], "uPass"=>$row["passwrd"], "status" => "SUCCESS");
				}
				return $response;
			}
			else {
				return array("status" => "401");
			}
		}
		else {
			return array("status" => "500");
		}
	}

	function dbRegister($uName, $uPass, $uL, $uF, $email) {
		$connection = connectionToDB();

		$sql = "SELECT * 
				FROM Users 
				WHERE username = '$uName'";
	
		$result = $connection->query($sql);

		if ($result->num_rows > 0) {
			return array("status" => "403");
		}
		else {
			$sql2 = "INSERT INTO users (fName, lName, username, passwrd, email) VALUES ('$uF', '$uL', '$uName', '$uPass', '$email')";

			if(mysqli_query($connection, $sql2)) {
					$response = array("firstname"=>$uF, "lastname"=>$uL, "uName"=>$uName,"status" => "SUCCESS");
					return $response;
				}
			else {
				return array("status" => "500");
			}	
		}
	}

	function dbGetPosts(){
		$connection = connectionToDB();
		$sql = "SELECT * FROM users JOIN posts ON users.username = posts.username";
	
		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "comment"=>$row["comment"], "postDate"=>$row["postDate"], "postID" =>$row["postID"]);
				array_push($response, $currentRow);
			}
			return $response;
		}
		else {
			return array("status" => "406");
		}
	}

	function dbGetPostsDate($date){
		$connection = connectionToDB();
		$sql = "SELECT * FROM users JOIN posts ON users.username = posts.username WHERE posts.postDate = '$date'";
	
		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "comment"=>$row["comment"], "postDate"=>$row["postDate"], "postID" =>$row["postID"]);
				array_push($response, $currentRow);
			}	
			return $response;
		}
		else {
			return array("status" => "407");
		}
	}

	function dbGetReplies($idPost) {
		$connection = connectionToDB();
		$sql = "SELECT * FROM replies JOIN users ON replies.username = users.username AND replies.postID = '$idPost' ORDER BY replies.replyID";

		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "reply" => $row["reply"]);
				array_push($response, $currentRow);
			}
			return $response;
		}
		else {
			return array("status" => "408");
		}
	}

	function dbGetGallery(){
		$connection = connectionToDB();
		$sql = "SELECT * FROM users JOIN images ON users.username = images.username";
	
		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "postDate"=>$row["postDate"], "image" => $row["image"], "imageID"=>$row["imageId"]);
				array_push($response, $currentRow);
			}
			return $response;
		}
		else {
			return array("status" => "410");
		}
	}

	function dbGetGalleryDate($date) {
		$connection = connectionToDB();
		$sql = "SELECT * FROM users JOIN images ON users.username = images.username WHERE images.postDate = '$date'";
	
		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "postDate"=>$row["postDate"], "image" => $row["image"], "imageID"=>$row["imageId"]);
				array_push($response, $currentRow);
			}		
			return $response;
		}
		else {
			return array("status" => "411");
		}
	}

	function dbGetPostsU($uName) {
		$connection = connectionToDB();
		$sql = "SELECT * FROM users JOIN posts ON users.username = posts.username AND users.username = '$uName'";
	
		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "comment"=>$row["comment"], "postDate"=>$row["postDate"], "postID" =>$row["postID"]);
				array_push($response, $currentRow);
			}
			return $response;
		}
		else {
			return array("status" => "409");
		}
	}

	function dbPost($comment, $uName) {
		$connection = connectionToDB();

		$sql2 = "INSERT INTO Posts (comment, username, postDATE) VALUES ('$comment', '$uName', CURRENT_DATE())";

		if(mysqli_query($connection, $sql2)) {
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "412");
		}
	}

	function dbAddReply($reply, $idPost, $uName) {
		$connection = connectionToDB();

		$sql2 = "INSERT INTO Replies (reply, username, postID) VALUES ('$reply', '$uName', '$idPost')";

		if(mysqli_query($connection, $sql2)) {
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "413");
		}
	}

	function dbUpdate($comment, $idPost) {
		$connection = connectionToDB();

		$sql2 = "UPDATE posts SET comment = '$comment' WHERE posts.postID = '$idPost'";

		if(mysqli_query($connection, $sql2)) {
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "414");
		}
	}


	function dbAddFavorite($uName, $idPost) {
		$connection = connectionToDB();

		$sql2 = "SELECT * FROM Favorites WHERE postID = '$idPost' AND username = '$uName'";

		$result = $connection->query($sql2);

		if ($result->num_rows > 0) {
			return array("status" => "415");
		}

		else {
			$sql = "INSERT INTO Favorites (username, postID) VALUES ('$uName', '$idPost')";

			if(mysqli_query($connection, $sql)) {
				$response = array("status" => "SUCCESS");
				return $response;
			}
			else {
				return array("status" => "416");
			}
		}
	}

	function dbGetFavorites($uName) {
		$connection = connectionToDB();

		$sql = "SELECT * FROM favorites f join posts p ON f.postID = p.postID join users u ON p.username = u.username WHERE f.username = '$uName'";
	
		$result = $connection->query($sql);

		$response = array();

		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "comment"=>$row["comment"], "postDate"=>$row["postDate"], "postID" =>$row["postID"]);
				array_push($response, $currentRow);
			}	
			return $response;
		}
		else {
			return array("status" => "417");
		}
	}

	function dbDeleteFavorite($idPost) {
		$connection = connectionToDB();

		$sql = "DELETE FROM Favorites WHERE postID = '$idPost'";

			if(mysqli_query($connection, $sql))	{
				$response = array("status" => "SUCCESS");
				return $response;
			}
			else {
				return array("status" => "418");
			}
	}

	function dbDelete($idPost) {
		$connection = connectionToDB();

		$sql2 = "DELETE FROM posts WHERE postID = '$idPost'";

		if(mysqli_query($connection, $sql2)) {
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "419");
		}
	}

	function dbUploadImage($username, $imageName) {
		$connection = connectionToDB();

		$sql = "INSERT INTO Images (username, image, postDate) VALUES ('$username', 'images/$imageName', CURRENT_DATE())";

		if(mysqli_query($connection, $sql))	{
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "426");
		}
	}

	function dbGetImagesU($uName) {
		$connection = connectionToDB();
		$sql = "SELECT * FROM users JOIN images ON users.username = images.username AND users.username = '$uName'";
	
		$result = $connection->query($sql);

		$response = array();
		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "image"=>$row["image"], "postDate"=>$row["postDate"], "imageID" =>$row["imageId"]);
				array_push($response, $currentRow);
			}
			return $response;
		}
		else {
			return array("status" => "420");
		}
	}

	function dbDeleteImage($idImage) {
		$connection = connectionToDB();

		$sql2 = "DELETE FROM images WHERE imageId = '$idImage'";

		if(mysqli_query($connection, $sql2)) {
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "421");
		}
	}

	function dbGetFavoriteImages($uName) {
		$connection = connectionToDB();

		$sql = "SELECT * FROM favoriteimages f join images i ON f.imageId = i.imageId join users u on i.username = u.username WHERE f.username = '$uName'";
	
		$result = $connection->query($sql);

		$response = array();

		if ($result->num_rows > 0) {
	
			while ($row = $result->fetch_assoc()) {
				$currentRow = array("firstname"=>$row["fName"], "lastname"=>$row["lName"], "image"=>$row["image"], "postDate"=>$row["postDate"], "imageID" =>$row["imageId"]);
				array_push($response, $currentRow);
			}	
			return $response;
		}
		else {
			return array("status" => "422");
		}
	}

	function dbAddFavoriteImage($uName, $idImage) {
		$connection = connectionToDB();

		$sql2 = "SELECT * FROM FavoriteImages WHERE imageId = '$idImage' AND username = '$uName'";

		$result = $connection->query($sql2);

		if ($result->num_rows > 0) {
			return array("status" => "423");
		}
		else {
			$sql = "INSERT INTO FavoriteImages (username, imageId) VALUES ('$uName', '$idImage')";

			if(mysqli_query($connection, $sql)){
				$response = array("status" => "SUCCESS");
				return $response;
			}
			else {
				return array("status" => "424");
			}
		}
	}

	function dbDeleteFavoriteImage($idImage) {
		$connection = connectionToDB();

		$sql = "DELETE FROM FavoriteImages WHERE imageId = '$idImage'";

		if(mysqli_query($connection, $sql)) {
			$response = array("status" => "SUCCESS");
			return $response;
		}
		else {
			return array("status" => "425");
		}
	}

?>
