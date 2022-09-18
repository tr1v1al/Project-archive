<?php
session_start();

//Error handling

if (isset($_POST['submit'])) {
	$pwd = $_POST['password'];

	//Empty password
	if (empty($pwd)) {
		header("Location: index.php?login=empty");
		exit();
	
	//Correct password, redirection to main page
	} elseif ($pwd == "login") {						//Пароль обычных пользователей изменяется здесь
		//Creating session variable user type,  
		//might have different users in the future
		$_SESSION['u_type'] = "user";
		header("Location: main.php");
		exit();
		
	} elseif ($pwd == "admin") {                        //Пароль администратора изменяется здесь
		//Creating session variable user type,  
		//might have different users in the future
		$_SESSION['u_type'] = "admin";
		header("Location: admin.php");
		exit();

	//Incorrect password
	} else {
		header("Location: index.php?login=incpwd");
		exit();
	}
//Attempt do directly reach this file
} else {
	header("Location: index.php?login=fatalerror");
	exit();
}


?>