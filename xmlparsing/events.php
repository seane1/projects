<?php
$xml = simplexml_load_file("events.xml") or die("Error: Cannot create object");
for($i = 0; $i < 1000; $i++){
	if($xml->Event[$i]->RenderingInfo->Level == "Error"){
		echo $xml->Event[$i]->RenderingInfo->Message . "<br>";
	}
}
?>