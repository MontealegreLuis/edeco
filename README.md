# EDECO

## Setup

You'll need to setup some configuration values before configuring this project
for development. Create a file named `local.build.properties` and set values 
for the following keys.

* `email.password`. This a application requires you to setup an SMTP email account
* `gmaps.key`. This application uses Google maps to provide the location of the 
   properties
* `csrf.salt`. Add a secure CSRF key here
* `db.dsn`. This is the Data Source Name for your development database

Once you have your configuration values, run the following command

```bash
$ make setup RUSER="root" RPSWD="root"
```

The values of `RUSER` and `RPSWD` are those of a MySQL user with permissions to
create users and databases.

## Local server setup

There are two commands to run the application using PHP's built-in server.

Run the web section with

```bash
$ make run
```

Run the admin section with

```bash
$ make run-admin
```

NOTE: The actions that work with files won't work because the routes have the
files extensions and the local server will try to find the files and will ignore
the routes.

To work with those routes you will need Apache.

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

## Admin credentials

* User: `admin@edeco.com`
* Password: `edecodev`

## Client credentials

* User: `client@edeco.com`
* password: `edecoclient`

## Reset database

To start again with a clean database run this command

```bash
$ make reset
```

## Notes

Add this to .htaccess in admin when in production

```apache
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R,L]
```
