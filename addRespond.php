<?php

require_once('db.php');
require_once('functions.php');
// TO-DO: some form of login validation probably? instead of if(true)

$jsonArray = array();
if(/*isset($_POST['requestID'] )*/true){
	$requestID = filter_input(INPUT_POST, 'requestID', FILTER_SANITIZE_NUMBER_INT);
	$responderID = filter_input(INPUT_POST, 'responderID', FILTER_SANITIZE_NUMBER_INT);
	$storeLat = filter_input(INPUT_POST, 'storeLat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$storeLng = filter_input(INPUT_POST, 'storeLng', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$storeName = mysqli_real_escape_string($conn, filter_input(INPUT_POST, 'storeName', FILTER_SANITIZE_STRING));

	$query = "INSERT INTO LISTING(RequestID, responderUserID, storeName, storeLocationLat, storeLocationLng)
				VALUES($requestID, '$responderID', '$storeName', $storeLat, $storeLng)";
	
	$result = mysqli_query($conn, $query);
	if($result){ // If query succeeds
		//$jsonArray["status"] = array("statusCode" => 600, "statusMessage" => "Query successful. Results found.");
		statusCodeJSON($jsonArray, 600, "Response successfully sent.");
		//$row = mysqli_fetch_assoc($result);
	}else{
		statusCodeJSON($jsonArray, 610, "Query failed. " . mysqli_error($conn));
		$jsonArray["query"] = array("queryFull" => $query);
	}
	//statusCodeJSON($jsonArray, 600, "POSTed");
}else{ 
	statusCodeJSON($jsonArray, 401, "An error occurred");
}
echo json_encode($jsonArray);
?>