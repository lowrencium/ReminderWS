<?php

interface IAction
{
    /**
     * @param SoapServer|nusoap_server $server
     */
    public static function RegisterAction($server);
}