function getSocket(server, port){
	var host = 'ws://' + server + ':' + port + '';
	var socket  = new WebSocket(host);
	var htmlString;
	socket.onmessage = function(e) {
		if(e.data == '!'){
			if(htmlString.substr(0,9) == 'undefined'){
				htmlString = htmlString.substr(9,htmlString.length);
			}
			document.getElementById('statussocket').innerHTML = htmlString;
			htmlString = '';
		}else{
			htmlString += e.data;
		}
	};
	socket.onclose = function(event) {
		document.getElementById('statussocket').innerHTML = 'Server connection lost';
		getSocket(server);
	};	
}