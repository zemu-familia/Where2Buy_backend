<?php

require_once('db.php');

function statusCodeJSON($fStatusCode, $fStatusMsg){
	echo json_encode(array(
		"statusCode" => $fStatusCode,
		"statusMessage" => $fStatusMsg
	));
}

// TO-DO: some form of login validation probably? instead of if(true)
if(/*isset($_POST['requestID'] )*/true){
	$googleID = filter_input(INPUT_POST, 'googleID', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$responderLat = filter_input(INPUT_POST, 'responderLat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$responderLng = filter_input(INPUT_POST, 'responderLng', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

	// Formula to calculate distance between two points found in https://stackoverflow.com/questions/24370975/find-distance-between-two-points-using-latitude-and-longitude-in-mysql
	$query = "SELECT 
				requestID, 
				b.displayName, 
				itemName, 
				requestDate, 
				111.111 
					* DEGREES(ACOS(LEAST(1.0, COS(RADIANS(a.areaCenterLat)) 
					* COS(RADIANS($responderLat)) 
					* COS(RADIANS(a.areaCenterLng - $responderLng)) 
					+ SIN(RADIANS(a.areaCenterLat)) 
					* SIN(RADIANS($responderLat)))))
				AS distance_in_km 
				FROM REQUEST a 
				INNER JOIN USER b 
					ON a.requesterUserID = b.userID 
				HAVING
					distance_in_km <= 10
				ORDER BY
					distance_in_km ASC";
	$result = mysqli_query($conn, $query);
	
	if($result){ // If query succeeds
		if(mysqli_num_rows($result) > 0){ // If there are results	
			statusCodeJSON(600, "Query successful. Results found.");
			while($row = mysqli_fetch_assoc($result)){
				echo json_encode(array(
					"requestID" => $row['requestID'],
					"requesterName" => $row['displayName'],
					"itemName" => $row['itemName'],
					//"areaCenter" => $row['areaCenter'],
					"distanceFromCenter" => $row['distance_in_km'],
					"requestDate" => $row['requestDate'],
				));
			}
		}else{ // If there are no results
			statusCodeJSON(601, "Query successful. No requests found nearby.");
		}
	}else{
		statusCodeJSON(610, "Query failed." . mysqli_error($conn));
	}
}else{ 
	statusCodeJSON(401, "An error occurred");
}
?>