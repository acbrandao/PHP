# PHP PHP on Linux authenticate users with a Windows Server Active Directory
IF you’re building your application in PHP a very easy way to do this is simply to use PHPs LDAP library and then simply call with the proper functions.  Let’s detail the steps below. I’ll be doing this from a PHP 5 , Debian installation.

LDAP tends to be tied into the Windows AD Domain Name System to allow integrated quick lookups and fast resolution of queries. LDAP generally runs on port 389 and like other protocols tends to usually conform to a distinct set of rules (RFC’s).
For complete details on this code please click into each sub-folder and check out my blog here:  http://www.abrandao.com/category/software/

# Requirements
*  PHP server with LDAP extension loaded
*  Accessible Windows Active Directory Server
*  valid credentials on Windows Active Direcotry server
# Installation

Place the index.php on any webserver that has PHP enabled. then change the following line to match your environment
```php
   define('DOMAIN_FQDN', 'YOUR_DOMAIN.local'); //Replace with REAL DOMAIN FQDN
   define('LDAP_SERVER', '10.0.1.3');  //Replace with REAL LDAP SERVER Address
   
```

# Project Descriptions
see http://www.abrandao.com/2018/08/php-authenticate-users-with-windows-server-active-directory/   for more details
