<?php
class RappelAction implements IAction
{
    public static function RegisterAction($server)
    {
        if($server instanceof SoapServer)
        {
            $server->addFunction("CreerRappel");
        }
        elseif($server instanceof nusoap_server)
        {
            CreerRappelIO::addType($server);
            $server->register("CreerRappel", array("id" => "xsd:string", "token" => "xsd:string", "titre" => "xsd:string", "lieu" => "xsd:string", "debut" => "xsd:int", "fin" => "xsd:int"), array("return" => "tns:CreerRappelIO"));
        }
    }
}

/**
 * @param string $id
 * @param string $token
 * @param string $titre
 * @param string $lieu
 * @param int $debut
 * @param int $fin
 * @return array
 */
function CreerRappel($id, $token, $titre, $lieu, $debut, $fin)
{
    $sortie = new CreerRappelIO();

    try
    {
        $dataAdapter = new AccountData();
        $data = $dataAdapter->checkToken($id, $token);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    //Test si token ok

    $titre = htmlspecialchars(htmlentities($titre));
    $lieu = htmlspecialchars(htmlentities($lieu));

    try
    {
        $dataAdapter = new RappelData();
        $dataAdapter->creerRappel($titre, $lieu, substr($debut, 0, 9), substr($fin, 0, 9));
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $sortie->setResultat(true);
    return $sortie->toArray();
}