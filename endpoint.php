<?php
declare(ticks=1);

// Server configuration
// Specify the network interface (e.g., eth0)
$networkInterface = 'eth0';
$client_count=0;
// Get the IP address for the specified network interface
$ipAddress = getIpAddress($networkInterface);

$port = 8999;
$host = "0.0.0.0"; // 0.0.0.0,allows connection from  any machine to access or specify the actual IP address
// Display server information
echo "PHP Server is running at http://$ipAddress:$port\n";

echo "Endpoints /status /heartbeat ";
echo "Listening for clients...";


// Create socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

// Register the signal handler
pcntl_signal(SIGTERM, "sigintHandler");

// Main server loop
while (true) {
    // Register a signal handler for Control+C (SIGINT)


    // Accept incoming connection
    $client = socket_accept($socket);

    // Fork a process to handle the request
    $pid = pcntl_fork();

     // write $client_count to a file
     $client_count++;
     file_put_contents('client_count.txt', $client_count); 

    if ($pid == -1) {
        die("Error forking process");
    } elseif ($pid == 0) {
        // Child process (handle request)
        if (@file_exists('client_count.txt')) {
            // read client_count from a file and assign it to $client_count variable
            $client_count = (int) file_get_contents('client_count.txt'); 
        
        }
        $client_count++;
      
        echo "Handling new client  $client_count .... ";
   
        $request = socket_read($client, 1024);
        $response = handleRequest($request);
        $bufsize=( strlen($response));
        socket_write($client, $response, $bufsize);
        echo "Response complete sent $bufsize bytes \n ";
        socket_close($client);
        exit();  //exit the request
    } else {
        // Parent process (close client socket)
        socket_close($client);
    }
}  //end wile looop

// Close the socket
socket_close($socket);
// Exit statement added to ensure termination
exit;

// Function to handle different endpoints
function handleRequest($request)
{
    // Parse the request
    $lines = explode("\r\n", $request);
    $firstLine = explode(" ", $lines[0]);
    $method = $firstLine[0];
    $endpoint = $firstLine[1];

  echo "Requested endpoint:$endpoint ";  
    // Switch case for different endpoints
    switch ($endpoint) {
        case '/file':
            $file_contents=file_get_contents("1mb.txt");
            $response = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\n.$file_contents\r\n\r\n";
            break;
        case '/status':
            $response = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\nServer Status: OK";
            break;
        case '/heartbeat':
            $response = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\nHeartbeat: Alive";
            break;
        default:
            $response = "HTTP/1.1 200 Ok\r\nContent-Type: text/plain\r\n\r\nEndpoint $endpoint not found";
            break;
    }

    return $response;
}

// Function to get the IP address for a specific network interface
function getIpAddress($interface)
{
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

    // Get the IP address associated with the specified network interface
    socket_bind($socket, 0, 0);
    socket_connect($socket, '8.8.8.8', 53); // Using a public DNS server for connection

    socket_getsockname($socket, $localAddress, $localPort);

    socket_close($socket);

    return $localAddress;
}

// Function to handle SIGINT (Control+C)
function sigintHandler() {
    global $socket;
        socket_close($socket); // Close each open socket
 // Simulate SIGTERM to trigger the handler
 posix_kill(getmypid(), SIGTERM);

    echo "ALL Sockets closed\n";
    exit(0); // Terminate the script
}
