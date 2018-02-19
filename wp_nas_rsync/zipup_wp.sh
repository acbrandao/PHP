#!/bin/bash


# zipup_wp.sh This script creates a compressed backup 
# ZIP archive of the given directory WORDPRESS folder (option to excluding cache folder) 
# and given MySQL database.  Using standard zip (unix utility) to  archive folders and database
# Finally script will  RSYNC backup to an offsite location
#

# Feel free to use this script wherever you want, however you want. 
# Author: Antonio Brandao http://www.abrandao.com July 2017 
# based off script from Konstantin Kovshenin exclusively for Theme.fm in June, 2011
# Set the date format, filename and the directories where your backup files will be placed and which directory will be archived.
SECONDS=0  #  simply to track how long script runs  ,not essential

NOW=$(date +"%Y-%m-%d-%H%M")
TODAY=$(date +"%Y-%m-%d")   #just the data no time

#Domain / Host name , just  a easy way to prefix your files
DOMAIN="your_domain_name.com"   

#FILE="$DOMAIN.$NOW.zip"  # allows you to data stamp the filename 
FILE="$DOMAIN.WORDPRESS_LATEST.zip"   #keeps the updated file the same name

# Destination TARGET folder where  zip archive will be placed
BACKUP_DIR="/home/backup"

#Source folder (WORDPRESS  top directory) , typically your html root but check server settings
WWW_DIR="/var/www/public_html/"

# Folders to  exclude from acrhive typically CAChe files or other temporary files.
EXCLUDE="/var/www/public_html/wp-content/cache/*"

#Rsync information and login
RSYNC_HOST="www.your_rsync_server.com"   # website or server WHERE THE RSYNC host (RECEIVING) server lives
RSYNC_USER="admin"  # name of the RSync user needed to login using SSH keys
RSYNC_PORT=22  # Default port through which RSYNC is setup  using SSH keys port 22 is most common

# MySQL database credentials 
#  ** SECURITY ISSUES ** Placing your credentials here is a BIG security vulnerabiliity
#  consider using  .my.cnf  files  with proper permisssions 
# Replace below to match your 

DB_USER="user_name"
DB_PASS="pwd"
DB_NAME="database_name"
DB_FILE="$DOMAIN.$TODAY.sql"

# Create the archive and the MySQL dump
if [ -f  $BACKUP_DIR/$FILE ]; then
   echo "File $FILE exists. UPDATING ONLY [$SECONDS s].."
   zip -9 -u  $BACKUP_DIR/$FILE   $WWW_DIR -x "$EXCLUDE"
else
   echo "INITIAL backup up WORDPRESS folder [ $WWW_DIR] STARTED .... (be patient) ...  "
  zip -9  -rq  $BACKUP_DIR/$FILE  $WWW_DIR  -x "$EXCLUDE"
   echo "INITIAL backup up WORDPRESS folder [$WWW_DIR]  COMPLETED in [$SECONDS s] "
fi


# Create the MYSQL dump of your database
echo "CREATING  MYSQL dump of database  [$DB_FILE]  [$SECONDS s]"
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/$DB_FILE

# Append the dump to the archive, remove the dump and compress the whole archive.
echo "Combining DATABASE + WORDPRESS Archive into final file  [$FILE]  [elpased $SECONDS s]"
zip -rv $BACKUP_DIR/$FILE $BACKUP_DIR/$DB_FILE

echo "REMOVING  database backup $BACKUP_DIR/$DB_FILE"
rm $BACKUP_DIR/$DB_FILE

# RSYNC to offsite , requires that  SSH keys for authentication  have been setup ahead of time.
# Refer to https://www.debian.org/devel/passwordlessssh on how to do this.
echo "STARTING OFFSITE RSyNC BACKUP  ..  [elapsed $SECONDS s]"
rsync -avz -e "ssh -p $RSYNC_PORT " --progress $BACKUP_DIR/$FILE  $RSYNC_USER@$RSYNC_HOST:/share/Download/

#echo "REMOVING   $BACKUP_DIR/$FILE compressed zip file"
# rm   $BACKUP_DIR/$FILE   #uncomment this if you want to re-create the zip file each time instead o fjust updating it

echo "BACKUP FINISHED in  $SECONDS seconds..."