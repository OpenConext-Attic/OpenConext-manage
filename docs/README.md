
# SURFnet SURFconext Manage #

The Manage application for SURFconext.

## Installing ##

Follow the steps below to install the Manage application.

### 1. Set up your HTTP server ###

Set up an HTTP server, note that for the Manage environment to work the following Apache2 config
(or it's functional equivalent for a different HTTP host) MUST be enabled:

    # Alias for handling simplesamlphp
    Alias /simplesaml !!PATH_TO_MANAGE_ROOT!!/library/simplesamlphp/www

    SetEnv APPLICATION_ENV !!ENV!!

    # Rewriting for rest services Management platform
    RewriteEngine On

    # Make sure simplesaml subdir is NOT run through mod_rewrite
    RewriteRule ^/simplesaml - [L,NC]

    # If we are NOT requesting a physical file
    RewriteCond %{REQUEST_FILENAME} !-f
    # ... a directory
    RewriteCond %{REQUEST_FILENAME} !-d
    # ... or an image, javascript or CSS file, route it to index.php
    RewriteRule !\.(js|ico|gif|jpg|png|css)$ /index.php

Note that !!ENV!! MUST be replaced by one of the environments from application.ini (production, staging, test, dev,...).
And !!PATH_TO_MANAGE_ROOT!! MUSTw be replaced with where the Manage application is placed at (example: /var/www/surfconext/manage).

### 2. Set up shell environment.

Edit */etc/profile* (as root or with sudo) and add:

    export APPLICATION_ENV="!!ENV!!"

Where "!!ENV!!" MUST be replace by your environment of choice.
Then open a new terminal to make sure you have the new environment.

### 3. Configure ###

Copy docs/example.manage.ini to /etc/surfconext/manage.ini and edit this file to reflect your configuration.

### 4 Set up VERS reporting.

Edit */etc/surfconext/manage.ini* and add:

    vers.env = "test"

or
    vers.env = "production"

Beware: there is no Development environment for VERS,
so make sure you don't mess up things for other developers.

Create a cronjob to run the VERS export:

    php [path to surfconext-admin]/scripts/versexport.php

Run the surfconext-admin/scipts/versexport.php script once a month,
on the first day of the month.
It will put the data of the previous month in VERS.
