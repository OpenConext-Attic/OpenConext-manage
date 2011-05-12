README
======

This directory should be used to place project specfic documentation including
but not limited to project notes, generated API/phpdoc documentation, or 
manual files generated or hand written.  Ideally, this directory would remain
in your development environment only and should not be deployed with your
application to it's final production location.


Setting Up Your VHOST
=====================

The following is a sample VHOST you might want to consider for your project.

<Virtualhost *:443>
        ServerAdmin surfconext-beheer@surfnet.nl

        DocumentRoot /var/www/html/manage/www/public
        ServerName manage.dev.surfconext.nl

        <Directory "/var/www/html/manage/www">
          Order deny,allow
          Deny from all
          
          // This is not required when in production
          Allow from 145.100.191.0/24 192.87.109.0/24 195.169.126.0/24 192.87.117.0/24 92.67.37.154 195.240.2.130 81.23.230.39 81.23.230.239 82.176.175.208 80.101.99.190 79.170.90.128        
        </Directory>

        # Alias for handling simplesamlphp
        Alias /simplesaml /var/www/html/manage/www/library/simplesamlphp-1.6.3/www

		# This should be omitted in the production environment
        SetEnv APPLICATION_ENV staging

		# Rewriting for rest services Management platform
        RewriteEngine On

        # Make sure simplesaml subdir is NOT run through mod_rewrite
        RewriteRule ^/simplesaml - [L,NC]

        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_URI} !^/demo/(.*)$
        RewriteRule !\.(js|ico|gif|jpg|png|css)$ /index.php

        SSLEngine on
        SSLCertificateFile /etc/httpd/ssl/star.dev.surfconext.nl.pem
        SSLCertificateKeyFile /etc/httpd/ssl/star.dev.surfconext.nl.key
        SSLCertificateChainFile /etc/httpd/ssl/chain.dev.surfconext.nl.pem

</VirtualHost>

