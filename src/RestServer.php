<?php 

namespace DaveRoss\RestServer;

if (!function_exists('socket_create')) {
    die('Are PHP socket extensions enabled?');
}

function restServer($port, callable $fn, $host = '127.0.0.1', $maxRequestSize = 9999) {

    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
    $result = socket_bind($socket, $host, intval($port)) or die("Could not bind to socket\n");
    $result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

    while (true) {

        $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");
        $input = socket_read($spawn, $maxRequestSize) or die("Could not read input\n");

        $restRequest = new RestRequest($input);

        if ('100-continue' === $restRequest->header('Expect')) {
            $input2 = socket_read($spawn, $restRequest->header('Content-Length')) or die("Could not read input\n");
            $restRequest->setPayload($input2, RestRequest::FORMAT_JSON);
        }

        $output = $fn($restRequest, new RestResponse());
        socket_write($spawn, json_encode($output), strlen($output) + 1) or die("Could not write output\n");

        socket_close($spawn);
        
    }

    socket_close($socket);
    
}
