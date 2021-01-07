# Docker
If you want to use the containers you can just run the following instruction:  

* Edit container args into `docker-compose.yml`  
  * `DOMAIN=<domain>` For the domain request
  * `SSL_SELF_SIGNED=<0|1>` 1 to setup self-signed certificate or 0 to set without ssl  
  * `docker-compose up -d --build` to build the container again  
* Run docker-compose  
  * `docker-compose up -d` if it's the first time  
  * `docker-compose up -d --build` to build the container again  

## if you use the self-signed certificate
* Add the host, override DNS.  
  * Windows: `C:\Windows\System32\drivers\etc\host` open as Administrator.  
  * Mac OS/Linux: `/etc/hosts`.  
* Get the rootCA.pem from the container `/var/server-conf/rootCA.pem`.  
  * `docker-compose exec dtt cat /var/server-conf/rootCA.pem`  
* Save the key and import to the Thrusted Root Certification Authorities  

# Installation with Docker Container
Run the command `php cli.php seeder houses <amount>` into the `src` folder to create a certain amount of house with fake data into the db.  
Setup jwt and lock configuration into `src\config\config.php`  
* `"jwtTimeout" => "<days>"` The amount of days the JWT token will be valid.  
* `"jwtIssuedBy" => "<domain>"` The domain which this API is provide.  
* `"userLockTime" => "<minutes>"` To prevent force-attack the user will be lock for an amount of time.  
* `"lockFailCounter" => "<times>"` The times a user can fail before lock the user account.  
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

# Env

|Variable                       |Description                                                            |
|-------------------------------|-----------------------------------------------------------------------|
|`JWT_KEY=<secret>`             |The encryption token. Make a random base64url string.                  |
|`DB_HOST=<host>`               |Database domain/ip.                                                    |
|`DB_PORT=<port>`               |Database port, normally `3306`.                                        |
|`DB_USERNAME=<user>`           |Database user                                                          |
|`DB_PASSWORD=<password>`       |Database password                                                      |
|`DB_NAME=<db_name>`            |Database name                                                          |

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