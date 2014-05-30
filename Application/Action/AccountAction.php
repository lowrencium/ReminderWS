<?php
class AccountAction implements IAction
{
    public static function RegisterAction($server)
    {
        if($server instanceof SoapServer)
        {
            $server->addFunction("Login");
        }
        elseif($server instanceof nusoap_server)
        {
            $server->register("Login", array("login" => "xsd:string", "password" => "xsd:string"), array("return" => "xsd:boolean"));
        }
    }
}

/**
 * @param string $login
 * @param string $password
 * @return bool
 */
function Login($login, $password)
{
    return true;
}