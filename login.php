<?php

require_once('db.php');

if($isset($_POST['googleID'] ) && isset($_POST['googleName'])){
	
	$filteredPost = filter_input_array(INPUT_POST, 
												[
												'googleID' => FILTER_SANITIZE_NUMBER_INT,
												'googleName' => FILTER_SANITIZE_STRING
												]);
												
	$googleID = $filteredPost['googleID'];
	$googleName = $filteredPost['googleName'];


	$query = "SELECT * FROM USER WHERE userID = $googleID";
	$result = mysqli_query($conn, $query);
	
	
	$sqlEscapedName = mysqli_real_escape_string($googleName);
	if(mysqli_num_rows($conn) == 0){ //If user does not exist in database
		// Add user into database using google details
		
		$query = "INSERT INTO USER(userID, userName) VALUES('$googleID', '$sqlEscapedName')";
		
		$result = mysqli_query($conn, $query);
		
		if(!$result){ // If account creation succeeeds
			echo json_encode(array(
				"googleID" => $googleID,
				"googleName" => $googleName,
				"statusCode" => 200,
				"statusDesc" => "Where2Buy account successfully created."
			));
		}else{	// If account creation failed in db
			echo json_encode(array(
				"statusCode" => 201,
				"statusDesc" => "Where2Buy account creation failed."
			));
		}
	}else{ // If user already exist in database
		// Do nothing for now
	}
}
	
?>