<?php

require_once('db.php');
require_once('functions.php');

if(true){
  $requesterID = filter_input(INPUT_POST, 'requesterID', FILTER_SANITIZE_NUMBER_INT);
  $requesterLat = filter_input(INPUT_POST, 'requesterLat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $requesterLng = filter_input(INPUT_POST, 'requesterLng', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $itemName = ucwords(mysqli_real_escape_string($conn, filter_input(INPUT_POST, 'itemName', FILTER_SANITIZE_STRING)));
  $areaRadius = filter_input(INPUT_POST, 'areaRadius', FILTER_SANITIZE_NUMBER_INT);


  $query = "INSERT INTO REQUEST(requesterUserID, itemName, areaCenterRadius, areaCenterLat, areaCenterLng, requestDate) VALUES('$requesterID', '$itemName', '$areaRadius', '$requesterLat', '$requesterLng', CURRENT_TIMESTAMP)";

  $result = mysqli_query($conn, $query);
  if($result){ // If query succeeds
    statusCodeJSON($jsonArray, 600, "Request successfully sent.");
  }else{
    statusCodeJSON($jsonArray, 610, "Query failed. " . mysqli_error($conn));
    $jsonArray["query"] = array("queryFull" => $query);
  }
}else{
  statusCodeJSON($jsonArray, 401, "An error occurred");
}
echo json_encode($jsonArray);

?>