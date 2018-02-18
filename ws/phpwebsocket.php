<?php  
/***************************************************************
*  phpWebSocket: modified PHP-Websockets code to handle websockets
* 
*  original source: https://github.com/ghedipunk/PHP-WebSockets
*  this modified source: http://www.abrandao.com  
*
*/
// Usage: $master=new WebphpWebSocketSocket("localhost",12345);

class phpWebSocket{
  var $master;
  var $sockets = array(); //create an array of socket objects 
  var $users   = array(); //create an array of users objects to handle discussions with users
  var $debug   = false;
  
  function ascii_banner() //just for old-skool fun...
  {
	$banner="               _    ____             _        _   \n";
	$banner.=" __      _____| |__/ ___|  ___   ___| | _____| |_\n ";
	$banner.="\ \ /\ / / _ \ '_ \___ \ / _ \ / __| |/ / _ \ __|\n";
	$banner.="  \ V  V /  __/ |_) |__) | (_) | (__|   <  __/ |_ \n";
	$banner.="   \_/\_/ \___|_.__/____/ \___/ \___|_|\_\___|\__|\n";
	return $banner;
                                                 
  }
  
  function __construct($address,$port){
  // error_reporting(E_ALL);
    set_time_limit(0);
    ob_implicit_flush();

    $this->master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)     or die("socket_create() failed");
    socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1)  or die("socket_option() failed");
    socket_bind($this->master, $address, $port)                    or die("socket_bind() failed");
    socket_listen($this->master,20)                                or die("socket_listen() failed");
    $this->sockets[] = $this->master;

	$this->say($this->ascii_banner() );
	$this->say("PHP WebSocket Server running....");
    $this->say("Server Started : ".date('Y-m-d H:i:s'));
    $this->say("Listening on   : ".$address." port ".$port);
    $this->say("Master socket  : ".$this->master."\n");
	$this->say(".... awaiting connections ...");
	
	// Main Server processing loop
    while(true)  //server is always listening
	{
      $changed = $this->sockets;
      socket_select($changed,$write=NULL,$except=NULL,NULL);
	  $this->say("listening...\n");
      foreach($changed as $socket)
	  {

        if($socket==$this->master){
          $client=socket_accept($this->master);
          if($client<0){ $this->log("socket_accept() failed"); continue; }
          else{ $this->connect($client); }
        }
        else{
          $bytes = @socket_recv($socket,$buffer,2048,0);
          if($bytes==0)
		    { 
			 $this->disconnect($socket); 
			 }
          else{
            $user = $this->getuserbysocket($socket);
            if(!$user->handshake)
			  { 
			  $this->say("Handshaking $user");
			  $this->dohandshake($user,$buffer);
 			  }
            else
			 { 
			 $this->process($user,$this->frame_decode($buffer) ); 
			 } 
          }
        }
      } //foreach socket
    } //main loop
  } //function

  function process($user,$msg){
    /* Extend and modify this method to suit your needs */
    /* Basic usage is to echo incoming messages back to client */
     $this->send($user->socket,$msg);
  }
  
  
  function send_pong($user)
  {
   $bytesHeader[0] = 138; // 1xA Pong frame (FIN + opcode)
   $msg = implode(array_map("chr", $bytesHeader)) ;
   $this->send($user->socket,$msg);
  }
  
  /**
 * Encode a text for sending to clients via ws://
 * @param $message
 * WebSocket frame 
 
+-+-+-+-+-------+-+-------------+-------------------------------+
0                   1                   2                   3
0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1
+-+-+-+-+-------+-+-------------+-------------------------------+
|F|R|R|R| opcode|M| Payload len |    Extended payload length    |
|I|S|S|S|  (4)  |A|     (7)     |             (16/64)           |
|N|V|V|V|       |S|             |   (if payload len==126/127)   |
| |1|2|3|       |K|             |                               |
+-+-+-+-+-------+-+-------------+ - - - - - - - - - - - - - - - +
|     Extended payload length continued, if payload len == 127  |
+ - - - - - - - - - - - - - - - +-------------------------------+
|                               |Masking-key, if MASK set to 1  |
+-------------------------------+-------------------------------+
| Masking-key (continued)       |          Payload Data         |
+-------------------------------- - - - - - - - - - - - - - - - +
:                     Payload Data continued ...                :
+ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - +
|                     Payload Data continued ...                |
+---------------------------------------------------------------+
 */
