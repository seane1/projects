<?php
include 'functions.php';

include 'header.php';

if(!isset($_SESSION["login"])){
	header('Location: http://' . $server . ':' . $apacheport . '/' . $sitefolder . '/index.php');
}
?>
<h2>Facility : <form action="" method="GET">
<select id="facility" name="facility" onchange="this.form.submit()">
<?php
	if(!isset($_GET["facility"])){
		printkeys();
	}else{
		printkeys($_GET["facility"]);
	}
?>
</select></h2>
<div id="barchart"></div>
<div id='statussocket'></div>
<?php 
if(!isset($_GET["facility"]) || $_GET["facility"] == 0){
	echo "<script type='text/javascript'>
	var server = '".$server."';
	var socket = '".$wsport."';
	getSocket(server, socket);</script>";
}

if(isset($_GET["facility"]) && $_GET["facility"]!=0){
	echo '<div class="grid-container">';
	echo '<div class="grid-item heading">Time : <input type="date" id="date" name="date" onchange="this.form.submit()"/>';
	if (isset($_GET["date"])){echo "<div class='date'>".$_GET["date"]."</div>";}
	echo '</div>';
	echo '<div class="grid-item heading trackid">Track ID</div>';
	echo '<div class="grid-item heading eventlevel">Event Level</div>';
	echo '<div class="grid-item heading details">Details</div>';
	echo '<div class="grid-item heading">elEvent</div>';
	if(!isset($_GET["date"])){
		printlogs($_GET["facility"]);
	}else{
		printlogs($_GET["facility"], $_GET["date"]);
	}
	echo '</form>';
	echo '</div>';
}

include 'footer.php';
?>