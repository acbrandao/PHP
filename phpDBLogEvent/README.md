# Log events via PHP to database with Windows or Linux command line
Here’s a neat example (for Linux and Windows systems) of how to easily capture (log) and store the result of some script or program or really anything, in a central database table. Sure you could use just a log file, 
but spinning up an instance of MySQL (or SQLite) is trivial, and in the long run the ability querying the table makes it worth it.


# Requirements
*  PHP server with PDO extension loaded
*  PHP SQLite extension available (optional - only if you intend to use SQL database)


# Installation
-  Create the required database tables, based on the .sql files inclduded here..
*  For MySQL 

```sql
CREATE TABLE `log` (
	`log_id` INT(11) NOT NULL AUTO_INCREMENT,
	`time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`ip` VARCHAR(35) NOT NULL DEFAULT '',
	`user` VARCHAR(30) NULL DEFAULT NULL,
	`description` VARCHAR(128) NOT NULL DEFAULT '',
	`category` VARCHAR(10) NULL DEFAULT NULL,
	PRIMARY KEY (`log_id`),
	INDEX `idxtime` (`time`),
	INDEX `description` (`description`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
'

* for SQLite
```sql
CREATE TABLE IF NOT EXISTS 
log(
id INT PRIMARY KEY, 
time DATETIME, 
ip VARCHAR(35), 
user VARCHAR(30), 
description VARCHAR(64), 
category VARCHAR(32));
'

Make sure that the database tables are correctly created and make sure that you have the PROPER PERMISSONS (database or FILE perms) to write to the databases.




Place the php on any webserver that has PHP enabled. then change the following line to match your environment
```php
//Script used to insert entires into the Activity log table 
//Connect to Databse
$host = '127.0.0.1';
$db   = 'test_db';
$user = 'db_user';
$pass = 'db_password';


// DSN string varies based on your database, refer here for details: http://php.net/manual/en/pdo.construct.php
// $dsn = "sqlite:c:/path/db.sqlite3 ;dbname=name_of_your_db"

$dsn = "mysql:host=$host;dbname=$db";

```

-  Finally log an event by using one of the two logme_.* scripts to insert an event into the table

# Project Descriptions
see http://www.abrandao.com/2019/02/log-events-php-url-via-windows-linux-command-line/  for more details
