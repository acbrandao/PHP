# PHP Simple Text File Caching Script snippet

It's often useful to cache frequently requested STATIC files instead of re-generating them or re-processing the PHP script itself.
This is a super simple script that does that, It simply looks at the file contents & date and if the date has exceeded a certain time and the 
file contents have changed it fires off a new edition otherwise it sends out the existing file 


# Requirements
*  PHP 5.x or PHP 7.x  on web server
*  File write permissions on the the folder where cache file is to be stored

# Installation

Place the index.php on any webserver that has PHP enabled. then change the following line to match your environment. 
  $cache_file is any file that will store this page and be the cahced file, it should be existing and intially set with 0744 permissions
  $cach_life, is the time in seconds to check to see if this is out of date.
```php
  $cache_file = 'file_cache.txt';
  $cache_life = 10; //caching time, in seconds   
```

# Project Descriptions
see http://www.abrandao.com/2012/09/sample-code-quick-and-dirty-php-cache/   for more details
