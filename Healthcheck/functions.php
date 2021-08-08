<?php
$server = '192.168.75.132';
$wsport = 25010;
//$dbserver = "192.168.75.12";
$apacheport = '8000';
$sitefolder = 'Healthcheck';

define("HIGHEST_LICENCE_NUM", 31);
date_default_timezone_set('Australia/Brisbane');

function dbconnect_logs(){
	$serverName = "192.168.75.12";
	$connectionOptions = array("Database"=>"AlitraxGlobal",  
		"Uid"=>"AlitraxGlobal", "PWD"=>"ticaBocca685");  
	$conn = sqlsrv_connect($serverName, $connectionOptions);  
	if($conn == false){  
		echo "<p>connection died</p>";
		die();
	}	
	return $conn;
}

function dbconnect_keys(){
	$serverName = "192.168.75.12";
	$connectionOptions = array("Database"=>"InstallKey",  
		"Uid"=>"AlitraxGlobal", "PWD"=>"ticaBocca685");  
	$conn = sqlsrv_connect($serverName, $connectionOptions);  
	if($conn == false){  
		echo "<p>connection died</p>";
		die();
	}	
	return $conn;
}

function printlogs($facilityID = 0, $date = null)  
{  
	$conn = dbconnect_logs();

	if($date){
		
		$endDate = endDateGen($date);

		$sql = "SELECT TOP 950 TimeStamp, TrackID, EventLevel, EventDetails, elEvent  
		FROM GlobalEventLog 
		WHERE FacilityID = ".$facilityID." AND (TimeStamp > '".$date."' AND TimeStamp < '".$endDate."') 
		ORDER BY TimeStamp DESC";  
	}else{
		$sql = "SELECT TOP 950 TimeStamp, TrackID, EventLevel, EventDetails, elEvent  
		FROM GlobalEventLog 
		WHERE FacilityID = ".$facilityID."
		ORDER BY TimeStamp DESC";  
	}
	
	$getEvents = sqlsrv_query($conn, $sql);  
	if ($getEvents == FALSE) {
		echo "<p>print logs query died</p>";
		die();
	}

	$eventCount = 0;
	while($row = sqlsrv_fetch_array($getEvents, SQLSRV_FETCH_ASSOC))  
	{  
		echo "<div class='grid-item'>" . $row["TimeStamp"]->format('Y-m-d H:i:s'). "</div><div class='grid-item trackid'>" .$row["TrackID"].
		"</div><div class='grid-item eventlevel'>" . $row["EventLevel"]. "</div><div class='grid-item details'>" .$row["EventDetails"]. "</div><div class='grid-item'>" .$row["elEvent"].  "</div>";
		$eventCount++;  
	}  
	 
	echo "<p>".$eventCount." records displayed</p>";
	sqlsrv_free_stmt($getEvents);  
 

	if($facilityID){
		events_graph($facilityID, $conn);
	}
	
	print_connection_info($conn);
	
	sqlsrv_close($conn); 
}

function printkeys($facilityID = 0)  
{  
	$conn = dbconnect_keys();
	
	$sql = "SELECT PKSureKey, CustomerName  
	FROM Surekey";
	
	$getEvents = sqlsrv_query($conn, $sql);  
	if ($getEvents == FALSE) {
		echo "<p>print keys query died</p>";
		die();
	}

	echo "<option value=0>Global</option>";
	while($row = sqlsrv_fetch_array($getEvents, SQLSRV_FETCH_ASSOC))  
	{  
		$selected = ($facilityID == $row["PKSureKey"]) ? 'Selected' : '';
		echo "<option value='" . $row["PKSureKey"]. "'".$selected.">" . $row["CustomerName"]. "</option>";
	}  
	
	sqlsrv_free_stmt($getEvents);  
	sqlsrv_close($conn);   
}

function endDateGen($date){
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
	
	return $endDate;
}

function calendarCheck($date){
	$day = substr($date, 8, 2);
	$month = substr($date, 5, 2);
	$prevDate = "";
	
	if($day == "01"){
		if($month == "01"){
			$year = substr($date, 0, 4);
			$year--;
			$prevDate = $year . "-12-31";
		}else if($month == "02"){
			$prevDate = "01-31";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "03"){
			$prevDate = "02-28";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "04"){
			$prevDate = "03-31";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "05"){
			$prevDate = "04-30";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "06"){
			$prevDate = "05-31";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "07"){
			$prevDate = "06-30";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "08"){
			$prevDate = "07-31";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "09"){
			$prevDate = "8-31";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "10"){
			$prevDate = "09-30";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "11"){
			$prevDate = "10-31";
			$prevDate = substr_replace($date, $prevDate, 5);
		}else if($month == "12"){
			$prevDate = "11-30";
			$prevDate = substr_replace($date, $prevDate, 5);
		}
	}else{
		$prevDate = --$day;
		$prevDate = substr_replace($date, $prevDate, 8, 10);
	}

	return $prevDate;
}

