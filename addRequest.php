<?php

require_once('db.php');
require_once('functions.php');

if(true){
  $requesterID = filter_input(INPUT_POST, 'requesterID', FILTER_SANITIZE_NUMBER_INT);
  $requesterLat = filter_input(INPUT_POST, 'requesterLat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $requesterLng = filter_input(INPUT_POST, 'requesterLng', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $itemName = ucwords(mysqli_real_escape_string($conn, filter_input(INPUT_POST, 'itemName', FILTER_SANITIZE_STRING)));
  $areaRadius = filter_input(INPUT_POST, 'areaRadius', FILTER_SANITIZE_NUMBER_INT);
  $imageBase64 = $_POST['imageBase64'];

  $imagePath = "./image/request/";
  $fileName = "$requesterID-".date("dmyHis").".jpg";
  $combinedPath = $imagePath.$fileName;

  if(!is_dir($imagePath)){
    mkdir($imagePath, 0777, true);
  }

  if(file_put_contents("$combinedPath", base64_decode($imageBase64))){
    $query = "INSERT INTO REQUEST(requesterUserID, itemName, areaCenterRadius, areaCenterLat, areaCenterLng, requestDate, imagePath) VALUES('$requesterID', '$itemName', '$areaRadius', '$requesterLat', '$requesterLng', CURRENT_TIMESTAMP, '$combinedPath')";

    $result = mysqli_query($conn, $query);
    if($result){ // If query succeeds
      statusCodeJSON($jsonArray, 600, "Request successfully sent.");
    }else{
      statusCodeJSON($jsonArray, 610, "Query failed. " . mysqli_error($conn));
    }
  }
  else {
    statusCodeJSON($jsonArray, 610, "File upload failed. " . mysqli_error($conn));
  }

  //$query = "INSERT INTO REQUEST(requesterUserID, itemName, areaCenterRadius, areaCenterLat, areaCenterLng, requestDate) VALUES('$requesterID', '$itemName', '$areaRadius', '$requesterLat', '$requesterLng', CURRENT_TIMESTAMP)";

}else{
  statusCodeJSON($jsonArray, 401, "An error occurred");
}
//$jsonArray["query"] = array("queryFull" => $query);
echo json_encode($jsonArray);

?>