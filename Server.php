<?php
    include("Core.php");

    ini_set("soap.wsdl_cache_enabled", $_CONF["SOAP_CACHE"]);

    $server = new SoapServer($_CONF["URL"].'/'.$_CONF["SERVER_NAME"].'/Service.php?wsdl');

    include("Actions.php");

    $server->handle();
