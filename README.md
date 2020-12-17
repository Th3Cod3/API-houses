# Docker
If you want to use the containers you can just run the following instruction:  

* Run docker-compose  
  * `docker-compose up -d`  
* Add the host, override DNS.  
  * Windows: `C:\Windows\System32\drivers\etc\host` open as Administrator.  
  * Mac OS/Linux: `/etc/hosts`.  
* Get the rootCA.pem from the container `/var/server-conf/rootCA.pem`.  
  * `docker-compose exec dtt cat /var/server-conf/rootCA.pem`  
* Save the key and import to the Thrusted Root Certification Authorities  

# Installation with Docker Container
Run the command `php cli.php seeder houses <amount>` into the `src` folder to create a certain amount of house with fake data into the db.  
Copy the `src\.env.sample`, saved as `src\.env` and edit the variables.  

# Installation without Docker Container
Make sure you have the following packages/software:  
* Phalcon 4.1  
* Composer  
* php >=7.3  
* Mysql  

Run the command `composer install` into the `src` folder.  
Run the command `mysql -u <user> -p<password> -D <db_name> << db` into the `dump` folder or import the file into the DB   
Run the command `php cli.php seeder houses <amount>` into the `src` folder to create a certain amount of house with fake data into the db.  
Copy the `src\.env.sample`, saved as `src\.env` and edit the variables.  

# INFO
## Users permissions
* `User`  
  * add houses  
  * get house information  
  * edit his own houses  
  * remove his own houses  
  * search for available houses  
  * get all available room types  
* `Admin`  
  * everything a user can do an Admin can do.  
  * add users  
  * get users information  

## Default Users
The default users and passwords:  
* `User:12345678`  
* `Admin:12345678`  
*These users have the corresponded permissions*

# DOCUMENTATION
* [API DOC](https://documenter.getpostman.com/view/9519887/TVsoGq5R)
* [Source Code DOC](docs/README.md)