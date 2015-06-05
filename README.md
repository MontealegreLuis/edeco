# EDECO

## Apache setup

Place this in vhosts.conf

```apache
#
# edeco.mx
#
<VirtualHost *:80>
    DocumentRoot "/path/to/edeco/edeco.mx"
    ServerName edeco.mx
    <Directory "/path/to/edeco/edeco.mx">
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
    DocumentRoot "/path/to/edeco/admin.edeco.mx"
    ServerName admin.edeco.mx
    <Directory "/path/to/edeco/admin.edeco.mx">
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

Add this to .htaccess in admin when in production

```apache
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R,L]
```

## Admin credentials

* User: `admin@edeco.com`
* Password: `edecodev`

## Client credentials

* User: `client@edeco.com`
* password: `edecoclient`

## Application setup

To create the database run:

```bash
$ mysql -u root -p < application/configs/data/sql/database-dev.sql
$ mysql -u root -p < application/configs/data/sql/schema.sql
$ mysql -u root -p < application/configs/data/sql/inserts.sql
```
