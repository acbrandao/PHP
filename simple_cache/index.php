<!DOCTYPE html>
<head>
<title>PHP FILE CACHE</title>
</head>
<body>
<h3>Cache File</h3>
<?php
error_reporting(E_ALL);
$cache_file = 'file_cache.txt';
$cache_life = 10; //caching time, in seconds

$filemtime = @filemtime($cache_file);  // returns FALSE if file does not exist
if (!$filemtime or (time() - $filemtime >= $cache_life)){
  echo "<h3>GENERATING DYNAMIC FILE </h3>- storing into cache [ $cache_file ] <hr>"; 

   if ( !file_exists($cache_file) ) {
         die("<h1>Create an EMPTY $cache_file first and set perissions to 744 </h1>");
           ob_get_flush();
        }
      ob_start();    // echo statements below will be directly put into file 
       //  Simulate some resource conuming funtion would go here 
        echo  "\n Generated content  @ The time is " . date("h:i:sa")."\n";
        file_put_contents($cache_file,ob_get_flush());
}else{
    echo "<h3>SERVING CACHED FILE </h3>";
     echo  " The time is " . date("h:i:sa")." (cache time: $cache_life s) \n\n";
    readfile($cache_file);   //Reads a file and writes it to the output buffer.
}

?>
</body>
</html>
