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

<VirtualHost *:80>
   DocumentRoot "D:/eanaya/_projects/cs/src/public"
   ServerName .local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development

   <Directory "D:/eanaya/_projects/cs/src/public">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>

</VirtualHost>



====================

#############
#     CS    #
#############
<VirtualHost *:80>
    ServerAdmin ernestex@gmail.com
    DocumentRoot "D:\eanaya\_projects\cs\src\public"
    ServerName devel.cs.info
    ErrorLog "logs/cs-error.log"
    CustomLog "logs/cs-access.log" common
    <Directory "D:\eanaya\_projects\cs\src\public">
        Options Indexes FollowSymLinks
        AllowOverride all
        Order Deny,Allow
        Deny from none
        Allow from all
    </Directory>
</VirtualHost>
<VirtualHost *:80>
    ServerAdmin ernestex@gmail.com
    DocumentRoot "D:\eanaya\_projects\cs\src\public\elements"
    ServerName e.devel.cs.info
    ErrorLog "logs/e-cs-error.log"
    CustomLog "logs/e-cs-access.log" common
    <Directory "D:\eanaya\_projects\cs\src\public\elements">
        Options Indexes FollowSymLinks
        AllowOverride all
        Order Deny,Allow
        Deny from none
        Allow from all
    </Directory>
</VirtualHost>
<VirtualHost *:80>
    ServerAdmin ernestex@gmail.com
    DocumentRoot "D:\eanaya\_projects\cs\src\public\static"
    ServerName s.devel.cs.info
    ErrorLog "logs/s-cs-error.log"
    CustomLog "logs/s-cs-access.log" common
    <Directory "D:\eanaya\_projects\cs\src\public\static">
        Options Indexes FollowSymLinks
        AllowOverride all
        Order Deny,Allow
        Deny from none
        Allow from all
    </Directory>
</VirtualHost>
