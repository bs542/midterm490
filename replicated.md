Apr/14/22. Working on the replicated database making Mahi as master and mine as slave. first created ssh connection between the both master and slave. Firewalls should be disabled and abled by slave connection. Ssh should be turned on for both connections. Master slave could take the data but realized that it will not be compatible with hatstandby. Then thought of using postgreSQL. 
https://www.howtoforge.com/tutorial/postgresql-replication-on-ubuntu-15-04/ we will  be using postgreSQL using the hatstand by mode. 
5:03 pm- going to use postgreSQL.



Mahi - Baljit association
-------------------------------------------------------

Biggest problem, setting up ssh and ensuring that our password when created was done with, IDENTIFIED WITH mysql_native_password
x----------------------x---------------------------------x

sudo ufw allow from replica IP addr to any port 3306

-------------------------------------------------------

sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

x---------------------x------------------------------x
" In the file change bind addr to master ip
Uncoment the server id  , we used 69 for master, and 68 for the slave id
Uncomment log_bin 
Uncomment binlog_do_db = IT490 ( CHANGE DATABASE NAME ACCORDING TO MAIN USER , DENISE) "

-------------------------------------------------------

sudo systemctl restart mysql

-------------------------------------------------------

"Make replicated users"

x---------------------x------------------------------x

sudo mysql

-------------------------------------------------------

CREATE USER 'replica_user'@'replica_server_ip' IDENTIFIED WITH mysql_native_password BY 'password';         ( we use mysql_native_pass because it gives sha2_password error, which is required for safe autentication)

-------------------------------------------------------

GRANT REPLICATION SLAVE ON . TO 'replica_user'@'replica_server_ip';

-------------------------------------------------------

FLUSH PRIVILEGES ;

-------------------------------------------------------

FLUSH TABLES WITH READ LOCK;

-------------------------------------------------------

SHOW MASTER STATUS;
      ( use the bin number and pos later on , think u have to change it everytime we get out of sql)

x---------------------x------------------------------x

"DO THIS IN A SEPERATE WINDOW WITHOUT TURNING OFF UR MYSQL
Since our source will have a populated database , because production environment"

-------------------------------------------------------

sudo mysqldump -u root database name (IT490 ) > databasename.sql
    ( this dumps the sql dumb of our existing data )
(The mysqldump client utility performs logical backups, producing a set of SQL statements that can be executed to reproduce the original database object definitions and table data.)

x---------------------x------------------------------x

Since we have SSH setup we can securely transfer our data with scp ( secure copy)

-------------------------------------------------------

scp databasename.sql baljit@replica_server_ip :/tmp/

-------------------------------------------------------

This will copy this dump file into their temp folder

x--------------------------x--------------------------x---------------------x

-------------------------------------------------------
FROM HERE ONWARDS WE TAKE CONTROL OF THE REPLICATED PERSONS REMOTE HOST ( BALJITS) AND WORK THERE 
-------------------------------------------------------

ssh 'baljit'@'192.168.191.217' ( make sure baljits vm is working)

-------------------------------------------------------

sudo mysql

-------------------------------------------------------

mysql> CREATE DATABASE databasename;

-------------------------------------------------------

exit

-------------------------------------------------------

sudo mysqldump databasename < /tmp/databasename.sql  ( Taking the dump file from our temp)


X-------------------X---------------------X-------------------------------X

sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf 

-------------------------------------------------------

"change server id and uncomment it, MAKE SURE ITS DIFFERENT FROM THE ORIGINAL
uncomment binlog and log_bin 
Add this line in the end , here we are defining the location of replica
     relay-log               = /var/log/mysql/mysql-relay-bin.log "

-------------------------------------------------------

sudo systemctl restart mysql

x-----------------x--------------------------x----------------------x

In the remote host ( baljits) , add these files

-------------------------------------------------------

sudo mysql

-------------------------------------------------------

CHANGE REPLICATION SOURCE TO



SOURCE_HOST='source_server_ip',



SOURCE_USER='replica_user',



SOURCE_PASSWORD='Baljit2020.',



SOURCE_LOG_FILE='mysql-bin.000001', ( use the location from SHOW MASTER STATUS on original,it changes)


SOURCE_LOG_POS=899;( use pos from SHOW MASTER STATUS on original , it changes) 

-------------------------------------------------------

START REPLICA;

-------------------------------------------------------

SHOW REPLICA STATUS\G; 

-------------------------------------------------------

SET GLOBAL SQL_SLAVE_SKIP_COUNTER = 1;

x-------------------x------------------------------------------x
after that if u add stuff in original db, u can start to see change if u did it correctly


If your replica has an issue in connecting or replication stops unexpectedly, it may be that an event in the source’s binary log file is preventing replication. Thats why we use SQL_SLAVE_SKIP_COUNTER so it skips the points in binary log file which is empty and doesnt just crash

