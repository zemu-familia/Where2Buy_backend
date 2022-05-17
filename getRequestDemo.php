<?php

require_once('db.php');

if(/*isset($_POST['requestID'] )*/true){
	
	$filteredPost = filter_input_array(INPUT_POST, ['googleID' => FILTER_SANITIZE_NUMBER_INT,'displayName' => FILTER_SANITIZE_STRING]);
	$googleID = $filteredPost['googleID'];
	$displayName = $filteredPost['displayName'];


	$query = "SELECT requestID, b.displayName, itemName, areaCenter, requestDate 
				FROM REQUEST a 
				INNER JOIN USER b ON a.userID = b.userID";
	$result = mysqli_query($conn, $query);

	if($result){
		if(mysqli_num_rows($result) == 1){
			while($row = mysqli_fetch_assoc($result)){
				echo json_encode(array(
					"requestID" => $row['requestID'],
					"requesterName" => $row['displayName'],
					"itemName" => $row['itemName'],
					"areaCenter" => $row['areaCenter'],
					"requestDate" => $row['requestDate'],
					"statusCode" => 600,
					"statusMessage" => "Query successful."
				));
			}
		}
	}else{
		echo json_encode(array(
			"statusCode" => 601,
			"statusMessage" => "Query failed." . mysqli_error($conn)
		));
	}
}else{
	echo json_encode(array(
		"statusCode" => 401,
		"statusMessage" => "An error occurred." . mysqli_error($conn)
	));
}
	
?>