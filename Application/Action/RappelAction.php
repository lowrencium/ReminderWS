<?php
class RappelAction implements IAction
{
    public static function RegisterAction($server)
    {
        if($server instanceof SoapServer)
        {
            $server->addFunction("CreerRappel");
            $server->addFunction("RecupererRappel");
            $server->addFunction("SupprimerRappel");
            $server->addFunction("PartagerRappel");
        }
        elseif($server instanceof nusoap_server)
        {
            CreerRappelIO::addType($server);
            $server->register("CreerRappel", array("id" => "xsd:string", "token" => "xsd:string", "titre" => "xsd:string", "lieu" => "xsd:string", "debut" => "xsd:int", "fin" => "xsd:int"), array("return" => "tns:CreerRappelIO"));
            RecupererRappelIO::addType($server);
            $server->register("RecupererRappel", array("id" => "xsd:string", "token" => "xsd:string"), array("return" => "tns:RecupererRappelIO"));
            SupprimerRappelIO::addType($server);
            $server->register("SupprimerRappel", array("id" => "xsd:string", "rappelId" => "xsd:string", "token" => "xsd:string"), array("return" => "tns:SupprimerRappelIO"));
            PartagerRappelIO::addType($server);
            $server->register("PartagerRappel", array("id" => "xsd:string", "token" => "xsd:string", "rappelId" => "xsd:string", "contactId" => "xsd:string", "type" => "xsd:string"), array("return" => "tns:PartagerRappelIO"));
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

    if($fin < $debut)
        $fin = $debut;

    try
    {
        $dataAdapter = new RappelData();
        $dataAdapter->creerRappel($id, $titre, $lieu, substr($debut, 0, 10), substr($fin, 0, 10));
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $sortie->setResultat(true);
    return $sortie->toArray();
}

/**
 * @param string $id
 * @param string $token
 * @return array
 */
function RecupererRappel($id, $token)
{
    $sortie = new RecupererRappelIO();

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

    try
    {
        $dataAdapter = new RappelData();
        $data = $dataAdapter->recupererRappel($id);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    while($row = $data->fetch(PDO::FETCH_OBJ))
    {
        try
        {
            $rappel = new Rappel($row->id);
            $rappel->setTitre($row->description);
            $rappel->setLieu($row->lieu);
            $begin = DateTime::createFromFormat("Y-m-d h:i:s", $row->begin);
            $rappel->setBegin($begin->getTimestamp());
            $end = DateTime::createFromFormat("Y-m-d h:i:s", $row->end);
            $rappel->setEnd($end->getTimestamp());
            $lastUpdate = DateTime::createFromFormat("Y-m-d h:i:s", $row->lastUpdate);
            $rappel->setLastUpdate($lastUpdate->getTimestamp());
            $sortie->addRappel($rappel);
        }
        catch(Exception $e)
        {
            $sortie->setErreur($e);
            return $sortie->toArray();
        }
    }

    $sortie->setResultat(true);
    return $sortie->toArray();
}

/**
 * @param string $id
 * @param string $rappelId
 * @param string $token
 * @return array
 */
function SupprimerRappel($id, $rappelId, $token)
{
    $sortie = new SupprimerRappelIO();

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

    try
    {
        $dataAdapter = new RappelData();
        $data = $dataAdapter->supprimerRappel($id, $rappelId);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    if($data)
        $sortie->setResultat(true);
    else
        $sortie->setErreur(new Exception("Echec de la requête"));

    return $sortie->toArray();
}

/**
 * @param string $id
 * @param string $token
 * @param string $rappelId
 * @param string $contactId
 * @param string $type
 * @return array
 */
function PartagerRappel($id, $token, $rappelId, $contactId, $type)
{
    $sortie = new PartagerRappelIO();

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

    try
    {
        $dataAdapter = new RappelData();
        $data = $dataAdapter->partagerRappel($id, $rappelId, $contactId, $type);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    if($data)
        $sortie->setResultat(true);
    else
        $sortie->setErreur(new Exception("Erreur lors de l'execution de la requête"));

    return $sortie->toArray();
}