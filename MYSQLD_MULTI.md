# Mysqld Multi setup.

 ##### This is an excellent setup for test or development systems.
 ##### On a production system it better splits processes and threads to read and write.

```apt-get Get mysql server```

**edit mysqld_multi as it needs to be patched.(usually found in /usr/bin/)**
**comment out this section:**
```
    #    if (!$suffix_found)
    #{
    #  $com.= " --defaults-group-suffix=";
    #  $com.= $groups[$i];
    #}
```
**Change elseif to below. elsif**
```
elsif ("--defaults-group-suffix=" eq substr($options[$j], 0, 24))
```


**Edit config to make multiple DB entries for logs and data **
```cd  /etc/mysql/mariadb.conf.d```

**Edit file to match : 50-client.cnf**
```
[client]
# Default is Latin1, if you need UTF-8 set this (also in server section)
default-character-set = utf8mb4

port=3306
# socket location
#socket = /var/run/mysqld/mysqld.sock
socket = /var/run/mysqld/mysql_master.sock
protocol=TCP
```

**Edit file to match : 50-server.cnf**
```
#
# These groups are read by MariaDB server.
# Use it for options that only the server (but not clients) should see
#
# See the examples of server my.cnf files in /usr/share/mysql
[mysqld_multi]
           mysqld     = /usr/bin/mysqld_safe
           mysqladmin = /usr/bin/mysqladmin
           user       = multi_admin
           password   = 123456
[mysqld1]
           server-id = 1
           port       = 3306

           socket      = /var/run/mysqld/mysql_master.sock
           pid-file    = /var/run/mysqld/mysql_master.pid

           datadir   = /var/lib/mysql_master/

           log_error = /var/log/mysql/error_master.log

           language   = /usr/share/mysql/english
           tmpdir = /tmp
           lc-messages-dir     = /usr/share/mysql
           basedir = /usr
           query_cache_size = 16M
           user       = mysql
           #skip-networking=0
           #skip-bind-address
           bind-address    = 0.0.0.0

           log_bin                     = /var/log/mysql/mysql-bin-master.log
           innodb_flush_log_at_trx_commit  = 1
                     sync_binlog                 = 1
           binlog-format               = ROW

           binlog-do-db = mysql
           binlog-do-db = wordpress


[mysqld2]
           server-id = 2
           port         = 3308
           socket      = /var/run/mysqld/mysql_slave.sock
           pid-file    = /var/run/mysqld/mysql_slave.pid

           datadir     = /var/lib/mysql_slave/

           log_error = /var/log/mysql/error_slave.log

           language   = /usr/share/mysql/english
           tmpdir = /tmp
           lc-messages-dir     = /usr/share/mysql
           basedir = /usr
           query_cache_size = 16M
           user       = mysql
           #skip-networking=0
           #skip-bind-address
           bind-address    = 0.0.0.0
           read_only           = 1

           relay-log           = /var/log/mysql/relay-bin-slave.log
           relay-log-index     = /var/log/mysql/relay-bin-slave.index
           master-info-file    = /var/log/mysql/master-slave.info
           relay-log-info-file = /var/log/mysql/relay-log-slave.info
           log_bin             = /var/log/mysql/mysql-bin-slave.log
           binlog-do-db = mysql
           binlog-do-db = wordpress
```

**Make needed master / slave directories**

**You should have log directory already , both master and slave will use this .**
```
    /var/log/mysql/
```
**You should already have a pid and socket directory.**
```
    /var/run/
```
**You will need to create a master database binary directory**
```
   /var/lib/mysql_master/
```
**You will need to create a slave database binary directory**
```
   /var/lib/mysql_slave/
```

**Build master and slave databases**
```
    mysql_install_db --user=mysql --datadir=/var/lib/mysql_master
    mysql_install_db --user=mysql --datadir=/var/lib/mysql_slave
```
**mysql_multi command controls**
```service mysqld start```
```mysqld_multi start 1,2```
```mysqld_multi report```

**Create multi_admin and slave user**
```
CREATE USER 'multi_admin'@'localhost' identified by '123456';
GRANT ALL ON *.* TO 'multi_admin'@'localhost';
CREATE USER 'slave'@'localhost' identified by '123456';
GRANT ALL ON *.* TO 'slave'@'localhost';
FLUSH PRIVILEGES;
```

**Once both databases are up you should be able to acces them via 0.0.0.0 or socket and set port 3306 and 3308**
```mysql --host=0.0.0.0 -S /var/run/mysqld/mysql_master.sock  --port=3306```
```mysql --host=0.0.0.0 -S /var/run/mysqld/mysql_slave.sock --port=3308```

**On Master LOCK DB and save position and bin file name.**
```
   FLUSH TABLES WITH READ LOCK;
   SHOW MASTER STATUS;
```

**From Master**
```mysqldump -uroot -p --host=0.0.0.0 --port=3306 --create-options --triggers --routines --events --all-databases --master-data=2 > replicationdump.sql```

**On Master Unlock DB.**
```
UNLOCK TABLES;
```

**On Slave import database from master.**
``` mysql --port=3308  -S /var/run/mysqld/mysql_slave.sock  < database.sql```

**On Slave start slave REPLICATION.**
```
   STOP SLAVE;
   SHOW SLAVE STATUS; 
   CHANGE MASTER TO MASTER_HOST='localhost', MASTER_USER='slave', MASTER_PASSWORD='123456', MASTER_LOG_FILE='mysql-bin-master.000013', MASTER_LOG_POS=798;
   START START;
   SHOW SLAVE STATUS;
```




