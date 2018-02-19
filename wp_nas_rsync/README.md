# Bash script WordPResss Backup via RSYNC to NAS drive
Example of code expalined here  http://www.abrandao.com/2017/07/backup-script-wordpress-offsite-nas-via-rsync/

This script creates a compressed backup archive of the given directory WORDPRESS folder (option to excluding) 
and given MySQL database.  Using standard zip (unix utility) to compressor and archive the various folders
Finally script will  RSYNC backup to an offsite location
#
 Feel free to use this script wherever you want, however you want. We produce open source, GPLv2 licensed stuff.
Author: Antonio Brandao July 2017 based off script from Konstantin Kovshenin exclusively for Theme.fm in June, 2011

## Installation

Download shell script to your system. 

 Open the file and customize code  to match your requirements. Be sure to chaange the following varaibles near the top of the script:

   * DOMAIN : Serves t prefix zipuped up filename
   * FILE   : "$DOMAIN.$NOW.zip"  # allows you to data stamp the filename 
   * BACKUP_DIR : Where the fully zipped file will live
   * WWW_DIR : Source folder (WORDPRESS  top directory) , typically your html root but check server settings
   *

Change the permissions to execute `chmod +x zipup_wp.sh`



## Usage

 To Run simply execute the script as :

`/zipup.sh`

  To execute  in a regular basis use the a cron entry as below
`# For more information see the manual pages of crontab(5) and cron(8)
#
# m h     dom mon dow   command
  0  5,20 *   *    *   /home/backup/zipup_wp.sh   #backups up at 5am and 8pm daily.
`


## Contributing
1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
## History
TODO: Write history
## Credits
TODO: Write credits
## License
TODO: Write license

