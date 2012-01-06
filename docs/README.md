
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

### 4 Configure LDAP

add engineblock ldap username to /etc/surfcontact/manage.ini:

engineblock.ldap.userName = "cn=VERS Account,dc=surfconext,dc=nl"
engineblock.ldap.password = "[password]"

Create LDAP user :

Add LDAP user "cn=VERS Account,dc=surfconext,dc=nl":

ldapadd -h ldap.surfconext.nl -D "cn=engine,dc=surfconext,dc=nl" \
    -x -W -f add_VERS-Account.ldif

file add_VERS-Account.ldif:
==================================== 
DN: cn=VERS Account,dc=surfconext,dc=nl
objectClass: pilotPerson
cn: VERS Account
sn: VERS
description: VERS Account
userPassword: {SHA}***********
====================================

Set sizelimit for VERS LDAP user:
ldapmodify -h ldap.surfconext.nl -x -b 'cn=config' -D 'cn=admin,cn=config' \
    -W -f change_bdb.ldif
file change_bdb.ldif:

==================================== 
dn: olcDatabase={1}bdb,cn=config
changetype: modify
add: olcLimits
olcLimits: {0}dn.exact="cn=VERS Account,dc=surfconext,dc=nl" size=unlimited
-
====================================

### 5 Install database schema

To install the initial database, just call the 'migrate' script in *bin/*, like so:

    cd bin && ./migrate && cd ..

**NOTE**
Manage requires database settings, without it the install script will not function

### 6 Set up VERS reporting.

Edit */etc/surfconext/manage.ini* and add:

    vers.env = "test"

or
    vers.env = "production"

Beware: there is no Development environment for VERS,
so make sure you don't mess up things for other developers.

Create a cronjob to run the VERS export:

    [path to surfconext-admin]/scripts/versexport.sh

Run the surfconext-admin/scipts/versexport.sh script once a month,
on the first day of the month.
It will put the data of the previous month in VERS.

## Updating ##

It is recommended practice that you deploy the Management application in a directory that includes the version number and use a
symlink to link to the 'current' version of the Management application.

**EXAMPLE**

    .
    ..
    manage -> manage-v1.6.0
    manage-v1.5.0
    manage-v1.6.0

If you are using this pattern, an update can be done with the following:

1. Download and deploy a new version in a new directory.

2. Check out the release notes in docs/release_notes/X.Y.Z.md (where X.Y.Z is the version number) for any
   additional steps.

3. Change the symlink.