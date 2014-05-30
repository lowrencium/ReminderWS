<?php
    include("Core.php");
    include("Libs/NuSOAP/lib/nusoap.php");

    $server = new nusoap_server();
    $server->configureWSDL($_CONF["SERVER_NAME"]);
    $server->wsdl->schemaTargetNamespace = $_CONF["URL"].'/'.$_CONF["SERVER_NAME"].'/Server.php';
    $server->wsdl->ports[$_CONF["SERVER_NAME"]."Port"]["location"] = $_CONF["URL"].'/'.$_CONF["SERVER_NAME"].'/Server.php';

    include("Actions.php");

    $POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
    $server->service($POST_DATA);
    exit();