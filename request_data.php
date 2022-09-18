<?php

//require 'connection.php';
require '../../connect.inc';

$conn = Connect();
mysqli_set_charset($conn,'utf8');
/*
if (!isset($_SESSION['u_type'])) {
	header("Location: index.php");
	$conn->close();
	exit();
} elseif($_SESSION['u_type'] != "admin") {
	header("Location: index.php");
	$conn->close();
	exit();
}
*/

// Getting requested data

$id = $_POST['id'];


$sql = "SELECT * FROM projects_data WHERE ID = '".$id."'";
$result = mysqli_query($conn, $sql);  
$resultCheck = mysqli_num_rows($result);

if ($resultCheck > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$project_name   = $row['project_name'];
		$authors        = $row['authors'];
		$supervisor     = $row['supervisor'];
		$faculty 	    = $row['faculty'];
		$grade          = $row['grade'];		
		$description    = $row['description'];
		$start_date     = $row['start_date'];
		$submit_date    = $row['submit_date'];
		$project_status = $row['project_status'];
		$project_type   = $row['project_type'];
		$project_log    = $row['project_log'];
	}
}

$conn->close();
// Sending requested data 

$response = $project_name."@@@".$authors."@@@".$supervisor."@@@".$faculty."@@@";
$response .= $grade."@@@".$description."@@@".$start_date."@@@".$submit_date."@@@";
$response .= $project_status."@@@".$project_type."@@@".$project_log;

echo $response;

?>