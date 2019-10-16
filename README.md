![Userspace.org](../Media/USO_LOGO3.png)

# USERSPACEORG

FOSS code for community website setup of server and development system. 

In the future I will make HOWTO setup the site  for each step as follows. 

**Rough Process For Setup**

**CREATE DUPLICATE TEST SYSTEM**

* Setup Wordpress DB in maria DB. 
    * CREATE DATABASE wordpress;

* Install Wordpress compat with 5.1 php5.x
    * "tar zxvf wordpress-5.1.2.tar.gz" to create wordpress directory.

* Copy over wp-config.php Keep each systems creditials in comments and uncomment next system.

**BACKUP on system to recreate Wordpress page.**

  * Backup all your files with UPDRAFT minus database file.

  * Export All Content TOOLS->EXPORT  will give you an *migrate<TIMESTAMP>.sql

  * Save DB using WP Migrate DB to convert DNS name to local testing IP.

  * Compress all of the images from upload directory and copy to next system.

  * SETTINGS->Updraft Plus plugins backup "plugins" and download compressed file.

**Restore to next system.**

  * Migrate sql into systems DB.  
    * mysql  wordpress < userspa2_wordpress-migrate-20191008215324.sql 

  * Overwrite database username and password for local system to match wp-config.php   
    * USE wordpress;
    * GRANT ALL PRIVILEGES ON wordpress.* TO "userspace"@"localhost" IDENTIFIED BY "notmypassword";

  * Change wordpress login password to current system.  

  * ONLINE GERNATE MD5HASH: http://www.miraclesalad.com/webtools/md5.php

   OR

  * Get an MD5 hash of your password.

     * On Unix/Linux:

  * Create a file called wp.txt, containing nothing but the wordpress admin password.

     * tr -d ‘\r\n’ < wp.txt | md5sum | tr -d ‘ -‘

     * rm wp.txt    

  * login into mysql  UPDATE wp_users SET user_pass="tc4dlsd67888dkf3662c86818b9df302314" WHERE ID = 1;

     * “use (name-of-database)” (select WordPress database)

     * “show tables;” (you’re looking for a table name with “users” at the end)

     * “SELECT ID, user_login, user_pass FROM (name-of-table-you-found);” (this gives you an idea of what’s going on inside)

     * “UPDATE (name-of-table-you-found) SET user_pass=”(MD5-string-you-made)” WHERE ID = (id#-of-account-you-are-reseting-password-for);” (actually changes the password)

     * “SELECT ID, user_login, user_pass FROM (name-of-table-you-found);” (confirm that it was changed)

     * "exit" mysql
      
  * Change permissions on files and directoies in alignment with httpd process.

     * This will let you update plugins to wordpress. 

     * cd /var/www/html/wordpress/

     * chown -R www-data ./wp-content/*

     * chown  www-data ./wp-content/

     * chgrp  www-data ./wp-content/

     * chgrp -R  www-data ./wp-content/*    

     * 11:Install Upfdraft pluign. Actvate.

 * SETTINGS->UPDRAFTPLUS BACKUPS and Install "plugins" compressed file with.

     * Themes and Plugins. (plugin will have to be written to move from dev to prod. ) 

     * Dont do this for "uploads" most likly will be too large. 

     * Activate pluigns that you use.        

* Copy over compressed file images to the Upload directory and decompress.
       
     * Tools-> IMPORT:

     * install WordPress importer. 

     * Run importer.

     * Pick file you created earlier from TOOLS->EXPORT
   
* Install and activate Fix Media Library.  

     * Just check Regernate Thumbnails and start processing.
       
* Random Mix

     * Menu Item is not working : Had to save again. APPEARANCE->MENUS

     * I also changed menu item icons to thumbnail size. 
        
* Wordsquest needs to be added to the DB. Then activate plugin.

     * scp words.sql to next server.  

     * mysql  wordpress < words.sql
   
* Permalinks were not working , had to go to SETTING->PERMALINKS and SAVE.

     * This will regenerate your .htaccess file. 
  
* If you still have problems with 404 and your permalinks. Check the apache2

configs or in sites enabled for "AllowOverride None" Change None to All. If you

dont have access to the apache2 configs, Changing permalinks type may work. 

* You may need to install the mbstirng package if video/audio pages are not working.

  * Debian/Ubuntu "sudo apt-get install php-mbstring"

  * Redhat/CentOS "sudo yum install php-mbstring"


