<?php
include 'functions.php';

include 'header.php';

if(isset($_POST["login"])){
	if($_POST["login"] == "admin"){
		$_SESSION["login"] = "ok";
		header('Location: http://' . $server . ':' . $apacheport . '/' . $sitefolder . '/health-monitor.php');
	}
}
?>
<form action="index.php" method="POST" class="login-form">
<label for="login">Login: </label><input type="password" id="login" name="login"/>
</form>
<?php
include 'footer.php';
?>