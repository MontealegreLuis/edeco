# Libraries info

* ZF       1.10.1
* Doctrine 1.2

Place this in vhosts.conf

```apache
#
# edeco.mx
#
<VirtualHost *:80>
    DocumentRoot "C:/Archivos de programa/Zend/Apache2/htdocs/svn/edeco.com/edeco.mx"
    ServerName edeco.mx
    ErrorLog "C:/Archivos de programa/Zend/Apache2/logs/error-vhosts.log"
    CustomLog "C:/Archivos de programa/Zend/Apache2/logs/access-vhosts.log" common
    <Directory "C:/Archivos de programa/Zend/Apache2/htdocs/svn/edeco.com/edeco.mx">
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
#
# admin.edeco.mx
#
<VirtualHost *:80>
    DocumentRoot "C:/Archivos de programa/Zend/Apache2/htdocs/svn/edeco.com/admin.edeco.mx"
    ServerName admin.edeco.mx
    ErrorLog "C:/Archivos de programa/Zend/Apache2/logs/error-vhosts.log"
    CustomLog "C:/Archivos de programa/Zend/Apache2/logs/access-vhosts.log" common
    <Directory "C:/Archivos de programa/Zend/Apache2/htdocs/svn/edeco.com/admin.edeco.mx">
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

Place this in hosts

```
127.0.0.1       edeco.mx
127.0.0.1       admin.edeco.mx
```

## Admin credentials

User: admin@edeco.com
password: edecodev

## Client credentials

User: client@edeco.com
password: edecoclient

Add this to .htaccess in admin when in production

```apache
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R,L]
```

## Application setup

To create the database run:

```bash
$ mysql -u root -p < application/configs/data/sql/database-dev.sql
$ mysql -u root -p < application/configs/data/sql/schema.sql
$ mysql -u root -p < application/configs/data/sql/inserts.sql
```
