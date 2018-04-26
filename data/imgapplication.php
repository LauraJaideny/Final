<?php
	//header('Content-type: application/json');
	//header('Accept: application/json');
	require_once __DIR__ . '/dataLayer.php';
	ini_set('display_errors', 1); 
	ini_set('log_errors', 1); 
	error_reporting(E_ALL);

	session_start();
	$uName = $_SESSION["uName"];

	if(isset($_FILES["file"]["type"])) {
			
			$validextensions = array("jpeg", "jpg", "png");
			$temporary = explode(".", $_FILES["file"]["name"]);
			$file_extension = end($temporary);
			
			if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && in_array($file_extension, $validextensions)) {

				if ($_FILES["file"]["error"] > 0) {
					echo json_encode($_FILES["file"]["error"]) ;
				}
				else {
					if (file_exists("../images/" . $_FILES["file"]["name"])) {
						echo json_encode("already exists");
					}
					else {
						$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
						$imageName = $_FILES["file"]["name"];

						$result = dbUploadImage($uName, $imageName);

						if($result["status"]=="SUCCESS") {

							$targetPath = "../images/".$_FILES['file']['name']; // Target path where file is to be stored
							move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
							echo json_encode($result);
						}
						else{
							errorHandling($result["status"]);
						}
					}
				}
			}
			else {
			errorHandling("427");
			}
		}

	function errorHandling($errorCode) {
		switch($errorCode) {
			case '500':
				header("HTTP/1.1 500 Bad connection, portal down");
				die("The server is down, we couldn't stablish the data base connection.");
				break;			
			case '426':
				header("HTTP/1.1 426 Bad connection, portal down, the image couldn't be uploaded");
				die("Bad connection, portal down, the image couldn't be uploaded");
				break;
			case '427':
				header("HTTP/1.1 427 The image has no valid extensions");
				die("The image has no valid extensions");
				break;
			default:
				header("HTTP/1.1 500 Bad connection, portal down ");
				die("The server is down, we couldn't stablish the data base connectionnn.");
				break;
		}
	}

	#The image code was made using this tutorial made by Neeraj Agarwal and retrieved from: https://www.formget.com/ajax-image-upload-php/ 

?>


