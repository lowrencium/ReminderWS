<?php
    require_once("Application/Action/IAction.php");

    foreach (glob("Application/Action/*Action.php") as $filename)
    {
        require_once($filename);
    }