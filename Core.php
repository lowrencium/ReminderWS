<?php
    $_CONF = array();
    if(file_exists("Application/Configuration/Config.php"))
        include("Application/Configuration/Config.php");
    else
        throw new Exception("Erreur interne au serveur");

    $GLOBALS["CONF"] = $_CONF;

    if(file_exists("Includer.php"))
        include("Includer.php");
    else
        throw new Exception("Erreur interne au serveur");