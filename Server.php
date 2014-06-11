<?php
    if(file_exists("Core.php"))
        include("Core.php");
    else
        throw new Exception("Erreur interne au serveur");

    ini_set("soap.wsdl_cache_enabled", $_CONF["SOAP_CACHE"]);

    if(getenv("APPLICATION_ENV") == "prod")
    {
        $server = new SoapServer('http://'.$_CONF["SERVER_NAME"].'.'.$_CONF["URL"].'/Service.php?wsdl');
    }
    else
    {
        $server = new SoapServer($_CONF["URL"].'/'.$_CONF["SERVER_NAME"].'/Service.php?wsdl');
    }

    include("Actions.php");

    $server->handle();
