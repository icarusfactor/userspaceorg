![Userspace.org](/Media/USO_LOGO3.png)
# USERSPACEORG

- - -
##### FOSS code for community website setup of server and development system.
**Process For Setup**
**CREATE DUPLICATE TEST SYSTEM**
- - -

==Setup VM for each of the systems==
==NOTE: Future setup I'm moving to virt-manager/virt-install for PXE boot from TFTP server==

1. Virtualbox VM for wordpress web server.
  - NAME webvm.dev 
  - CPU 1 
  - Memory 2G min
  - Disk 20G min
  - Network Bridge

1. Virtualbox VM for MariaDB server.
  - NAME dbvmA.dev
  - CPU 1 
  - Memory 2G min
  - Disk 15G min
  - Network Bridge

1. Virtualbox VM for MariaDB server.
  - NAME dbvmB.dev
  - CPU 1 
  - Memory 2G min
  - Disk 15G min
  - Network Bridge

==Setup DNS on each VM==
###### LOGON: webvm.dev 
`vim /etc/hosts.net`
```
localhost 127.0.0.1
webvm.dev 192.168.1.10
dbvmA.dev 192.168.1.11
dbvmB.dev 192.168.1.12
```
###### REPEAT ABOVE & LOGON: dbvmA.dev, dbvmB.dev, localhost

##### Setup a users and wordpress database with Maria DB. 

1. Install mysql server on primary database vm
   ###### LOGON: dbvmA.dev 
	` sudo apt-get install mysql-server mysql-client -y`
	
	###### For production servers, don't bother with it for dev systems.
    `sudo mysql_secure_installation`
    
    ###### Edit config file for primary SQL server
    
   `sudo vim /etc/mysql/mariadb.conf.d/50-server.cnf`
    
  ```
[mysqld]
bind-address            = dbvmA.dev
server-id              = 1
log_bin                = /var/log/mysql/mysql-bin.log
log_bin_index          = /var/log/mysql/mysql-bin.log.index
expire_logs_days        = 10
max_binlog_size        = 100M
binlog_do_db           = wordpress
binlog_ignore_db       = mysql
sync-binlog = 1
sync-relay-log = 1
sync-relay-log-info = 1
sync-master-info = 1
   ```
   
   - Restart Mysql Service
`sudo service mysqld restart`

1. Install mysql server on secondary database vm
   ###### LOGON: dbvmB.dev 
	` sudo apt-get install mysql-server mysql-client -y`
    ###### For production servers, don't bother with it for dev systems. 
    `sudo mysql_secure_installation`
    ###### Edit config file for primary SQL server 
   ` sudo vim /etc/mysql/mariadb.conf.d/50-server.cnf`
   
 ```
[mysqld]
bind-address            = dbvmA.dev
server-id              = 2
log_bin                = /var/log/mysql/mysql-bin.log
log_bin_index          = /var/log/mysql/mysql-bin.log.index
expire_logs_days        = 10
max_binlog_size        = 100M
binlog_do_db           = wordpress
binlog_ignore_db       = mysql
sync-binlog = 1
sync-relay-log = 1
sync-relay-log-info = 1
sync-master-info = 1
```

   - Restart Mysql Service
`sudo service mysqld restart`

1. Setup named wordpress admin user of database.
   ==NOTE: admin needs full access to the wordpress database==
   ###### LOGON: dbvmA.dev 
     - CREATE USER 'webuser'@'webvm.dev' identified by '123456';
     - GRANT ALL ON *.* TO 'webuser'@'%.webvm.dev';
      
   ###### LOGON: dbvmB.dev 
     - CREATE USER 'webuser'@'webvm.dev' identified by '123456';
     - GRANT ALL ON *.* TO 'webuser'@'%.webvm.dev';  
     
1. Setup wordpress replication user for master-master database.
   ==NOTE: replication only needs access to each other wordpress database==
   ###### LOGON: dbvmA.dev 
     - CREATE USER 'repl_user'@'dbvmB' identified by '123456';
     - GRANT REPLICATION SLAVE ON wordpress.* TO 'repl_user'@'%.dbvmB.dev';
     - FLUSH PRIVILEGES;
     
   ###### LOGON: dbvmB.dev 
    - CREATE USER 'repl_user'@'dbvmA' identified by '123456';
    - GRANT REPLICATION SLAVE ON wordpress.* TO 'repl_user'@'%.dbvmA.dev';
    - FLUSH PRIVILEGES;

