<!DOCTYPE html>
<head>
<title>PHP FILE CACHE</title>
</head>
<body>
<h3>Cache File</h3>

<?php
error_reporting(E_ALL);

$cache_file = 'file_cache.txt';
$cache_life = '120'; //caching time, in seconds
 
$filemtime = @filemtime($cache_file);  // returns FALSE if file does not exist
if (!$filemtime or (time() - $filemtime >= $cache_life)){
    ob_start();
    echo "<h3>SERVERING  DYNAMIC FILE </h3>-  storing long running process  into cache";
         /*  Simulate some resource conuming funtion would go here */
	$myfile = fopen($cache_file, "w") or die("Unable to open file!");
	$txt = "John Doe @ ".Date(). "\n";
	fwrite($myfile, $txt);
	sleep(1);  /* simulate a logng running process*/
	$txt = "Jane Doe @ ".Date()." \n";
	fwrite($myfile, $txt);
	fclose($myfile);	 
    file_put_contents($cache_file,ob_get_flush());   
}else{
    echo "<h3>SERVING CACHED FILE </h3>";
    readfile($cache_file);   //Reads a file and writes it to the output buffer.
}

?>


</body>
</html>
