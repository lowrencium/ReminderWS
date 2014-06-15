<?php
class RecupererDemandesContactIO extends DefaultIO
{
    /**
     * @var bool
     */
    private $_resultat = false;

    /**
     * @var Contact[]
     */
    private $_contacts = array();

    /**
     * @param bool $resultat
     * @throws Exception
     */
    public function setResultat($resultat)
    {
        if(is_bool($resultat))
        {
            $this->_resultat = $resultat;
        }
        else
        {
            throw new Exception("Booléen requis");
        }
    }

    /**
     * @return bool
     */
    public function getResultat()
    {
        return $this->_resultat;
    }

    /**
     * @param Contact $contact
     */
    public function addContact($contact)
    {
        if($contact instanceof Contact)
            $this->_contacts[] = $contact;
        else
            throw new Exception("L'objet doit être de type Contact");
    }

    /**
     * @return array
     */
    private function contactsToArray()
    {
        $result = array();
        foreach($this->_contacts as $contact)
        {
            $result[] = $contact->toArray();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array(
            "Resultat" => $this->_resultat,
            "Token" => "",
            "Contacts" => $this->contactsToArray(),
            "Erreur" => $this->_erreur
        );

        return $result;
    }

    /**
     * @param nusoap_server $serveur
     */
    public static function addType($serveur)
    {
        Contact::addType($serveur);

        $serveur->wsdl->addComplexType(
            'ContactArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(
                array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Contact[]')
            ),
            'tns:Contact'
        );

        $serveur->wsdl->addComplexType(
            "RecupererDemandesContactIO",
            "complexType",
            "struct",
            "all",
            "",
            array(
                "Resultat" => array("name" => "Resultat", "type" => "xsd:boolean"),
                "Token" => array("name" => "Token", "type" => "xsd:string"),
                "Contacts" => array("name" => "Contacts", "type" => "tns:ContactArray"),
                "Erreur" => array("name" => "Erreur", "type" => "xsd:string")
            )
        );
    }
}