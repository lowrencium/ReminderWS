<?php
class ContactAction implements IAction
{
    public static function RegisterAction($server)
    {
        if($server instanceof SoapServer)
        {
            $server->addFunction("AjouterContact");
            $server->addFunction("RecupererContacts");
            $server->addFunction("SupprimerContact");
        }
        elseif($server instanceof nusoap_server)
        {
            AjouterContactIO::addType($server);
            $server->register("AjouterContact", array("id" => "xsd:string", "token" => "xsd:string", "nom" => "xsd:string", "email" => "xsd:string", "telephone" => "xsd:string", "adresse" => "xsd:string"), array("return" => "tns:AjouterContactIO"));
            RecupererContactsIO::addType($server);
            $server->register("RecupererContacts", array("id" => "xsd:string", "token" => "xsd:string"), array("return" => "tns:RecupererContactsIO"));
            SupprimerContactIO::addType($server);
            $server->register("SupprimerContact", array("id" => "xsd:string", "token" => "xsd:string", "email" => "xsd:string"), array("return" => "tns:SupprimerContactIO"));
        }
    }
}

/**
 * @param string $id
 * @param string $token
 * @param string $nom
 * @param string $email
 * @param string $telephone
 * @param string $adresse
 * @return array
 */
function AjouterContact($id, $token, $nom, $email, $telephone, $adresse)
{
    $sortie = new AjouterContactIO();

    try
    {
        $dataAdapter = new AccountData();
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    try
    {
        $dataAdapter = new ContactData();
        $data = $dataAdapter->RecupererContacts($id);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $row = $data->fetch(PDO::FETCH_OBJ);
    if(empty($row->contacts))
    {
        $result = array();
    }
    else
    {
        $result = json_decode($row->contacts, true);
    }

    if(is_array($result))
    {
        if(!array_key_exists($email, $result))
        {
            $result[$email] = array(
                "Nom" => $nom,
                "Telephone" => $telephone,
                "Adresse" => $adresse
            );
        }
        else
        {
            $sortie->setErreur(new Exception("Ce contact existe déjà"));
            return $sortie->toArray();
        }
    }
    else
    {
        $sortie->setErreur(new Exception("Format de données incorrect"));
        return $sortie->toArray();
    }

    try
    {
        $data = $dataAdapter->SetContacts($id, json_encode($result));
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
 * @return array
 */
function RecupererContacts($id, $token)
{
    $sortie = new RecupererContactsIO();

    try
    {
        $dataAdapter = new AccountData();
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    try
    {
        $dataAdapter = new ContactData();
        $data = $dataAdapter->RecupererContacts($id);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $row = $data->fetch(PDO::FETCH_OBJ);
    if(empty($row->contacts))
    {
        $result = array();
    }
    else
    {
        $result = json_decode($row->contacts, true);
    }

    foreach($result as $key=>$value)
    {
        $contact = new Contact($key);
        $contact->setNom($value["Nom"]);
        $contact->setTelephone($value["Telephone"]);
        $contact->setAdresse($value["Adresse"]);
        $sortie->addContact($contact);
    }

    $sortie->setResultat(true);
    return $sortie->toArray();
}

/**
 * @param string $id
 * @param string $token
 * @param string $email
 * @return array
 */
function SupprimerContact($id, $token, $email)
{
    $sortie = new SupprimerContactIO();

    try
    {
        $dataAdapter = new AccountData();
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    try
    {
        $dataAdapter = new ContactData();
        $data = $dataAdapter->RecupererContacts($id);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $row = $data->fetch(PDO::FETCH_OBJ);
    if(empty($row->contacts))
    {
        $result = array();
    }
    else
    {
        $result = json_decode($row->contacts, true);
    }

    if(array_key_exists($email, $result))
    {
        unset($result[$email]);
        try
        {
            $data = $dataAdapter->SetContacts($id, json_encode($result));
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
    }
    else
    {
        $sortie->setErreur(new Exception("Contact inconnu"));
    }

    return $sortie->toArray();
}