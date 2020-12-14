# Docker
* Run docker-compose
  * `docker-compose up -d`
* Add the host, override DNS.
  * Windows: `C:\Windows\System32\drivers\etc\host` open as Administrator.
  * Mac OS/Linux: `/etc/hosts`.
* Get the rootCA.pem from the container `/var/server-conf/rootCA.pem`.
  * `docker-compose exec dtt cat /var/server-conf/rootCA.pem`
* Save the key and import to the Thrusted Root Certification Authorities

# DOCUMENTATION
[API DOC](https://documenter.getpostman.com/view/9519887/TVsoGq5R#c3134db1-408d-4a8e-9a72-a4bf662d1380)