<?php
//$servername = "localhost";
//$username = "root";
//$password = "zdm2srav";

// Create connection
//$conn = new mysqli($servername, $username, $password);

// Check connection
//if ($conn->connect_error) {
//  die("Connection failed: " . $conn->connect_error);
//}
//echo "Connected successfully";
?>
<?php
$servername = "localhost";
$username = "root";
$password = "zdm2srav";

try {
  $conn = new PDO("mysql:host=$servername;dbname=GpsGateServer", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>