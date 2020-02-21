# Scientis4Future 

This is installation kit for the website Scientis4Future. The application is build using 
[October CMS](http://octobercms.com) a Content Management System (CMS) and web platform for website development.

The working installation of the website is here: [Scientis4Future](https://scientists4future.pt/) 

## Enviroment preparation. 
### Minimum system requirementsRequirements
 
MYSQL: 5.7 ou 8
PHP version 7.0.8 or higher
PDO PHP Extension
cURL PHP Extension
OpenSSL PHP Extension
Mbstring PHP Library
ZipArchive PHP Library
GD PHP Library

### How to install 


1. Go to your {installation folder}
2. Clone the code from git `git clone https://github.com/syl-viadcc/scientists4futureKit.git .``
3. Configure Databaste
    Create empty databse s4future. 
    Edit config/database.php to fit your settings
   
4. Run: composer update
    If everything is OK, this process completes with message: 'Ping? Pong!'
    
5. You may need to adjust the permissions of the {installation folder}. 

```
ps aux | egrep '(apache|httpd)' #check the user of your server
sudo chown -R {owneruser} .
sudo  chgrp -R {apache or httpd} .
sudo chmod -R 750 .
sudo chmod -R g+s .
sudo chmod -R g+w storage/ themes/ plugins/
```

6. Extract database/media.tar file to folder storage/app
```
cd storage/app
tar xvf ../../database/media.tar
cd ../..
chown -R {owneruser}.{apache or httpd} storage/ 
```

7. In the {installation folder} run:
```
php artisan cache:clear
php artisan october:up
php artisan october:update
```

8. Almost ready! Open in the browser {your_s4fapp}/backend
9. Login with admin/admin
10. Go to 'Configurations' {your_s4fapp}/backend/cms/themes and activate the Scientists 4 Future theme

11. Ready. 

#### Backend access

{website-url}/backend
login/password: admin/admin

### Learning October

The best place to learn October is by [reading the documentation](https://octobercms.com/docs) or [following some tutorials](https://octobercms.com/support/articles/tutorials).
You may also watch these introductory videos for [beginners](https://vimeo.com/79963873) and [advanced users](https://vimeo.com/172202661).

