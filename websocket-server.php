<?php
$host = '127.0.0.1';
$port = 8001;

$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($server, $host, $port);
socket_listen($server);

echo "WebSocket server started at ws://$host:$port\n";

while (true) {
  $client = socket_accept($server);

  $request = @socket_read($client, 1024);

  $headers = "HTTP/1.1 101 Switching Protocols\r\n";
  $headers .= "Upgrade: websocket\r\n";
  $headers .= "Connection: Upgrade\r\n";
  $headers .= "Access-Control-Allow-Origin: http://localhost:3002\r\n";
  $headers .= "Access-Control-Allow-Methods: GET, POST\r\n";
  $headers .= "Access-Control-Allow-Headers: Content-Type\r\n";
  $headers .= "Access-Control-Allow-Credentials: true\r\n";

  socket_write($client, $headers, strlen($headers));

  while (true) {
    $message = socket_read($client, 1024);
    if ($message) {
      echo "Message from client: $message\n";
    } else {
      break;
    }
  }

  socket_close($client);
}

socket_close($server);