1. Setup empty database for wordpress installation to fill.
   ###### LOGON: dbvmA.dev
     - CREATE DATABASE wordpress;
     
   ###### LOGON: dbvmB.dev
     - CREATE DATABASE wordpress;  

1. Get Replication position on primary database.
  ###### LOGON: dbvmA.dev

` mysql> use wordpress; `
mysql> FLUSH TABLES WITH READ LOCK;


###### LOGON: dbvmA.dev again in another console
  `mysql -uroot --vertical -e "show master status;"`

   ```
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000001 |      455 | wordpress    | mysql            |                   |
+------------------+----------+--------------+------------------+-------------------+
1 row in set (0.00 sec)
```
   
1. Dump mysql database to raw sql file.
  `mysqldump -uroot -p wordpress > dbvmA.sql`

1. Switch back to console with open mysql client connection and release the DB.
```
   mysql> UNLOCK TABLES;
   mysql> exit;
```

1. Copy database to secondary database server.
   ###### LOGON: dbvmA.dev 
   `scp ./dbvmA.sql datasci@dbvmB.dev:/tmp/dbvmA.sql`

1. Import database from primary database into secondary database.
   ###### LOGON: dbvmB.dev
   `mysql -uroot -p wordpress < /tmp/dbvmA.sql`

1. Get Replication position on secondary database.
  `mysql -uroot --vertical -e "show master status;"`

 ```
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000001 |     1426 | wordpress    | mysql            |                   |
+------------------+----------+--------------+------------------+-------------------+
1 row in set (0.00 sec)
```

1. Start replication on secondary database.
  ```
mysql> STOP SLAVE;
mysql> CHANGE MASTER TO master_host='dbvmA.dev', master_port=3306, master_user='repl_user', master_password='12345', master_log_file='mysql-bin.000001', master_log_pos=455;
mysql> START SLAVE;
   ```

1. Start replication on primary database.
   ###### LOGON: dbvmA.dev
  ```
mysql> STOP SLAVE;
mysql> CHANGE MASTER TO master_host='dbvmB.dev', master_port=3306, master_user='repl_user', master_password='12345', master_log_file='mysql-bin.000001', master_log_pos=1426;
mysql> START SLAVE;
   ```

1. Verify on each server that replication is working on each.
   ###### LOGON: dbvmA.dev
   `mysql -uroot --vertical -e "show slave status;"`
  ```
Slave_IO_Running: Yes
Slave_SQL_Running: Yes
```
   ###### LOGON: dbvmB.dev
   `mysql -uroot --vertical -e "show slave status;"`
```
Slave_IO_Running: Yes
Slave_SQL_Running: Yes
   ```
   
1. If you have Connecting or No on either database you will have to resync.
  ==NOTE: ADD HOW TO RESYNC IN FUTURE ==
    - But entails mostly going back over of item *11* , *12* , *13*

1. Install Wordpress 5.x php7.x and support libraries.
   ###### LOGON: webvm.dev
   
   ==URL: for download: https://wordpress.org/download/releases/==
    - cd /var/www/html/
    - wget https://wordpress.org/wordpress-5.3.2.tar.gz
    - tar zxvf wordpress-5.3.2.tar.gz
    - sudo apt-get install php7.3 php7.3-mysql php7.3-curl php7.3-gd php7.3-mbstring php7.3-xml php7.3-xmlrpc php7.3-soap php7.3-intl php7.3-zip

    - Copy *wp-config.php* from remote system and keep each systems credentials in comments.
    - Uncomment for local development or production remote system.

1. BACKUP on system to recreate Wordpress page.
      - Use *Filezilla* to backup all your wordpress directory files with ftp.
      - Save DB using *WP Migrate* convert DNS webhost name to local testing *webvm.dev*.
      - Convert all //userspace.org to //webvm.dev:8080
      - Convert all /a/b/c/d/public_html to local /var/www/html

1. Change Various HOSTNAME data in files to DEV system.
     - The rest of the changes will be in the Curator Theme.
     - Need to make script to change theme HOSTNAME data.
     - cd /var/www/html/wp-content/themes/curator/
     - Change HOSTNAME data to your dev system in *header.php* , *index.php* , *style.css*
     - cd js
     - Change HOSTNAME data in *common5.js* , *init4.js*

