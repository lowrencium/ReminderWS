# Reminder Webservice Repository

> This project is the webservice part of the Remind Me project.

## Project Installation

To install the project :

```sh
cp Application/Config.php.dist Application/Config.php
vim Application/Config.php
```

And Configure the file with your local settings.

To run the project here is a Virtual Host for apache :

```sh
<Virtualhost *:80>
    DocumentRoot "/path/to/project/"
    ServerName reminderWS.dev
    ServerAlias reminderWS.dev
    <Directory /path/to/project/reminderWS/>
        Options Indexes FollowSymlinks ExecCGI Includes
        AllowOverride All
        Order allow,deny
        Allow from All
    </Directory>    
    Errorlog "/var/log/reminderWS.dev.log"
</Virtualhost>
```



