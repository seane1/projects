<?php
function dbconnect(){
	$serverName = "localhost";  
	$connectionOptions = array("Database"=>"Alitrax",  
		"Uid"=>"sa", "PWD"=>"ThisIsAVerySecurePassword2");  
	$conn = sqlsrv_connect($serverName, $connectionOptions);  
	if($conn == false){  
		echo "<p>die</p>";
	}	
	return $conn;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="favicon.ico" type="image/x-icon" rel="icon" /><link href="favicon.ico" type="image/x-icon" rel="shortcut icon" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" type="text/css">
<style>
@import url("https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700|Open+Sans:300,400,600,700|Source+Code+Pro");
@font-face{font-family: Heading;src: url(MagistralC-Bold.otf);}
body{background-color: #141921;background-image: url(circle.svg);background-repeat: repeat;background-size: 5px;font-family:"Open Sans";}
header{color:white;text-align:center;font-family:Heading;}
main{}
h2{color:white;padding:10px;font-family:Heading;}
.grid-container {display: grid;grid-template-columns: auto auto auto auto;background-color:white;}
.grid-item{padding:20px;border-bottom: 1px solid WhiteSmoke;}
.heading{font-weight:bold;font-size:20px;font-family:Heading;background-color:WhiteSmoke;  position: -webkit-sticky; /* Safari */position: sticky;top: 0;}
.date{font-size:20px;}
@media only screen and (max-width: 1000px) {
	body{margin:0px;border:0px;padding:0px;}
	header img{width:40%;}
	h1{font-size:25px;}
	h2{font-size:20px;}
	.grid-item{font-size:13px;padding:15px;}
	.heading{font-size:16px;}
	.date{font-size:16px;}
}
</style>
<title>Race Event Viewer</title>
</head>
<body>
<header>
<img src="site_logo3.png">
<h1>Race Event Viewer</h1>
</header>
<main>
<?php
function getName(){
	$conn = dbconnect();
	
	$sql = "SELECT Tracks.Name
	FROM Tracks 
	WHERE Tracks.Active = 1";  
	
	$getName = sqlsrv_query($conn, $sql);  
	if ($getName == FALSE) {
		echo "<p>die</p>";
		die();
	}

	echo "<h2>Track : ";
	while($row = sqlsrv_fetch_array($getName, SQLSRV_FETCH_ASSOC))  
	{  
		echo $row["Name"]; 
	}  
	echo "</h2>";
}
?>

<?php    
function printlogs($date = null)  
{  
	$conn = dbconnect();

	if($date){
		$endDate = substr($date, 8, 2);
		$month = substr($date, 5, 2);
		
		if($month == "01" && $endDate == "31"){
			$endDate = "02-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "02" && $endDate == "28"){
			$endDate = "03-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "03" && $endDate == "31"){
			$endDate = "04-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "04" && $endDate == "30"){
			$endDate = "05-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "05" && $endDate == "31"){
			$endDate = "06-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "06" && $endDate == "30"){
			$endDate = "07-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "07" && $endDate == "31"){
			$endDate = "08-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "08" && $endDate == "31"){
			$endDate = "09-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "09" && $endDate == "30"){
			$endDate = "10-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "10" && $endDate == "31"){
			$endDate = "11-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "11" && $endDate == "30"){
			$endDate = "12-01";
			$endDate = substr_replace($date, $endDate, 5);
		}else if($month == "12" && $endDate == "31"){
			$year = substr($date, 0, 4);
			$year++;
			$endDate = $year . "-01-01";
		}else{
			$endDate++;
			$endDate = substr_replace($date, $endDate, 8, 10);
		}
		$date = "'".$date . "'";
		$sql = "SELECT TOP 950 LogEventLevels.EventLevelDesc, EventLog.TimeStamp, Tracks.Name, EventLog.elEvent, EventLog.EventDetails 
		FROM EventLog 
		INNER JOIN LogEventLevels ON EventLog.EventLevel = LogEventLevels.EventLevelID
		INNER JOIN Tracks ON EventLog.TrackID = Tracks.ID
		WHERE Tracks.Active = 1 AND (TimeStamp > ".$date." AND TimeStamp < '".$endDate."')
		ORDER BY EventLog.TimeStamp DESC";  
	}else{
		$sql = "SELECT TOP 950 LogEventLevels.EventLevelDesc, EventLog.TimeStamp, Tracks.Name, EventLog.elEvent, EventLog.EventDetails 
		FROM EventLog 
		INNER JOIN LogEventLevels ON EventLog.EventLevel = LogEventLevels.EventLevelID
		INNER JOIN Tracks ON EventLog.TrackID = Tracks.ID
		WHERE Tracks.Active = 1
		ORDER BY EventLog.TimeStamp DESC";  
	}
	
	$getEvents = sqlsrv_query($conn, $sql);  
	if ($getEvents == FALSE) {
		echo "<p>die</p>";
		die();
	}

	$eventCount = 0;
	while($row = sqlsrv_fetch_array($getEvents, SQLSRV_FETCH_ASSOC))  
	{  
		echo "<div class='grid-item'>" . $row["TimeStamp"]->format('Y-m-d H:i:s'). "</div><div class='grid-item'>" . $row["elEvent"]. "</div><div class='grid-item'>" .$row["EventDetails"]. "</div><div class='grid-item'>" .$row["EventLevelDesc"].  "</div>";
		$eventCount++;  
	}  
	
//	$sql = "SELECT TOP 500 LogEventLevels.EventLevelDesc, EventLog.TimeStamp, EventLog.TrackID, EventLog.SourceID, EventLog.elEvent, EventLog.EventDetails 
//	FROM EventLog 
//	INNER JOIN LogEventLevels ON EventLog.EventLevel = LogEventLevels.EventLevelID
//	WHERE EventLog.TrackID = 0
//	ORDER BY EventLog.TimeStamp DESC";  
	
//	$getEvents = sqlsrv_query($conn, $sql);  
//	if ($getEvents == FALSE) {
//		echo "<p>die</p>";
//		die();
//	}

//	while($row = sqlsrv_fetch_array($getEvents, SQLSRV_FETCH_ASSOC))  
//	{  
//		$source = (!$row["SourceID"]) ? 'Race Control' : $row["SourceID"];
//		$track = (!$row["TrackID"]) ? 'System' : $row["TrackID"];
//		echo "<div class='grid-item'>" . $row["TimeStamp"]->format('Y-m-d H:i:s'). "</div><div class='grid-item'>" . $track . "</div><div class='grid-item'>" .$source.
//		"</div><div class='grid-item'>" . $row["elEvent"]. "</div><div class='grid-item'>" .$row["EventLevelDesc"]. "</div><div class='grid-item'>" .$row["EventDetails"].  "</div>";
//		$eventCount++;  
//	} 
	
	
	
	echo "<p>".$eventCount." records displayed</p>";
	sqlsrv_free_stmt($getEvents);  
	sqlsrv_close($conn);   
}
?>
<?php
getName();
?>
<div class="grid-container">
<div class="grid-item heading">Time : <form action="" method="GET"><input type="date" id="date" name="date" onchange="this.form.submit()"/></form><?php if (isset($_GET["date"])){echo "<div class='date'>".$_GET["date"]."</div>";}?></div><div class="grid-item heading">
Event</div><div class="grid-item heading">Event Details</div><div class="grid-item heading">Event Level</div>
<?php
if(!isset($_GET["date"])){
	printlogs();
}else{
	printlogs($_GET["date"]);
}
?>
</div>
</main>
</body>
</html>