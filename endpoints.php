<?php
/****************************************************************************
#     
# Filename: endpoints.php
#
# Description:A simple PHP driven that handles a couple of /endpoints usefuly for running as 
# a seperate process to for API Purposes.
# Created by: Tony Brandao <abrandao29@gmail.com>, Jan 03, 2024
# Last update: May 03, 2019 by Tony Brandao <abrandao29@gmail.com>
#
****************************************************************************/

//TODO Tony B. more port and host info into settings.ini
// Set the ip and port we will listen on BE SURE to have the PORT exposed in the Dockerfile
// Not possible to use in Azure Web app as ONLY TWO ports are exposed 80:443
$port = 8083;
$host = "0.0.0.0"; // 0.0.0.0,allows connection from  any machine to access or specify the actual IP address
$http_server=null; // holds the URL of the http websserver , setup in the banner
$httpd_isRunning=false; // holds status of http status server

// Handle incoming HTTP requests for /heartbeat /status requests
//Not possible run in Azure web app environemnt
if ($httpd_isRunning===false)  //only run this one time to bind to listening socket
{
    $httpd_isRunning=true;   //set the state to true so it doesnt try to trigger this code again
    // Display server information
    $domain= gethostbyaddr(gethostname()); //determine which URL to show
    $http_server= "http://{$domain}:{$port}";
    $httpstatus.= "HTTP Server for SQL Dispatcher is running at  $http_server \n";  //TODO have it display actual IP address
    $httpstatus.="Overall status (HTML)   : $http_server/status \n";
    $httpstatus.="hearbeat (JSON)         : $http_server/hearbeat \n";
    $httpstatus.="snowflake status (JSON) : $http_server/snowflake \n";
    $httpstatus.="------------------------------------------------------ \n";    
    $httpstatus.="Listening for clients... \n";

    echo $httpstatus;

    $pidd = pcntl_fork();  //fork out the HTTP server to handle responses on its own.
    if ($pidd === -1) {
        die("Failed to fork HTTP Ednpoint daemon \n");
    } elseif ($pidd) {
        $childProcesses[$pidd] = true;  //keep track of the PID to remove them when process is killed
        HttpRequests(); //Monitor HTTP for requests this function is forked
        exit(0);  //exit the parent process
    }
}


/**********************************************************************
 *   HttpRequests   Is the main http service that listens for connections on $port 
 *  and forks new listeners to handle them
*/
function  HttpRequests() {

    global $host,$port,$childProcesses;
    $client_count=0;
    $httpstatus=null;


  
 // Try to bind and establihs a socket Create socket
 try {
 $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
 socket_bind($socket, $host, $port);
 socket_listen($socket);
 }
 catch (Exception $e)
 {
    echo "httpd Socket Error ".$e->getMessage();
    $httpstatus=false;
     socket_close($socket); // Close the socket
    exit(0);  // Exit this forked process
 }
 
 // Main HTTP server  loop
 while (true) {
     // Accept incoming connection
     $client = socket_accept($socket);
 
     // Fork a process to handle the request
     $pid = pcntl_fork();
  
      // write $client_count to a file, just for testing TODO , remove when done
      $client_count++;
      file_put_contents('client_count.txt', $client_count); 
       
     if ($pid == -1) {
         die("Error forking process");
     } elseif ($pid == 0) {
         // Child process (handle request)
         $childProcesses[$pid] = true;

         if (@file_exists('client_count.txt')) {
             // read client_count from a file and assign it to $client_count variable
             $client_count = (int) file_get_contents('client_count.txt'); 
         
         }
         $client_count++;
       
         echo "Handling new client  $client_count ....\n ";
    
         $request = socket_read($client, 1024);
         $response = handleEndPointRequest($request);  //Now check if we have specific endpoints
         $bufsize=( strlen($response));
         socket_write($client, $response, $bufsize);
         
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
 exit(0);
  
 }
 

  /**********************************************************************
 *   handleEndPointRequest   Provides responses to various http endpoint requests
 *   /status   : shows an HTML page with contents of SqlStatus() function
 *  /heartbeat :  returns a json object heartbeat['running']
 *  /snowflake : returns a json objec snowflake connection
 * /sqlite : returns a json objec sqlite connection
*/
 function handleEndPointRequest($request)
 {
     global $current_dir,$SnowDBConnectionStatus ;
 
     $response=null;
 
     // Parse the request
     $lines = explode("\r\n", $request);
     $firstLine = explode(" ", $lines[0]);
     $method = $firstLine[0];
     $endpoint = $firstLine[1];
    
   
     //Headers to avoid CORS errors
     $headers="Access-Control-Allow-Origin: *\r\nAccess-Control-Allow-Methods: POST, GET, OPTIONS\r\nAccess-Control-Allow-Headers: Content-Type\r\n" ;

     // Switch case for different endpoints
     switch ($endpoint) {
 
         case '/status':
                 $status="Status Code would go here....";
                 $html="<h2>SQL Endpoint live status </h2> <pre>$status</pre>   ";
                 $response  = "HTTP/1.1 200 OK\r\n".$headers."Content-Type: text/html\r\n\r\n" . $html;
             break;

         case '/heartbeat':
            //  $response = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\nHeartbeat: Alive";
             $response_json = json_encode(array( 'heartbeat' => 'Running', 'time' => date('h:i:s') ) );  //json response
             //header(' Header set Access-Control-Allow-Origin "*" ');
             $response ="HTTP/1.1 200 OK\r\n". $headers."Content-Type: application/json;\r\n\r\n".$response_json;
             break;
        
        //add additional endpoints as needed.
    
         default:
             $response = "HTTP/1.1 404 Ok\r\nContent-Type: text/plain\r\n\r\nEndpoint $endpoint not found";
             break;
     }
 
     return $response;
 }
 
 ?>