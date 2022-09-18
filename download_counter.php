
<?php
//require 'connection.php';
require '../../connect.inc';
require 'functions.php';

$conn = Connect();
mysqli_set_charset($conn,'utf8');

$id = $_GET['id'];
$target = $_GET['target'];

$sql = "SELECT * FROM projects_data WHERE ID = '".$id."'";
$result = mysqli_query($conn, $sql);  
$resultCheck = mysqli_num_rows($result);

if ($resultCheck > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$downloads = $row['downloads'];
		$projectname = $row['project_name'];
	}
}

$translitname = russian2translit($projectname);

$downloads = intval($downloads) + 1;

$query = "
UPDATE projects_data
SET downloads = '".$downloads."'
WHERE ID = '".$id."'
";

$success = $conn->query($query);
//Handle error here

session_start();

if (isset($_SESSION['u_type'])) {
	if ($_SESSION['u_type'] == "user") {
		$returnlink = "main.php";
	} else {
		$returnlink = "admin.php";
	}
}

if ($target == "all") {
	$conn->close();
	header("Location: uploads/".$translitname."/".$translitname.".zip");
	exit();
} elseif($target == "text") {
	$result = glob("uploads/".$translitname."/Tekst proekta -*");
	$conn->close();
	if (!$result) {
		header("Location: ".$returnlink."?error=notext");
		exit();
	} else {
		header("Location: ".$result[0]."");	
		exit();
	}
	exit();
} else {
	$result = glob("uploads/".$translitname."/Prezentaciya proekta -*");
	$conn->close();
	if (!$result) {
		header("Location: ".$returnlink."?error=nopres");
		exit();
	} else {
		header("Location: ".$result[0]."");
		exit();
	}
	
	
}


	
$conn->close();


?>