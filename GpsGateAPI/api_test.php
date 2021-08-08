<?php
function dbconnect(){
	$serverName = "192.168.75.12";  
	$connectionOptions = array("Database"=>"nautech",  
		"Uid"=>"sa", "PWD"=>"thatshowiroll");  
	$conn = sqlsrv_connect($serverName, $connectionOptions);  
	if($conn == false){  
		echo "<p>die</p>";
	}	
	return $conn;
}
function getName(){
	$conn = dbconnect();
	
	$sql = "SELECT TOP (100) [VehicleUID]
      ,[VehicleID]
      ,[ProgramID]
      ,[DistrictID]
      ,[AssetNumber]
      ,[PurchaseOrder]
      ,[ManfOrder]
      ,[ManfID]
      ,[VinNumber]
      ,[VStatusID]
      ,[ShipArrival]
      ,[ModelID]
      ,[FuelTypeID]
      ,[ColourID]
      ,[ClassificationID]
      ,[PurposeID]
      ,[FunctionID]
      ,[FitoutID]
      ,[MDT_AVL_ID]
      ,[LiveryID]
      ,[RoofNumber]
      ,[RequiredDate]
      ,[ProgrammedDelivery]
      ,[DateToNautech]
      ,[DateCompleted]
      ,[DateDespatched]
      ,[Registration]
      ,[ReplacesVehicle]
      ,[LocationsID]
      ,[DisposalDate]
      ,[PoliceCostCentre]
      ,[NautechJobNumber]
      ,[Comments]
      ,[Requested]
      ,[RequestedDate]
      ,[DespatchArranged]
      ,[DespatchReqDate]
      ,[ArrivalDocket]
      ,[PickupDocket]
      ,[Modified]
      ,[Inactive]
      ,[Delayed]
      ,[SimCard]
      ,[CurrentStage]
      ,[LTSANumber]
      ,[ContractCodeUID]
      ,[RadioCodeUID]
      ,[Entity]
      ,[Purpose]
      ,[LocationRequired]
      ,[InstalledBy]
      ,[RadioPack1]
      ,[RadioPack2]
      ,[RadioPack3]
      ,[RadioPack4]
      ,[RadioPack5]
      ,[RadioPack6]
      ,[RadioPack7]
      ,[RadioPack8]
      ,[RadioPack9]
      ,[RadioPack10]
      ,[UHFConfig]
      ,[Source]
      ,[VehicleCategory]
      ,[VehicleType]
      ,[MarkedUnmarked]
      ,[VehicleModel]
      ,[VehicleSeries]
      ,[VehicleMoreInfo]
      ,[OtherEquipmentImpact]
      ,[ManfDate]
      ,[LightBarMounting]
      ,[G20StorageLocation]
      ,[Notes]
      ,[RadioProfile]
      ,[MotorolaUniqueID]
      ,[CompletedByMotorola]
      ,[RegistrationDate]
      ,[NotRegisteredByNautech]
      ,[PartsUsedAtInstall]
      ,[InstallationAddition]
      ,[AdditionalHours]
      ,[AuthorizedExtras]
      ,[INCNumber]
      ,[ExpectedInstallTime]
      ,[OrderConfirmed]
      ,[DealerVehicleID]
      ,[RadioOnsite]
      ,[VehicleKitted]
      ,[AbelRadioJob]
      ,[CustomerID]
      ,[AdditionalDamage]
      ,[DamageReported]
      ,[PaperworkWithVehicle]
      ,[PartsInVehicle]
      ,[DeinstallKit]
      ,[WorkTypeID]
      ,[DeinstallOptions]
      ,[Odometer]
      ,[vSubStatusID]
      ,[VehicleAccepted]
      ,[RITM]
      ,[Keytag]
      ,[ANPR]
      ,[ANPRAbelJob]
      ,[CallSign]
      ,[RadioModNumber]
      ,[LightStyle]
      ,[SinageStyle]
      ,[Agency]
      ,[RadioPackRequested]
      ,[RadioPackOnsite]
      ,[AgencyID]
      ,[DateActualBuild]
      ,[InSLA]
      ,[SLANotes]
      ,[ANIUHF]
      ,[ANIVHF]
      ,[DateCustomerAccepted]
      ,[DateScheduledComplet]
  FROM [nautech].[dbo].[tblVehicle]
  ORDER BY [DateDespatched] DESC";  
	
	$getName = sqlsrv_query($conn, $sql);  
	if ($getName == FALSE) {
		echo "<p>die</p>";
		die();
	}
	echo "<div class='gridcontainer'>";
	while($row = sqlsrv_fetch_array($getName, SQLSRV_FETCH_ASSOC))  
	{  
		echo "<div class= 'griditem'>" . $row["VinNumber"] . "</div>"; 
	}
	echo "</div>";
}
?>
<html>
<head>
<style>
body{font-family:Arial;}
.gridcontainer{display: grid;grid-template-columns: auto auto auto auto auto;border: solid 1px black;}
.griditem{padding:10px;}
.username{font-weight:bold;}
.odometer{color:red;}
</style>
</head>
<body>
<!--			for(var i = 0; i < jsonObj.variables.length; i++){
				if(jsonObj.variables[i].name == "OdometerAcc"){
					document.getElementById("demo").innerHTML += "<div class='griditem'>" + jsonObj.variables[i].name + " " + jsonObj.variables[i].value + "</div>";
				}
			}
-->
<button type="button" onclick="getToken()">Get Data With Token</button>
<div id="demo" class="gridcontainer">

</div>
</body>

<script>
setInterval(
function() {
	document.getElementById("demo").innerHTML = '';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200){
			var jsonObj = JSON.parse(this.responseText);
			for(var i = 0; i < jsonObj.length; i++){
				if(jsonObj[i].variables[0] != null){
					document.getElementById("demo").innerHTML += "<div class='griditem username'>" + jsonObj[i].username + "</div>";
					for(var j = 0; j < jsonObj[i].variables.length; j++){
						if(jsonObj[i].variables[j].name.search("Odometer") != -1){
							document.getElementById("demo").innerHTML += "<div class='griditem odometer'>" + jsonObj[i].variables[j].name + " " + jsonObj[i].variables[j].value + "</div>";
						}else{
							document.getElementById("demo").innerHTML += "<div class='griditem'>" + jsonObj[i].variables[j].name + " " + jsonObj[i].variables[j].value + "</div>";
						}
					}
				}
			}
		}
	}
	xmlhttp.open("GET", "http://192.168.75.18/comGpsGate/api/v.1/applications/4/usersstatus", true);
	xmlhttp.setRequestHeader('Authorization', 'wrHmFvMJN8ClE1orPWqf2Y3qXAFCQFi%2bvBk8TQGGzodvZ7%2bzuG4yWd4TCP7q82HP');
	xmlhttp.send();
}, 10000);

</script>

<!--<script>
var data = [
   ['Foo', 'programmer'],
   ['Bar', 'bus driver'],
   ['Moo', 'Reindeer Hunter']
];
 
 
function download_csv() {
    var csv = 'Name,Title\n';
    data.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
    });
 
    console.log(csv);
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = 'people.csv';
    hiddenElement.click();
}
</script>
 
<button onclick="download_csv()">Download CSV</button> -->
<?php
getName();
?>
</html>