<?php
    if(file_exists("Application/Action/IAction.php"))
        require_once("Application/Action/IAction.php");
    else
        throw new Exception("Erreur interne au serveur");

    foreach (glob("Application/Action/*Action.php") as $filename)
    {
        require_once($filename);
    }

    foreach (glob("Application/Data/*Data.php") as $filename)
    {
        require_once($filename);
    }

    foreach (glob("Application/IO/*IO.php") as $filename)
    {
        require_once($filename);
    }

    foreach (glob("Application/Model/*.php") as $filename)
    {
        require_once($filename);
    }