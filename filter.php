<?php

session_start();
$user = $_SESSION['u_type'];

if ($user == "user") {
	$filter = "main.php?filter=";
} elseif($user == "admin") {
	$filter = "admin.php?filter=";
}


if (isset($_POST['sixth'])) {
	$filter = $filter."*6";
}

if (isset($_POST['seventh'])) {
	$filter = $filter."*7";
}

if (isset($_POST['eighth'])) {
	$filter = $filter."*8";
}

if (isset($_POST['ninth'])) {
	$filter = $filter."*9";
}

if (isset($_POST['tenth'])) {
	$filter = $filter."*10";
}

if (isset($_POST['eleventh'])) {
	$filter = $filter."*11";
}

$filter = $filter."~";

if (isset($_POST['fiztekh'])) {
	$filter = $filter."*f";
}

if (isset($_POST['sotzgum'])) {
	$filter = $filter."*s";
}

if (isset($_POST['proga'])) {
	$filter = $filter."*p";
}

if (isset($_POST['teormat'])) {
	$filter = $filter."*t";
}

if (isset($_POST['lingvo'])) {
	$filter = $filter."*l";
}

if (isset($_POST['biokhim'])) {
	$filter = $filter."*b";
}

if (isset($_POST['bez'])) {
	$filter = $filter."*n";
}

$filter = $filter."~";

	$minyear = 2014;
	$curryear = intval(date('Y'));
	$currmonth = intval(date('m'));

	if ($currmonth >= 1 && $currmonth <= 5) {
		$counter = $curryear - $minyear;
	} else {
		$counter = $curryear - $minyear + 1;
	}

	$gap = $minyear;

	for ($i=0; $i < $counter; $i++) { 
		$filler = $gap.'-';
		++$gap;
		$filler = $filler.$gap;
		if (isset($_POST[$filler])) {
			$filter = $filter.'*'.$filler;
		}
	}





header("Location: ".$filter."");
exit();

?>