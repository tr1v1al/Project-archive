<?php
	session_start();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Библиотека проектов</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script type="text/javascript">
		function logout() {
			location.href = "logout.php";
		}
		function upload() {
			document.getElementById('upload-box-wrapper').style.display='block';
		}
		function filter() {
			document.getElementById('filter-box-wrapper').style.display='block';
		}
	</script>	
			
	</style>
</head>

<body>
	



<?php
if (isset($_SESSION['u_type'])) {
	$homebutton = glob("tools/home-button.*"); 
	$homebuttonPath = $homebutton[0];

	if ($_SESSION['u_type'] == "user") {
		$homelink = "main.php";
	} else {
		$homelink = "admin.php";
	}

	if (isset($_GET['search'])) {
		$searchvalue = $_GET['search'];
	} else {
		$searchvalue = "";
	}

	echo '
	<div class="logo-header">
		<a href="'.$homelink.'" title="На главную"><img src="tools/logo.png"></a>
	</div>

	<div class="utility-header">
	<div class="left-block">
		<form class="search-container" action="search.php" method="POST" id="search-container">
    		<input type="text" id="search-bar" placeholder="Искать..." name="search_field" autocomplete="off" value="'.$searchvalue.'">
    		<a href="#" onclick="document.getElementById(\'search-container\').submit();"><img class="search-icon" src="tools/search-icon.png"></a>
  		</form>		
	</div><div class="right-block" >
		<div class="header-filter-block" onclick="filter()">
			<p class="header-logout-title">ФИЛЬТРЫ</p>
		</div><div class="header-upload-block" onclick="upload()">
			<p class="header-logout-title">ЗАГРУЗИТЬ</p>
		</div><div class="header-logout-block" onclick="logout()">
			<p class="header-logout-title">ВЫЙТИ</p>
		</div>
	</div>
	';
} else {
	echo '
	<div class="logo-header">
		<img src="tools/logo.png">
	</div>

	<div class="utility-header">
	';
}
//http://www.endlessicons.com/wp-content/uploads/2012/12/search-icon.png

?>
</div>
		