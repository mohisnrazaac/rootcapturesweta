<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Chat</title>
    <script>
        var socket = new WebSocket('wss://rootcapture.com/:9000');

        socket.onopen = function() {
            console.log('Connected to the server.');
        };

        socket.onmessage = function(event) {
            var message = event.data;
            console.log('Received message: ' + message);
        };

        socket.onclose = function() {
            console.log('Connection closed.');
        };

        // Send a message to the server
        socket.send('Hello, server!');
    </script>
</head>
<body>
</body>
</html>
