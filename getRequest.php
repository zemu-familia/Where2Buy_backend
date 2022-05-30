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

	// Formula to calculate distance between two points found in https://stackoverflow.com/questions/24370975/find-distance-between-two-points-using-latitude-and-longitude-in-mysql
	$query = "SELECT requestID, itemName, requestDate FROM REQUEST WHERE requestID = $requestID";
					
	$result = mysqli_query($conn, $query);
	if($result){ // If query succeeds
		if(mysqli_num_rows($result) > 0){ // If there are results	
			$resultsArray = array();
			
			//$jsonArray["status"] = array("statusCode" => 600, "statusMessage" => "Query successful. Results found.");
			statusCodeJSON($jsonArray, 600, "Query successful. Match found.");
			$row = mysqli_fetch_assoc($result);
			array_push($resultsArray, array(
				"requestID" => $row['requestID'],
				"itemName" => $row['itemName'],
				"requestDate" => $row['requestDate'],
			));
			
			$jsonArray["result"] = $resultsArray;
			
		}else{ // If there are no results
			statusCodeJSON($jsonArray, 601, "Query returned no rows.\n" . $query);
		}
	}else{
		statusCodeJSON($jsonArray, 610, "Query failed." . mysqli_error($conn));
	}
}else{ 
	statusCodeJSON($jsonArray, 401, "An error occurred");
}
echo json_encode($jsonArray);
?>