function frame_encode($message) {

    $length = strlen($message);

    $bytesHeader = [];
    $bytesHeader[0] = 129; // 0x1 text frame (FIN + opcode)

    if ($length <= 125) {
            $bytesHeader[1] = $length;
    } else if ($length >= 126 && $length <= 65535) {
            $bytesHeader[1] = 126;
            $bytesHeader[2] = ( $length >> 8 ) & 255;
            $bytesHeader[3] = ( $length      ) & 255;
    } else {
            $bytesHeader[1] = 127;
            $bytesHeader[2] = ( $length >> 56 ) & 255;
            $bytesHeader[3] = ( $length >> 48 ) & 255;
            $bytesHeader[4] = ( $length >> 40 ) & 255;
            $bytesHeader[5] = ( $length >> 32 ) & 255;
            $bytesHeader[6] = ( $length >> 24 ) & 255;
            $bytesHeader[7] = ( $length >> 16 ) & 255;
            $bytesHeader[8] = ( $length >>  8 ) & 255;
            $bytesHeader[9] = ( $length       ) & 255;
    }

	 //apply chr against bytesHeader , then prepend to message
    $str = implode(array_map("chr", $bytesHeader)) . $message;
    return $str;
} 
 
 /**
 * frame_decode (decode data frame)  a received payload (websockets)
 * @param $payload  (Refer to: https://tools.ietf.org/html/rfc6455#section-5 )
 */
 function frame_decode($payload) 
 {
	if (!isset($payload))
		return null;  //empty data return nothing

    $length = ord($payload[1]) & 127;

    if($length == 126) {
        $masks = substr($payload, 4, 4);
        $data = substr($payload, 8);
    }
    elseif($length == 127) {
        $masks = substr($payload, 10, 4);
        $data = substr($payload, 14);
    }
    else {
        $masks = substr($payload, 2, 4);
        $data = substr($payload, 6);
    }

	for ($i = 0; $i < strlen($masks); ++$i) {
	  $this->say("header[".$i."] =". ord($masks[$i]). " \n");
	}
	 //$this->say(" data:$data \n");
	 
	 //did we just get a PING frame
	 if (strlen($masks)==4 && strlen($data)==0) 
	 {
	  return "ping";
	  }
	
    $text = '';
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i%4];
    }
    return $text;
}  //end of frame_decode unmask(Received from client)



  function send($client,$msg){ 
    $msg = $this->frame_encode($msg);
    socket_write($client, $msg);
    $this->say("> ".$msg." (".strlen($msg). " bytes) \n");
  } 

  function connect($socket){
    $user = new User();
    $user->id = uniqid();
    $user->socket = $socket;
    array_push($this->users,$user);
    array_push($this->sockets,$socket);
    $this->say($socket." CONNECTED!");
  
  }

  function disconnect($socket){
    $found=null;
    $n=count($this->users);
    for($i=0;$i<$n;$i++){
      if($this->users[$i]->socket==$socket){ $found=$i; break; }
    }
    if(!is_null($found))
	{ 
	array_splice($this->users,$found,1); 
	}
    $index=array_search($socket,$this->sockets);
    socket_close($socket);
	$this->say(" DISCONNECTED!  User count:".count( $this->users));
    if($index>=0)
		{ 
		array_splice($this->sockets,$index,1); 
		}
  }

   function calcKey($key1,$ws_magic_string)
   {
    $this->log("\n Calculating sec-key: [".$key1."] \n MagicString:".$ws_magic_string."\n");
    return base64_encode(SHA1($key1.$ws_magic_string,true));
  }
   
  
  function dohandshake($user,$buffer){
    $this->say("\nWS Requesting handshake...");
    list($resource,$host,$origin,$key1,$key2,$l8b) = $this->getheaders($buffer);
   	$ws_magic_string="258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
	//Calculate Accept = base64_encode( SHA1( key1 +attach magic_string ))
	 $accept=$this->calcKey($key1,$ws_magic_string);
    
		/*
			Respond only when protocol specified in request header
			"Sec-WebSocket-Protocol: chat" . "\r\n" .
			*/
	$upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
                   "Upgrade: websocket\r\n".
                   "Connection: Upgrade\r\n".
				    "WebSocket-Location: ws://" . $host . $resource . "\r\n" .
					"Sec-WebSocket-Accept: $accept".
                   "\r\n\r\n";
					
    socket_write($user->socket,$upgrade);
	$this->say("Issuing websocket Upgrade \n");
    $user->handshake=true;
  
    $this->say("Done handshaking... User count:".count( $this->users));
    return  $user->handshake;
  }
  

  function getheaders($req){
    $r=$h=$o=null;
    if(preg_match("/GET (.*) HTTP/"               ,$req,$match)){ $r=$match[1]; }
    if(preg_match("/Host: (.*)\r\n/"              ,$req,$match)){ $h=$match[1]; }
    if(preg_match("/Origin: (.*)\r\n/"            ,$req,$match)){ $o=$match[1]; }
    if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/",$req,$match)){ $this->say("WebSocket-Key: ".$sk1=$match[1]); }
    if(preg_match("/Sec-WebSocket-Version: (.*)\r\n/",$req,$match)){ $this->say("WebSocket-Version: ".$sk2=$match[1]); }
    if($match=substr($req,-8)) 
	{ $this->log("Last 8 bytes: ".$l8b=$match); }
    return array($r,$h,$o,$sk1,$sk2,$l8b);
  }

  //Search for a particular user's socket
  function getuserbysocket($socket){
    $found=null;
    foreach($this->users as $user){
      if($user->socket==$socket)
		  { 
		  $found=$user; 
		  break; 
		  }
    }
    return $found;
  }

   //utility functions
  function say($msg=""){ echo $msg."\n"; } //display server console messages
  function log($msg=""){ if($this->debug){ echo $msg."\n"; } }

}  //end class WebSocket

//User class holds basic user identifying information
class User{
  var $id;
  var $socket;
  var $handshake;
  
   function __construct()
   {    //do stuff to initialize each user  
   }
  
	public function __toString()
	{	 return "(User: ". $this->id." )"; 	}
	
	
}  //end of class User

?>
