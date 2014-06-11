<?php
    require_once("Application/Action/IAction.php");

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