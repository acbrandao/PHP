#!/php -q
<?php  
// Run from command prompt > php -q ws_server.php
include "phpwebsocket.php";

$server_ip="192.168.1.200";  //what is the IP of your server

// Extended basic WebSocket as ws_server
class ws_server extends phpWebSocket{


  //Overridden process function from websocket.class.php
  function process($user,$msg){
    $c=0;
  	$this->say("(user: ".$user->id.") msg> ".$msg);
    //$this->say("< ".$msg);
	
    switch($msg){
	  case "ping" :  $this->send($user->socket,"pong"); break; //heartbeat frame reply with pong
      case "hello" : $this->send($user->socket,"hello human");                       break;
      case "name"  : $this->send($user->socket,"My Name is".php_uname("n") ); 		break;
	  case "temp"  : $this->send($user->socket,"Temp. in NYC:".$this->getTemp() );   break;
      case "date"  : $this->send($user->socket,"today is ".date("Y.m.d"));           break;
      case "time"  : $this->send($user->socket,"server time is ".date("H:i:s"));     break;
      case "thanks": $this->send($user->socket,"you're welcome");                    break;
	  case "id" : 	$this->send($user->socket,"You are user: ".$user." \r\n");    break;
	  case "users":  $list="User's List \r\n";
						foreach($this->users as $u)
						   $list.="user #".++$c.". $u \r\n";
						   
						$this->send($user->socket,$list); 
					 break;
					
      case "bye"   : $this->send($user->socket,"bye");                               
						$this->disconnect($user->socket);
						break;
      default      : $this->send($user->socket,$msg." not understood - ".date("H:i:s") );              break;
    }
  }
  
 function getTemp()
  {
	$jsonurl = "http://api.openweathermap.org/data/2.5/weather?q=New_York,us";
	$json = file_get_contents($jsonurl);
	$weather = json_decode($json);
	$temp = $weather->main->temp;

 if ( !is_numeric($temp) ) 
	{ 
	return false; 
	}
	else
	{
    $temp_f=round((($temp - 273.15) * 1.8) + 32);
	$temp_celcius=round(($temp - 273.15));
	return $temp_f."f";
	}
 }  //end get Temp

  
}  //end class

$master = new ws_server($server_ip,4444);