1. RESTORE to local development system.
  ###### LOGON: dbvmA.dev
  ==NOTE: You should have already installed/setup DB for master-master replaication.==
    * Migrate only wordpress sql into systems DB.
     `mysql  wordpress < wordpress-migrate-20191008215324.sql` 
    * (OPTIONAL)Change wordpress login password to current system.
     - ONLINE GERNATE MD5HASH: http://www.miraclesalad.com/webtools/md5.php
  **OR**
  * Get an MD5 hash of your password.
  * On Unix/Linux:
     - Create a file called wp.txt, containing nothing but the wordpress admin password.
     * tr -d ‘\r\n’ < wp.txt | md5sum | tr -d ‘ -‘
     * rm wp.txt

  ###### LOGON: dbvmA.dev
    `UPDATE wp_users SET user_pass="tc4dlsd67888dkf3662c86818b9df302314" WHERE ID = 1;`

1. OPTIONAL FIND admin user in detail.
     - “use (name-of-database)” (select WordPress database)
     - “show tables;” (you’re looking for a table name with “users” at the end)
     - “SELECT ID, user_login, user_pass FROM (name-of-table-you-found);” (this gives you an idea of what’s going on inside)
     - “UPDATE (name-of-table-you-found) SET user_pass=”(MD5-string-you-made)” WHERE ID = (id#-of-account-you-are-reseting-password-for);” (actually changes the password)
     - “SELECT ID, user_login, user_pass FROM (name-of-table-you-found);” (confirm that it was changed)
     - "exit" mysql
1. Change permissions on files and directoies in alignment with httpd process.
   ###### LOGON: webvm.dev ######

    - Install files you had downloaded from remote FTP server

    ==NOTE: This will let you update plugins to wordpress.==
    ```
    cd /var/www/html/wordpress/
    sudo find . -exec chown www-data:www-data {} +
    sudo find . -type f -exec chmod 664 {} +
    sudo find . -type d -exec chmod 775 {} +
    sudo chmod 660 wp-config.php
    ```
    ==NOTE: So that you don't have to setup an FTP server.==

    ` echo "define('FS_METHOD','direct');" >> wp-config.php`
    ###### LOGON: webvm.dev wordpress
    `URL: http://webvm.dev/wp-admin/`

1.  Install/Activate Plugins,Fix Media Library.

     - Just check Regenerate Thumbnails and start processing.

1. Random Mix

     - Widget Item not working : Had to save again. APPEARANCE->MENUS
     - I also changed menu item icons to thumbnail size. 

1. Wordsquest, if new words need to be overwritten to DB. Then reactivate plugin.

     - scp words.sql to dbvmA.dev server.
     - mysql  wordpress < words.sql

1. Permalinks were not working.
     - Go to *SETTING->PERMALINKS* and SAVE.
     - This will regenerate your *.htaccess* file.

1. Still having problems with 404 and your permalinks.

    - Check the apache2 config or in sites enabled for "AllowOverride None" Change None to All.

1. Setup CRON to PING site and set off WPCRON functions for updating my site cache
   == NOTE: May use free account on https://uptimerobot.com in future.==
   ###### LOGON: local system
  ```
 mkdir /usr/local/cron/
 vim /usr/local/cron/updateRSScache.sh
curl -v --silent http://userspace.org/ 2>&1 | grep Words   
crontab -e
0 */2 * *  root /usr/local/cron/updateRSScache.sh
```

1. Add Hyperdb to Wordpress for load balance,fail-over,replication and cache.
   ==NOTE: This maybe already instaled in your FTP download , but want to show manual add.==
   ###### LOGON: webvm.dev
    * Download zip "wget https://downloads.wordpress.org/plugin/hyperdb.zip"
    * Unzip "unzip hyperdb.zip"
    * Move config to /var/www/html/db-config.php
    * Add define('SLAVE_HOST','IP_ADDRESS_OF_SLAVE');"  to wp-config.php
    * Comment or uncomment needed DB config in db-config.php
    * Activate HyperDB by moving config to /var/www/html/wp-content/db.php

1. Install REDIS for object caching.
   ###### LOGON: URL: http://webvm.dev/wp-admin/ ######
   - Install REDIS Wordpress plugin and activate.
   ###### LOGON: dbvmA.dev
   ==NOTE: Will only setup one of these and not distrubuted, but could be.==
   ` apt-get install php-redis redis-server`
    `vim /etc/redis/redis.conf`
      - Add bind address
      - Uncomment auth password
      - Add segment to wp-config.php
      ###### LOGON: webvm.dev
```
$redis_server = array(
            'host'     => 'dbvmA.dev',
            'port'     => 6379,
            'auth'     => '12345',
            'database' => 0, // Default is 0.
        );
```

1. Test to see if REDIS object cache its working.
   ###### LOGON: dbvmA.dev
   `redis <host address>`
   `  auth 12345  `
   `  keys * `
 
