# midterm490

i have created three files of the front end, where user can log in sign up(inside them there is ager calculator, review stystem) i created an questionare file. 
HAD TO UPLOAD THEM BECAUSE I'VE USED VISUAL CODE AND GITHUB WAS GIVING ISSUES.


April/6/22(impleneted ssl to all web pages) SSL denying the permissions. 
  https://hostadvice.com/how-to/how-to-install-a-self-signed-ssl-certificate-for-apache-on-ubuntu-18-04-server/
  https://www.arubacloud.com/tutorial/how-to-enable-https-protocol-with-apache-2-on-ubuntu-20-04.aspx
  https://www.youtube.com/watch?v=1daMCJeh5yM
  
April/8/22 - implemented ssd code into the new group.

openssl version

sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/my.key -out /etc/ssl/certs/my.crt
sudo mkdir /etc/certificate 
Important: 
Common Name: localhost
email: localhost@localhost.edu
------------------------------------------------------------------------------------- 
certificate (Spelling reference)
------------------------------------------------------------------------------------- 
sudo cp /etc/ssl/private/my.key /etc/certificate
sudo cp /etc/ssl/certs/my.crt /etc/certificate
sudo ufw allow 443
------------------------------------------------------------------------------------- 
CAREFUL WITH THIS STEP 
sudo nano /etc/apache2/conf-available/ssl-params.conf
-------------------------------------------------------------------------------------
**sudo nano /etc/apache2/sites-available/default-ssl.conf 
COPY (below text) EXACTLY INTO FILE(above this message) WITH THE **
------------------------------------------------------------------------------------- 
SSLCipherSuite EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH

    SSLProtocol All -SSLv2 -SSLv3 -TLSv1 -TLSv1.1

    SSLHonorCipherOrder On


    Header always set X-Frame-Options DENY

    Header always set X-Content-Type-Options nosniff

    # Requires Apache >= 2.4

    SSLCompression off

    SSLUseStapling on

    SSLStaplingCache "shmcb:logs/stapling-cache(150000)"


    # Requires Apache >= 2.4.11

    SSLSessionTickets Off
To get out of NANO:
cntrl O, enter, cntrl X
------------------------------------------------------------------------------------- 
-------------------------------------------------------------------------------------
sudo ufw app list
sudo ufw app info "Apache Full"
sudo ufw app allow in "Apache Full"
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enconf ssl-params
sudo a2ensite default-ssl
sudo apache2ctl configtest
sudo systemctl restart apache2 
-------------------------------------------------------------------------------------
IN BROWERS:
type in URL search: 
https://localhost/






-------------------------------------------------------------------------------------




april/12/22 

assigned to work on replicated database/firewall





