#!/bin/sh
#Get the most recent 1 line from  Apache error logs
tail -n1  /var/log/apache2/error.log  > apache_errors.txt
# echo the results to a variable to include in the CURL post paramters
text_file=`cat apache_errors.txt`
echo "$text_file"
#now call post the information to the PHP script
# replace www.your_site_com  with actual webste details

curl -d "user=CRON&message=$text_file&category=linux" -X POST http://www.your_site_com/logevent.php
