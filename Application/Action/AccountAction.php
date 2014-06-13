<?php
class AccountAction implements IAction
{
    public static function RegisterAction($server)
    {
        if($server instanceof SoapServer)
        {
            $server->addFunction("Login");
            $server->addFunction("Logout");
            $server->addFunction("SignIn");
        }
        elseif($server instanceof nusoap_server)
        {
            LoginIO::addType($server);
            $server->register("Login", array("login" => "xsd:string", "password" => "xsd:string"), array("return" => "tns:LoginIO"));
            LogoutIO::addType($server);
            $server->register("Logout", array("token" => "xsd:string"), array("return" => "tns:LogoutIO"));
            SignInIO::addType($server);
            $server->register("SignIn", array("username" => "xsd:string", "prenom" => "xsd:string", "nom" => "xsd:string", "email" => "xsd:string", "type" => "xsd:int", "password" => "xsd:string"), array("return" => "tns:SignInIO"));
        }
    }
}

/**
 * @param string $login
 * @param string $password
 * @return array
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

        $digest = Helper::encodePassword($salted);

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

/**
 * @param $token
 * @return LogoutIO
 */
function Logout($token)
{
    Helper::tokenManager($token);
    $sortie = new LogoutIO();

    $sortie->setResultat(session_destroy());

    return $sortie->toArray();
}

function SignIn($username, $prenom, $nom, $email, $type, $password)
{
    $sortie = new SignInIO();
    try
    {
        $dataAdapter = new AccountData();
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $salt = md5(uniqid(null, true));
    $salted = $password.'{'.$salt.'}';
    $digest = Helper::encodePassword($salted);

    try
    {
        //$dataAdapter->addUser($username, $digest, $salt, $prenom, $nom, $email, $type);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $sortie->setResultat(true);
    return $sortie->toArray();
}