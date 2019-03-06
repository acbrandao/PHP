<?php
//Script used to insert entires into the Activity log table 
//Connect to Databse
$host = '127.0.0.1';
$db   = 'test_db';
$user = 'db_user';
$pass = 'db_password';

echo "Connecting to the database \n";

// DSN string varies based on your database, refer here for details: http://php.net/manual/en/pdo.construct.php
// $dsn = "sqlite:c:/path/db.sqlite3 ;dbname=name_of_your_db"

$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
     throw new PDOException($e->getMessage(), (int)$e->getCode());
}

//Now grab the URL paramters do some sanitization
if (isset($_REQUEST['user']) &&  isset($_REQUEST['message']) )
{
  //Apply your own filtering here.. 
   $user=filter_var( trim($_REQUEST['user']) , FILTER_SANITIZE_STRING);
   $message=filter_var( trim($_REQUEST['message']) , FILTER_SANITIZE_STRING);
   $category=filter_var( trim($_REQUEST['category']) , FILTER_SANITIZE_STRING);

  //optional used to prevent unapproved URL requests
    $checksum=isset($_REQUEST['checksum'] ) ? trim($_REQUEST['checksum']) : null ;

$data = [
    'user' => $user,
    'ip' => $_SERVER['REMOTE_ADDR'] ,
    'message' =>  $message,
    'category' => $category 
  ];

//OPTIONAL add a checksum verification to prevent any app from calling this script and flooding your log
// Check sum could be  a simple md5 (some_field + salt )  which equals  the checksum created at source,
// if ((md5( $message))== $checksum)

try {
  $sql = "INSERT INTO `test_db`.`log` (`ip`, `user`, `description`,`category`) VALUES (:ip, :user, :message,:category) ";
  $stmt= $pdo->prepare($sql);
  $stmt->execute($data);

    echo "$message inserted  Successfully code: $result \n";
} catch (PDOException $e) {
     throw new PDOException($e->getMessage(), (int)$e->getCode());
      echo "Your request $message encountered and Error: code $result";
}
    
   }
else
  echo "Invalid request see API /user=  /message=";

?>
