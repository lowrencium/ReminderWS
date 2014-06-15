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
            $server->addFunction("ValiderContact");
        }
        elseif($server instanceof nusoap_server)
        {
            AjouterContactIO::addType($server);
            $server->register("AjouterContact", array("id" => "xsd:string", "token" => "xsd:string", "nom" => "xsd:string", "email" => "xsd:string", "telephone" => "xsd:string", "adresse" => "xsd:string"), array("return" => "tns:AjouterContactIO"));
            RecupererContactsIO::addType($server);
            $server->register("RecupererContacts", array("id" => "xsd:string", "token" => "xsd:string"), array("return" => "tns:RecupererContactsIO"));
            SupprimerContactIO::addType($server);
            $server->register("SupprimerContact", array("id" => "xsd:string", "token" => "xsd:string", "contactId" => "xsd:string", "type" => "xsd:string"), array("return" => "tns:SupprimerContactIO"));
            ValiderContactIO::addType($server);
            $server->register("ValiderContact", array("id" => "xsd:string", "token" => "xsd:string", "contactId" => "xsd:string"), array("return" => "tns:ValiderContactIO"));
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
        $data = $dataAdapter->checkContact($email);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    $row = $data->fetch(PDO::FETCH_OBJ);
    if($row->nbUser == 1)
    {
        try
        {
            $data = $dataAdapter->addUserContact($id, $email);
        }
        catch(Exception $e)
        {
            $sortie->setErreur($e);
            return $sortie->toArray();
        }
    }
    else
    {
        try
        {
            $data = $dataAdapter->addGuestContact($id, $nom, $email, $telephone, $adresse);
        }
        catch(Exception $e)
        {
            $sortie->setErreur($e);
            return $sortie->toArray();
        }
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
        $data = $dataAdapter->recupererUserContacts($id);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    while($row = $data->fetch(PDO::FETCH_OBJ))
    {
        $contact = new Contact($row->id);
        $contact->setNom($row->firstname." ".$row->lastname);
        $contact->setEmail($row->email);
        $contact->setAdresse("");
        $contact->setTelephone($row->phone);
        $contact->setType("User");
        $sortie->addContact($contact);
    }

    try
    {
        $dataAdapter = new ContactData();
        $data = $dataAdapter->recupererGuestContacts($id);
    }
    catch(Exception $e)
    {
        $sortie->setErreur($e);
        return $sortie->toArray();
    }

    while($row = $data->fetch(PDO::FETCH_OBJ))
    {
        $contact = new Contact($row->id);
        $contact->setNom($row->name);
        $contact->setEmail($row->email);
        $contact->setAdresse($row->adress);
        $contact->setTelephone($row->phone);
        $contact->setType("Guest");
        $sortie->addContact($contact);
    }

    $sortie->setResultat(true);
    return $sortie->toArray();
}

/**
 * @param string $id
 * @param string $token
 * @param string $contactId
 * @param string $type
 * @return array
 */
function SupprimerContact($id, $token, $contactId, $type)
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
        $data = $dataAdapter->supprimerContact($id, $contactId, $type);
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

/**
 * @param string $id
 * @param string $token
 * @param string $contactId
 * @return array
 */
function ValiderContact($id, $token, $contactId)
{
    $sortie = new ValiderContactIO();

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
        $data = $dataAdapter->validerContact($id, $contactId);
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