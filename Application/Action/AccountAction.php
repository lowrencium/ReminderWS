<?php
class AccountAction implements IAction
{
    public static function RegisterAction($server)
    {
        if($server instanceof SoapServer)
        {
            $server->addFunction("Login");
            $server->addFunction("Logout");
        }
        elseif($server instanceof nusoap_server)
        {
            LoginIO::addType($server);
            $server->register("Login", array("login" => "xsd:string", "password" => "xsd:string"), array("return" => "tns:LoginIO"));
            LogoutIO::addType($server);
            $server->register("Logout", array("token" => "xsd:string"), array("return" => "tns:LogoutIO"));
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
    $sortie = new LoginIO();
    try
    {
        $dataAdapter = new AccountData();
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }
    $user = null;

    try
    {
        $data = $dataAdapter->login($login);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    while($row = $data->fetch(PDO::FETCH_OBJ))
    {
        $salt = $row->salt;
        $salted = $password.'{'.$salt.'}';

        $digest = hash("sha512", $salted, true);

        for ($i = 1; $i < 5000; $i++) {
            $digest = hash("sha512", $digest.$salted, true);
        }

        $digest = base64_encode($digest);

        if($digest == $row->password)
        {
            $user = new User($row->id, $login);
            $sortie->setResultat(true);
            break;
        }
    }

    if($sortie->getResultat())
    {
        session_start();
        try
        {
            $data = $dataAdapter->getInfos($user->getId());
        }
        catch(Exception $e)
        {
            $sortie->setErreur($e);
            return $sortie->toArray();
        }
        $row = $data->fetch(PDO::FETCH_OBJ);
        $user->setFirstName($row->firstname);
        $user->setLastName($row->lastname);
        $user->setEmail($row->email);
        $sortie->setUser($user);
        $_SESSION["USER"] = $user;
    }

    return $sortie->toArray();
}

function Logout($token)
{
    Helper::tokenManager($token);
    $sortie = new LogoutIO();

    $sortie->setResultat(session_destroy());

    return $sortie;
}