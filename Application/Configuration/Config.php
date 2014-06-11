<?php
    if(getenv("APPLICATION_ENV") == "prod")
    {
        $_CONF["URL"] = "aminemanzou.com";
        $_CONF["SERVER_NAME"] = "remindme-webservice";
        $_CONF["SOAP_CACHE"] = "1";
    }
    else
    {
        $_CONF["URL"] = "http://127.0.0.1";
        $_CONF["SERVER_NAME"] = "ReminderWS";
        $_CONF["SOAP_CACHE"] = "0";
    }


    $_CONF["BDD_URL"] = "127.0.0.1";
    $_CONF["BDD_TYPE"] = "mysql";
    $_CONF["BDD_USER"] = "root";
    $_CONF["BDD_PASSWORD"] = "";
    $_CONF["BDD_BASE"] = "reminder";