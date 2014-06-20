### Configure Apache VirtualHost

<VirtualHost *:80>
DocumentRoot "/vagrant/silex-skel"
ServerName silex-skel.dev

<Directory "/vagrant/silex-skel">
        Options Indexes Multiviews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all


        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule !\.(js|ico|gif|jpg|png|css|htm|html|txt|mp3)$ index.php
</Directory>
</VirtualHost>
