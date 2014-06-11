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
            LoginIO::addType($server);
            $server->register("Login", array("login" => "xsd:string", "password" => "xsd:string"), array("return" => "tns:LoginIO"));
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
    $dataAdapter = new AccountData();
    $sortie = new LoginIO();
    $user = null;

    $data = $dataAdapter->login($login);

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
        $data = $dataAdapter->getInfos($user->getId());
        $row = $data->fetch(PDO::FETCH_OBJ);
        $user->setFirstName($row->firstname);
        $user->setLastName($row->lastname);
        $user->setEmail($row->email);
        $sortie->setUser($user);
    }

    return $sortie->toArray();
}