<?php
include 'functions.php';

$host = $server;
$port = $wsport;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, $host, $port);
socket_listen($socket);

$socketconnected = 0;
while (true) {
	$client = socket_accept($socket);

	$request = socket_read($client, 5000);
	echo $request;
	preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
	$key = base64_encode(pack(
		'H*',
		sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
	));
	$headers = "HTTP/1.1 101 Switching Protocols\r\n";
	$headers .= "Upgrade: websocket\r\n";
	$headers .= "Connection: Upgrade\r\n";
	$headers .= "Sec-WebSocket-Version: 13\r\n";
	$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
	socket_write($client, $headers, strlen($headers));
	echo $headers;
	
	while($socketconnected == 0){
		$content = print_status();

		//$response = chr(129) . chr(strlen($content)) . $content;
		$response = $content;

		for($i = 0; $i < strlen($response); $i++){
			
			echo $response[$i];
			$temp = chr(129) . chr(strlen($response[$i])) . $response[$i];
			socket_write($client, $temp);
			//sleep(.5);
		}
		$temp = chr(129) . chr(strlen("!")) . "!";
		socket_write($client, $temp);
		
		$socketconnected = socket_last_error($client);
		if($socketconnected == 10053){
			socket_close($client);
		}
		sleep(10);
	}
	$socketconnected = 0;
}
socket_close($client);
socket_close($socket);
?>
