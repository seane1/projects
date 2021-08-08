<?php
if(isset($_GET["mountPos"])){
	$mountPos = $_GET["mountPos"];
	$myfile = fopen("script.ps1", "w") or die("Unable to open file!");

	$txt = "\$port= new-Object System.IO.Ports.SerialPort COM3,9600,None,8,one
	\$port.open()

	\$port.WriteLine('AT+NAMESNS-GLB-".$mountPos." ')

	\$port.ReadLine()

	\$port.WriteLine('AT+RESET ')

	\$port.Close()";
	fwrite($myfile, $txt);
	fclose($myfile);

	$success = Shell_exec ('powershell.exe -executionpolicy bypass -NoProfile -File .\script.ps1');
	if($success){
		echo "<div class='cmd'>".$success."</div>";
	}else{
		echo "<div class='cmd error'>Check USB/Serial connection, Powershell environment/permissions, Windows user permissions</div>";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sensen QC</title>
<style>
.cmd{background-color:blue;color:white;width:100%;padding:20px;}
.error{background-color:red;}
input{padding:20px 40px;font-size}
.grid-container{display: grid;grid-template-columns: auto auto;width:450px;}
.grid-item{margin:10px;}
h2{padding:20px;font-family:Arial;}
</style>
</head>
<body>
<div class="grid-container">
<div class="grid-item">
	<form method="GET" action="test.php">
		<input type="submit" value="FL"  id="mountPos" name="mountPos">
	</form>
</div>
<div class="grid-item">
	<form method="GET" action="test.php">
		<input type="submit" value="FR"  id="mountPos" name="mountPos">
	</form>
</div>
<div class="grid-item">
	<form method="GET" action="test.php">
		<input type="submit" value="RL"  id="mountPos" name="mountPos">
	</form>
	</div>
<div class="grid-item">
	<form method="GET" action="test.php">
		<input type="submit" value="RR"  id="mountPos" name="mountPos">
	</form>
</div>	
</div>
<h2>SenSen lightbar renaming tool</h2>
</body>
</html>