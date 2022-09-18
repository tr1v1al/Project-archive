<?php
	include_once 'header.php';
?>
<!--the login box as a whole-->
<div class="login-box">
<?php
	// session might have started
if (isset($_SESSION['u_type'])) { 
	if ($_SESSION['u_type'] == 'admin') {
		header("Location: admin.php");
	} elseif ($_SESSION['u_type'] == 'user') {
		header("Location: main.php");
	}
} else {
// perhaps it hasn't
	echo '
	<p>Авторизация</p>
	<form action="login.php" method="POST" autocomplete="new-password">
	';
	if (isset($_GET['login'])) {
		if ($_GET['login'] == "incpwd") {
			echo ' 
			<input type="password" name="password" placeholder="Неправильный пароль" autofocus id="incpwd">
			';
		} elseif($_GET['login'] == "empty") {
			echo '
			<input type="password" name="password" placeholder="Вы не ввели пароль" autofocus id="incpwd">
			';		
		}
	} else {
		echo '
		<input type="password" name="password" placeholder="Введите пароль" autofocus>
		';				
	}

	echo '	
		<input type="submit" name="submit" value="Войти">
	</form>
	';

}
?>	
</div>



<?php
	include_once 'footer.php';
?>