<?php
	header('Content-type: application/json');
	header('Accept: application/json');
	require_once __DIR__ . '/dataLayer.php';
	ini_set('display_errors', 1); 
	ini_set('log_errors', 1); 
	error_reporting(E_ALL);

	$action = $_POST["action"];

	switch($action)
	{
		case 'LOGIN':
			attemptLogin();
			break;
		case 'REGISTER':
			attemptRegister();
			break;
		case 'LOGOUT':
			attemptLogout();
			break;
		case 'GETSESSION':
			attemptGetSession();
			break;
		case 'COOKIE':
			 attemptCookie();
			 break;
		case 'GETPOSTS':
			 attemptGetPosts();
			 break;
		case 'GETPOSTSDATE':
			 attemptGetPostsDate();
			 break;
		case 'GETREPLIES':
			 attemptGetReplies();
			 break;
		case 'ADDREPLY':
			 attemptAddReply();
			 break;
		case 'GETGALLERY':
			 attemptGetGallery();
			 break;
		case 'GETGALLERYDATE':
			 attemptGetGalleryDate();
			 break;
		case 'GETPOSTSU':
			 attemptGetPostsU();
			 break;
		case 'POST':
			 attemptPost();
			 break;
		case 'EDIT':
			 attemptEdit();
			 break;
		case 'DELETEP':
			 attemptDelete();
			 break;
		case 'STARTSESSION':
			attemptStartSession();
			break;
		case 'ADDFAVORITE':
			attemptFavorite();
			break;
		case 'GETFAVORITES':
			attemptGetFavorites();
			break;
		case 'DELETEFAVORITE':
			attemptDeleteFavorite();
			break;
		case 'GETIMAGESUSER':
			attemptGetImages();
			break;
		case 'DELETEIMAGE':
			attemptDeleteImage();
			break;
		case 'GETFAVORITEIMAGES':
			attemptGetFavoriteImages();
			break;
		case 'ADDFAVORITEIMAGE':
			attemptAddFavoriteImage();
			break;
		case 'DELETEFAVORITEIMAGE':
			attemptDeleteFavoriteImage();
			break;
		default:
			# code
			break;
	}

	function attemptLogin() {
		$uName = $_POST["uName"];

		$result = dbLogin($uName);

		if($result["status"] == "SUCCESS") {

			$uPassword = decryptPassword($result["uPass"]);
			if($_POST["uPassword"] === $uPassword) {

				$rememberMe = $_POST["rememberMe"];

				if($rememberMe == "true") {
					setcookie("usernameWeb", "$uName", time()+3600*24*5);
				}

				session_start();
				$_SESSION["fName"]=$result["firstname"];
				$_SESSION["lName"]=$result["lastname"];
				$_SESSION["uName"]=$result["uName"];

				echo json_encode($result);
			}
			else {
				errorHandling("402");
			}
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptRegister() {
		$uName = $_POST["uName"];
		$uF = $_POST["uF"];
		$uL = $_POST["uL"];

		$email = $_POST["email"];

		$uPassword = encryptPassword();

		$result = dbRegister($uName, $uPassword, $uL, $uF, $email);

		if($result["status"] == "SUCCESS") {

			session_start();
			$_SESSION["fName"]=$result["firstname"];
			$_SESSION["lName"]=$result["lastname"];
			$_SESSION["uName"]=$result["uName"];

			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function encryptPassword() {
		$uPassword = $_POST["uPassword"];

		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
	    $keySize =  strlen($key);
		
	    $ivSize = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = @mcrypt_create_iv($ivSize, MCRYPT_RAND);
	    
	    $cipher = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $uPassword, MCRYPT_MODE_CBC, $iv);
	    $cipher = $iv . $cipher;
		
		$uPassword = base64_encode($cipher);
		
		return $uPassword;
	}

	function decryptPassword($uPassword) {
		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
	    $cipher = base64_decode($uPassword);
		
		$ivSize = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = substr($cipher, 0, $ivSize);
	    $cipher = substr($cipher, $ivSize);

	    $uPassword = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $cipher, MCRYPT_MODE_CBC, $iv);
	   	
	   	$uPassword = rtrim($uPassword, "\0");
	    return $uPassword;
	}

	function attemptLogout() {
		session_start();
		unset($_SESSION["fName"]);
		unset($_SESSION["lName"]);
		unset($_SESSION["uName"]);
		session_destroy();
		echo json_encode(array('success'=>'Session deleted')); 
	}

	function attemptGetSession() {
		session_start();
		if(isset($_SESSION["fName"])) {
			$response = array("firstname"=>$_SESSION["fName"], "lastname"=>$_SESSION["lName"]);
			echo json_encode($response);
		}
		else {
			errorHandling("404");
		}
	}

	function attemptCookie() {
		if(isset($_COOKIE["usernameWeb"])) {
			$response2 = $_COOKIE["usernameWeb"];
			echo json_encode(array("cookieUserName" => $_COOKIE["usernameWeb"]));
		}
		else {
			errorHandling("405");
		}
	}

	function attemptStartSession() {
		$username = $_POST["username"];
		$password = $_POST["password"];

		$result = dbLogin($username, $password);

		if($result["status"] == "SUCCESS"){

			session_start();
			$_SESSION["fName"]=$result["firstname"];
			$_SESSION["lName"]=$result["lastname"];
			$_SESSION["uName"]=$result["uName"];

			echo json_encode(array('success'=>'Session started with new user'));
		}
		else{
			errorHandling($result["status"]);
		}
	}

	function attemptGetPosts() {
		$result = dbGetPosts();
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptGetPostsDate() {
		$date = $_POST["date"];
		$result = dbGetPostsDate($date);
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptGetReplies() {
		$idPost = $_POST["idPost"];
		$result = dbGetReplies($idPost);
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptGetPostsU() {
		session_start();
		$uName = $_SESSION["uName"];
		$result = dbGetPostsU($uName);
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptGetGallery() {
		$result = dbGetGallery();
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptGetGalleryDate() {
		$date = $_POST["date"];
		$result = dbGetGalleryDate($date);
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptPost() {
		session_start();
		$uName = $_SESSION["uName"];
		$comment = $_POST["comment"];

		$result = dbPost($comment, $uName);

		if($result["status"] == "SUCCESS") {	
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptEdit() {
		session_start();
		$uName = $_SESSION["uName"];
		$comment = $_POST["comment"];
		$idPost = $_POST["idPost"];

		$result = dbUpdate($comment, $idPost);

		if($result["status"] == "SUCCESS") {
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptAddReply() {
		session_start();
		$uName = $_SESSION["uName"];
		$reply = $_POST["reply"];
		$idPost = $_POST["idPost"];

		$result = dbAddReply($reply, $idPost, $uName);

		if($result["status"] == "SUCCESS") {
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}


	function attemptFavorite() {
		session_start();
		$uName = $_SESSION["uName"];
		$idPost = $_POST["idPost"];

		$result = dbAddFavorite($uName, $idPost);

		if($result["status"] == 'SUCCESS') {
			echo json_encode(array('success'=>'Favorite post added'));
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptGetFavorites() {
		session_start();
		$uName = $_SESSION["uName"];

		$result = dbGetFavorites($uName);

		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptDeleteFavorite() {
		$idPost = $_POST["idPost"];

		$result = dbDeleteFavorite($idPost);

		if($result["status"] == "SUCCESS") {	
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptDelete() {
		$idPost = $_POST["idPost"];

		$result = dbDelete($idPost);

		if($result["status"] == "SUCCESS") { 
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptGetImages() {
		session_start();
		$uName = $_SESSION["uName"];
		$result = dbGetImagesU($uName);
		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptDeleteImage() {
		$idImage = $_POST["idImage"];

		$result = dbDeleteImage($idImage);

		if($result["status"] == "SUCCESS") {
			
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptGetFavoriteImages() {
		session_start();
		$uName = $_SESSION["uName"];

		$result = dbGetFavoriteImages($uName);

		if(isset($result["status"])) {
			errorHandling($result["status"]);
		}
		else {
			echo json_encode($result);
		}
	}

	function attemptAddFavoriteImage() {
		session_start();
		$uName = $_SESSION["uName"];
		$idImage = $_POST["idImage"];

		$result = dbAddFavoriteImage($uName, $idImage);

		if($result["status"] == 'SUCCESS') {
			echo json_encode(array('success'=>'Favorite added'));
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function attemptDeleteFavoriteImage() {
		$idImage = $_POST["idImage"];

		$result = dbDeleteFavoriteImage($idImage);

		if($result["status"] == "SUCCESS") {	
			echo json_encode($result);
		}
		else {
			errorHandling($result["status"]);
		}
	}

	function errorHandling($errorCode) {

		switch($errorCode) {
			case '500':
				header("HTTP/1.1 500 Bad connection, portal down");
				die("The server is down, we couldn't stablish the data base connection.");
				break;
			case '401':
				header("HTTP/1.1 401 User not found.");
				die("Wrong credentials provided.");
				break;
			case '402':
				header("HTTP/1.1 402 Incorrect password");
				die("Incorrect password");
				break;
			case '403':
				header("HTTP/1.1 403 Username already exist");
				die("Username already exists.");
				break;
			case '404':
				header("HTTP/1.1 404 There in no session open");
				die("There is no session open");
				break;
			case '405':
				header("HTTP/1.1 405 Cookie not set yet");
				die("Cookie not set yet");
				break;
			case '406':
				header("HTTP/1.1 406 There are no posts to be shown");
				die("There are no posts to be shown");
				break;
			case '407':
				header("HTTP/1.1 407 There are no posts in the selected date");
				die("There are no posts in the selected date");
				break;
			case '408':
				header("HTTP/1.1 408 There are no replies for the post");
				die("There are no replies for the post");
				break;
			case '409':
				header("HTTP/1.1 409 You have no posts");
				die("You have no posts");
				break;
			case '410':
				header("HTTP/1.1 410 There are no images to be loaded");
				die("There are no images to be loaded");
				break;
			case '411':
				header("HTTP/1.1 411 There are no images in the selected date");
				die("There are no images in the selected date");
				break;
			case '412':
				header("HTTP/1.1 412 Bad connection, portal down, it was no posted");
				die("Bad connection, portal down, it was no posted");
				break;
			case '413':
				header("HTTP/1.1 413 Not success in adding reply");
				die("Not success received in adding reply");
				break;
			case '414':
				header("HTTP/1.1 414 Bad connection, portal down, the edition couldn't be saved");
				die("Bad connection, portal down, the edition couldn't be saved");
				break;
			case '415':
				header("HTTP/1.1 415 This post was added previously to favorites");
				die("This post was added previously to favorites");
				break;
			case '416':
				header("HTTP/1.1 416 Bad connection, portal down, the post couldn't be added");
				die("Bad connection, portal down, the post couldn't be added");
				break;
			case '417':
				header("HTTP/1.1 417 No favorite post added before");
				die("No favorite post added before");
				break;
			case '418':
				header("HTTP/1.1 418 No success in deleting from favorites");
				die("No success in deleting from favorites");
				break;
			case '419':
				header("HTTP/1.1 419 Bad connection, portal down, the post couldn't be deleted");
				die("Bad connection, portal down, the post couldn't be deleted");
				break;
			case '420':
				header("HTTP/1.1 420 You have no added images before");
				die("You have no images added before");
				break;
			case '421':
				header("HTTP/1.1 421 No success in deleting image");
				die("No success in deleting image");
				break;
			case '422':
				header("HTTP/1.1 422 No favorite image added before");
				die("No favorite image added before");
				break;
			case '423':
				header("HTTP/1.1 423 This image was added previously to favorites");
				die("This image was added previously to favorites");
				break;
			case '424':
				header("HTTP/1.1 424 Bad connection, portal down, the image couldn't be added");
				die("Bad connection, portal down, the image couldn't be added");
				break;
			case '425':
				header("HTTP/1.1 425 Bad connection, portal down, the image couldn't be deleted");
				die("Bad connection, portal down, the image couldn't be deleted");
				break;
			default:
				header("HTTP/1.1 500 Bad connection, portal down ");
				die("The server is down, we couldn't stablish the data base connectionnn.");
				break;
		}
	}

?>
