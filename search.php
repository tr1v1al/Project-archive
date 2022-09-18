<?php
session_start();
$searchdata = $_POST['search_field'];
$user = $_SESSION['u_type'];

if ($user == "user") {
	header("Location: main.php?search=".$searchdata."");
	exit();
} elseif ($user == "admin") {
	header("Location: admin.php?search=".$searchdata."");
	exit();
}



?>