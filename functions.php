<?php
function statusCodeJSON(&$fJsonArray, $fStatusCode, $fStatusMsg){
	$fJsonArray["status"] = array(
		"statusCode" => $fStatusCode,
		"statusMessage" => $fStatusMsg
	);
}
?>