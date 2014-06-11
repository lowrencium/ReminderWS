<?php
    if(file_exists("Core.php") && file_exists("Libs/NuSOAP/lib/nusoap.php"))
    {
        include("Core.php");
        include("Libs/NuSOAP/lib/nusoap.php");
    }
    else
        throw new Exception("Erreur interne au serveur");

    $server = new nusoap_server();
    $server->configureWSDL($_CONF["SERVER_NAME"]);

    if(getenv("APPLICATION_ENV") == "prod")
    {
        //Target en prod
        $server->wsdl->schemaTargetNamespace = 'http://'.$_CONF["SERVER_NAME"].'.'.$_CONF["URL"].'/Server.php';
        $server->wsdl->ports[$_CONF["SERVER_NAME"]."Port"]["location"] = 'http://'.$_CONF["SERVER_NAME"].'.'.$_CONF["URL"].'/Server.php';
    }
    else
    {
        //Target de dev
        $server->wsdl->schemaTargetNamespace = $_CONF["URL"].'/'.$_CONF["SERVER_NAME"].'/Server.php';
        $server->wsdl->ports[$_CONF["SERVER_NAME"]."Port"]["location"] = $_CONF["URL"].'/'.$_CONF["SERVER_NAME"].'/Server.php';
    }

    if(file_exists("Actions.php"))
        include("Actions.php");
    else
        throw new Exception("Erreur interne au serveur");

    $POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
    $server->service($POST_DATA);
    exit();