function previousDayGen($date, $steps){
	if($steps == 0){
		return calendarCheck($date);
	}else{
		$steps++;
		$previousDayGen(calendarCheck($date), $steps);
	}
}

function print_connection_info($conn){
	$server_info = sqlsrv_server_info( $conn);
	if( $server_info )
	{
		foreach( $server_info as $key => $value) {
		   echo $key.": ".$value."<br />";
		}
	} else {
		  die( print_r( sqlsrv_errors(), true));
	}
	
	if( $client_info = sqlsrv_client_info( $conn)) {
    foreach( $client_info as $key => $value) {
        echo $key.": ".$value."<br />";
    }
	} else {
		echo "Error in retrieving client info.<br />";
	}
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function events_graph($facilityID, $conn){
	echo "<script type='text/javascript'>";

	echo "google.charts.load('current', {'packages':['corechart']});";
	echo "google.charts.setOnLoadCallback(drawChart);";
	
	echo "function drawChart() {";
	echo "  var data = google.visualization.arrayToDataTable([";
	echo "  ['Day', 'Events'],";
	
	for($i = 0; $i < 7; $i++){		
		$day = date('d');
		$day = $day - $i;
		$eventDate = "";
		$firstDate = date('Y-m-d');
		$firstDate = substr_replace($firstDate, 01, 8);	

		if($day <= 0 && $day >= -6){
			$eventDate = previousDayGen($firstDate, $day);
		}else{
			$eventDate = date('Y-m-d');
			$eventDate = substr_replace($eventDate, $day, 8);	
		}
		
		$endDate = endDateGen($eventDate);
		
		$sql = "SELECT COUNT(*)  
		FROM GlobalEventLog 
		WHERE FacilityID = ".$facilityID. " AND (TimeStamp > '".$eventDate."' AND TimeStamp < '".$endDate."')"; 
		$getCount = sqlsrv_query($conn, $sql);
		$count = sqlsrv_fetch_array($getCount, SQLSRV_FETCH_NUMERIC);	
		sqlsrv_free_stmt($getCount);
		
		if($i != 6){
			echo "  ['".$eventDate."', ".$count[0]."],";
		}else{
			echo "  ['".$eventDate."', ".$count[0]."]";
		}
	}
	
	echo "]);";

	echo "var options = {'title':'Daily Events - 7 days'};";

	echo "var chart = new google.visualization.BarChart(document.getElementById('barchart'));";
	echo "chart.draw(data, options);}";
	echo "</script>";
}

function print_status(){
	$conn = dbconnect_keys();
	$tracks = array();
	$times = array();
	$tracktimes = array();
	$status = "";
	
	$sql = "SELECT PKSureKey, CustomerName  
	FROM Surekey";
	
	$getTracks = sqlsrv_query($conn, $sql);  
	if ($getTracks == FALSE) {
		echo "<p>print status query died</p>";
		die();
	}
	
	while($row = sqlsrv_fetch_array($getTracks, SQLSRV_FETCH_ASSOC)){
		$tracks[$row["PKSureKey"]] = $row["CustomerName"];
	}

	sqlsrv_free_stmt($getTracks);  
	
	$conn = dbconnect_logs();
	
	for($i = 1; $i < HIGHEST_LICENCE_NUM; $i++){
		$sql = "SELECT TOP(1) TimeStamp  
		FROM GlobalEventLog
		WHERE FacilityID = " . $i ."
		ORDER BY TimeStamp DESC";
		
		$getTimes = sqlsrv_query($conn, $sql);  
		if ($getTimes == FALSE) {
			echo "<p>print status query died</p>";
			die();
		}
		
		while($row = sqlsrv_fetch_array($getTimes, SQLSRV_FETCH_ASSOC)){
			$times[$i] = $row["TimeStamp"]->format('Y-m-d H:i:s'); 
		}  
		sqlsrv_free_stmt($getTimes); 
	}
	
	sqlsrv_close($conn); 
	
	foreach($tracks as $key => &$track){
		$tracktimes[$track] = (array_key_exists($key, $times) ? $times[$key] : "");
	}
	
	$status = "<div class='status-container'>";
	foreach($tracktimes as $track => &$time){
		$temp = substr($time, 0, 10);
		$class = "";
		if($temp == date("Y-m-d")){
			$class = "today";
		}
		$status .= "<div class='status-item'>" . $track . " : ";
		$status .= "<span class = 'errordate ".$class."'>".$time."</span>";
		$status .= "</div>";
	}
	$status .= "</div>";
	// echo $status;
	return $status;
}
?>